<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointOfSale;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PointOfSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $this->authorize('manage_pos');

        $points = PointOfSale::with('accountant')
            ->orderBy('is_active', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => PointOfSale::count(),
            'active' => PointOfSale::where('is_active', true)->count(),
            'inactive' => PointOfSale::where('is_active', false)->count(),
        ];

        return view('admin.pos.index', compact('points', 'stats'));
    }

    public function create()
    {
        $this->authorize('manage_pos');

        $accountants = User::role('accountant')
            ->where('is_active', true)
            ->get();

        return view('admin.pos.create', compact('accountants'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage_pos');

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:point_of_sales,name'],
            'location' => ['required', 'string', 'max:255'],
            'accountant_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['required', 'boolean'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:point_of_sales,email'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['password'] = bcrypt($data['password']);

        PointOfSale::create($data);

        return redirect()->route('admin.pos.index')->with('success', 'تم إنشاء نقطة البيع بنجاح.');
    }

    public function show(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        $transactions = $pos->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.pos.show', compact('pos', 'transactions'));
    }

    public function edit(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        $accountants = User::role('accountant')
            ->where('is_active', true)
            ->get();

        return view('admin.pos.edit', compact('pos', 'accountants'));
    }

    public function update(Request $request, PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('point_of_sales')->ignore($pos->id)
            ],
            'location' => ['required', 'string', 'max:255'],
            'accountant_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['required', 'boolean'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pos->update($validator->validated());

        return redirect()->route('admin.pos.index')->with('success', 'تم تحديث نقطة البيع بنجاح.');
    }

    public function toggleStatus(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        $pos->update(['is_active' => !$pos->is_active]);

        $status = $pos->is_active ? 'نشطة' : 'غير نشطة';
        return redirect()->back()->with('success', "تم تغيير حالة نقطة البيع إلى {$status}.");
    }

    public function destroy(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        if ($pos->transactions()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف نقطة البيع لأنها مرتبطة بمعاملات مالية.');
        }

        $pos->delete();

        return redirect()->route('admin.pos.index')->with('success', 'تم حذف نقطة البيع بنجاح.');
    }

    public function export()
    {
        $this->authorize('manage_pos');

        return redirect()->back()->with('success', 'تم تصدير بيانات نقاط البيع بنجاح.');
    }
}
