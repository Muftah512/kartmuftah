@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تفاصيل نقطة البيع</h1>
        <a href="{{ route('accountant.pos.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <i class="fas fa-arrow-left mr-1"></i> رجوع
        </a>
    </div>

   <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">إعادة تعيين كلمة المرور</h2>
        
        <form action="{{ route('accountant.pos.reset-password', $pointOfSale->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة</label>
                    <input type="password" name="new_password" id="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-key mr-2"></i> تعيين كلمة المرور
                    </button>
                </div>
            </div>
        </form>
        
        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
                @if(session('mail_sent'))
                    <p class="mt-2">تم إرسال كلمة المرور الجديدة إلى صاحب نقطة البيع</p>
                @else
                    <p class="mt-2 text-yellow-700">لم يتم إرسال البريد الإلكتروني، يرجى إبلاغ صاحب نقطة البيع يدويًا</p>
                @endif
            </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="border-r border-gray-200 pr-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">معلومات أساسية</h2>
                <p><span class="font-medium">الاسم:</span> {{ $pointOfSale->name }}</p>
                <p><span class="font-medium">الموقع:</span> {{ $pointOfSale->location }}</p>
                <p><span class="font-medium">الهاتف:</span> {{ $pointOfSale->phone }}</p>
                <p class="mt-2">
                    <span class="font-medium">الحالة:</span>
                    @if($pointOfSale->is_active)
                        <span class="text-green-600 font-bold">نشطة</span>
                    @else
                        <span class="text-red-600 font-bold">غير نشطة</span>
                    @endif
                </p>
            </div>

            <div class="border-r border-gray-200 pr-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">الرصيد والعمليات</h2>
                <p class="text-xl font-bold text-blue-600 mb-2">
                    {{ number_format($pointOfSale->balance) }} ر.ي
                </p>
                <p><span class="font-medium">آخر شحن:</span> 
                    @if($lastRecharge)
                        {{ $lastRecharge->created_at->format('d/m/Y') }} ({{ number_format($lastRecharge->amount) }} ر.ي)
                    @else
                        لا يوجد
                    @endif
                </p>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">إجراءات سريعة</h2>
                <div class="space-y-2">
                    <a href="{{ route('accountant.recharges.create', ['point_id' => $pointOfSale->id]) }}" class="block w-full bg-green-100 hover:bg-green-200 text-green-800 py-2 px-4 rounded-lg text-center transition">
                        شحن رصيد
                    </a>
                    <a href="{{ route('accountant.invoices.create', ['point_id' => $pointOfSale->id]) }}" class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-800 py-2 px-4 rounded-lg text-center transition">
                        إنشاء فاتورة
                    </a>
                    <a href="#" class="block w-full bg-purple-100 hover:bg-purple-200 text-purple-800 py-2 px-4 rounded-lg text-center transition">
                        تعديل النقطة
                    </a>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4">سجل الشحنات</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pointOfSale->recharges as $recharge)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            {{ number_format($recharge->amount) }} ر.ي
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($recharge->payment_method === 'cash')
                                نقدي
                            @elseif($recharge->payment_method === 'bank_transfer')
                                تحويل بنكي
                            @else
                                بطاقة
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $recharge->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $recharge->notes ?: 'لا يوجد ملاحظات' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            لا يوجد شحنات سابقة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection