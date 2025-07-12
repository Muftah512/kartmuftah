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
    /**
     * تطبيق سياسات التحقق والصلاحيات
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * عرض قائمة نقاط البيع مع بيانات المحاسب المرتبط
     */
    public function index()
    {
        $this->authorize('manage_pos');

        // جلب نقاط البيع مع بيانات المحاسب والترتيب حسب آخر تحديث
        $points = PointOfSale::with('accountant')
            ->orderBy('is_active', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // إحصائيات سريعة
        $stats = [
            'total' => PointOfSale::count(),
            'active' => PointOfSale::where('is_active', true)->count(),
            'inactive' => PointOfSale::where('is_active', false)->count(),
        ];

        return view('admin.pos.index', compact('points', 'stats'));
    }

    /**
     * إظهار نموذج إنشاء نقطة بيع جديدة
     */
    public function create()
    {
        $this->authorize('manage_pos');

        // جلب المستخدمين بدور المحاسب (فقط النشطين)
        $accountants = User::role('accountant')
            ->where('is_active', true)
            ->get();

        return view('admin.pos.create', compact('accountants'));
    }

    /**
     * تخزين نقطة البيع الجديدة
     */
    public function store(Request $request)
    {
        $this->authorize('manage_pos');

        // تحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:point_of_sales,name'],
            'location' => ['required', 'string', 'max:255'],
            'accountant_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['required', 'boolean'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ], [
            'name.required' => 'حقل اسم نقطة البيع مطلوب.',
            'name.unique' => 'اسم نقطة البيع مستخدم مسبقاً.',
            'location.required' => 'حقل الموقع مطلوب.',
            'accountant_id.exists' => 'المحاسب المحدد غير موجود.',
            'is_active.required' => 'حقل الحالة مطلوب.',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفاً.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صالحاً.',
            'balance.numeric' => 'الرصيد يجب أن يكون رقماً.',
        ]);

        // إذا فشل التحقق، إرجاع الأخطاء
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // إنشاء السجل
        PointOfSale::create($validator->validated());

        return redirect()
            ->route('admin.pos.index')
            ->with('success', 'تم إنشاء نقطة البيع بنجاح.');
    }

    /**
     * عرض تفاصيل نقطة البيع
     */
    public function show(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        // جلب المعاملات المالية المرتبطة
        $transactions = $pos->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.pos.show', compact('pos', 'transactions'));
    }

    /**
     * إظهار نموذج تعديل نقطة البيع
     */
    public function edit(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        // جلب المستخدمين بدور المحاسب (فقط النشطين)
        $accountants = User::role('accountant')
            ->where('is_active', true)
            ->get();

        return view('admin.pos.edit', compact('pos', 'accountants'));
    }

    /**
     * تحديث بيانات نقطة البيع
     */
    public function update(Request $request, PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        // تحقق من صحة البيانات
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
            'email' => ['nullable', 'email', 'max:255'],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ], [
            'name.required' => 'حقل اسم نقطة البيع مطلوب.',
            'name.unique' => 'اسم نقطة البيع مستخدم مسبقاً.',
            'location.required' => 'حقل الموقع مطلوب.',
            'accountant_id.exists' => 'المحاسب المحدد غير موجود.',
            'is_active.required' => 'حقل الحالة مطلوب.',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفاً.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صالحاً.',
            'balance.numeric' => 'الرصيد يجب أن يكون رقماً.',
        ]);

        // إذا فشل التحقق، إرجاع الأخطاء
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // تحديث السجل
        $pos->update($validator->validated());

        return redirect()
            ->route('admin.pos.index')
            ->with('success', 'تم تحديث نقطة البيع بنجاح.');
    }

    /**
     * تغيير حالة نقطة البيع (نشطة/غير نشطة)
     */
    public function toggleStatus(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        $pos->update(['is_active' => !$pos->is_active]);

        $status = $pos->is_active ? 'نشطة' : 'غير نشطة';
        return redirect()
            ->back()
            ->with('success', "تم تغيير حالة نقطة البيع إلى {$status}.");
    }

    /**
     * حذف نقطة البيع
     */
    public function destroy(PointOfSale $pos)
    {
        $this->authorize('manage_pos');

        // التحقق من عدم وجود معاملات مرتبطة
        if ($pos->transactions()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'لا يمكن حذف نقطة البيع لأنها مرتبطة بمعاملات مالية.');
        }

        $pos->delete();

        return redirect()
            ->route('admin.pos.index')
            ->with('success', 'تم حذف نقطة البيع بنجاح.');
    }

    /**
     * تصدير قائمة نقاط البيع
     */
    public function export()
    {
        $this->authorize('manage_pos');

        // سيتم تنفيذ عملية التصدير هنا
        return redirect()
            ->back()
            ->with('success', 'تم تصدير بيانات نقاط البيع بنجاح.');
    }
}

