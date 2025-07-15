<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointOfSale as Pos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StorePosRequest;
use App\Http\Requests\Admin\UpdatePosRequest;

class PosController extends Controller
{
    public function __construct()
    {
        // تأكد من المصادقة ودور 'admin' على جميع الدوال
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * عرض قائمة نقاط البيع.
     */
    public function index()
    {
        Gate::authorize('view-any-pos');

        $points = Pos::latest()->paginate(10);

        return view('admin.pos.index', compact('poses'));
    }

    /**
     * حفظ نقطة بيع جديدة.
     */
    public function store(StorePosRequest $request)
    {
        Gate::authorize('create-pos');

        Pos::create($request->validated());

        return redirect()
            ->route('admin.pos.index')
            ->with('success', 'تم إضافة نقطة البيع بنجاح.');
    }

    /**
     * إظهار نموذج تعديل نقطة بيع.
     */
    public function edit(Pos $pos)
    {
        Gate::authorize('view-pos', $pos);

        return view('admin.pos.edit', compact('pos'));
    }

    /**
     * تحديث بيانات نقطة البيع.
     */
    public function update(UpdatePosRequest $request, Pos $pos)
    {
        Gate::authorize('update-pos', $pos);

        $pos->update($request->validated());

        return redirect()
            ->route('admin.pos.index')
            ->with('success', 'تم تحديث نقطة البيع بنجاح.');
    }

    /**
     * حذف نقطة البيع.
     */
    public function destroy(Pos $pos)
    {
        Gate::authorize('delete-pos', $pos);

        $pos->delete();

        return redirect()
            ->route('admin.pos.index')
            ->with('success', 'تم حذف نقطة البيع.');
    }

    /**
     * تفعيل/تعطيل نقطة البيع (AJAX).
     */
    public function toggleStatus(Request $request, Pos $pos)
    {
        Gate::authorize('update-pos', $pos);

        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $pos->update(['is_active' => $validated['is_active']]);

        return response()->json([
            'success'   => true,
            'message'   => 'تم تحديث الحالة بنجاح',
            'is_active' => $pos->is_active
        ]);
    }
}
