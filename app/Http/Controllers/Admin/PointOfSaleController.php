<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointOfSale;

class PointOfSaleController extends Controller
{
    public function index()
    {
        $this->authorize('manage_pos');

        $points = PointOfSale::with('supervisor')->paginate(10);

        return view('admin.pos.index', compact('points'));
    }

    public function create()
    {
        $this->authorize('manage_pos');

        // تأكد من استخدام اسم الدور الصحيح باللغة الإنجليزية دون ترميز غريب
        $supervisors = User::role('supervisor')->get();

        return view('admin.pos.create', compact('supervisors'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage_pos');

        // عدّل هنا حقول الـ validation حسب الحقول الفعلية في جدول POS
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'location'       => ['required', 'string', 'max:255'],
            'supervisor_id'  => ['nullable', 'exists:users,id'],
            // ... أضف أي حقول أخرى عندك
        ]);

        PointOfSale::create($validated);

        return redirect()->route('admin.pos.index')
                         ->with('success', 'تم إنشاء نقطة البيع بنجاح.');
    }
}
