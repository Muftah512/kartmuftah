@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">لوحة تحكم المحاسب</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- بطاقة نقاط البيع -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-store text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">نقاط البيع</p>
                    <p class="text-2xl font-bold">{{ $totalPoints }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('accountant.pos.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm">
                    <i class="fas fa-eye mr-2"></i> عرض جميع نقاط البيع
                </a>
            </div>
        </div>

        <!-- بطاقة الفواتير المعلقة -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-file-invoice text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">الفواتير المعلقة</p>
                    <p class="text-2xl font-bold">{{ $pendingInvoices }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('accountant.invoices.index') }}" class="text-green-600 hover:text-green-800 flex items-center text-sm">
                    <i class="fas fa-list mr-2"></i> عرض الفواتير
                </a>
            </div>
        </div>

        <!-- بطاقة الشحنات الحديثة -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">آخر الشحنات</p>
                    <p class="text-2xl font-bold">{{ $recentRecharges->count() }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('accountant.recharges.index') }}" class="text-purple-600 hover:text-purple-800 flex items-center text-sm">
                    <i class="fas fa-history mr-2"></i> عرض سجل الشحنات
                </a>
            </div>
        </div>
    </div>

    <!-- قسم آخر عمليات الشحن -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">آخر عمليات الشحن</h2>
            <a href="{{ route('accountant.recharges.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                عرض الكل <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </div>
        
        @if($recentRecharges->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نقطة البيع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentRecharges as $recharge)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $recharge->pointOfSale->name ?? 'غير معروف' }}</div>
                            <div class="text-sm text-gray-500">{{ $recharge->pointOfSale->location ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            {{ number_format($recharge->amount) }} ر.ي
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($recharge->payment_method === 'cash')
                                نقدي
                            @elseif($recharge->payment_method === 'bank_transfer')
                                تحويل بنكي
                            @elseif($recharge->payment_method === 'card')
                                بطاقة
                            @else
                                {{ $recharge->payment_method }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $recharge->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-money-bill-wave text-gray-300 text-4xl mb-3"></i>
            <p class="text-gray-500">لا توجد عمليات شحن حديثة</p>
            <a href="{{ route('accountant.recharges.create') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i> إضافة شحنة جديدة
            </a>
        </div>
        @endif
    </div>

    <!-- قسم نقاط البيع الخاصة بي -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">نقاط البيع الخاصة بي</h2>
            
            @if($myPoints->count() > 0)
            <div class="space-y-4">
                @foreach($myPoints as $point)
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $point->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $point->location }}</p>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-blue-600">
                            {{ number_format($point->balance) }} ر.ي
                        </div>
                        <div class="text-xs text-gray-500 text-left mt-1">
                            @if($point->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">نشطة</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">غير نشطة</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('accountant.pos.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    عرض جميع نقاط البيع <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-store text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">لا توجد نقاط بيع مسجلة</p>
                <a href="{{ route('accountant.pos.create') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i> إضافة نقطة بيع جديدة
                </a>
            </div>
            @endif
        </div>

        <!-- قسم الإجراءات السريعة -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">الإجراءات السريعة</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('accountant.recharges.create') }}" class="bg-green-100 text-green-800 hover:bg-green-200 rounded-lg p-4 text-center transition flex flex-col items-center justify-center">
                    <i class="fas fa-plus-circle text-xl mb-2"></i>
                    <p>شحن رصيد</p>
                </a>
                <a href="{{ route('accountant.invoices.create') }}" class="bg-blue-100 text-blue-800 hover:bg-blue-200 rounded-lg p-4 text-center transition flex flex-col items-center justify-center">
                    <i class="fas fa-file-invoice text-xl mb-2"></i>
                    <p>إنشاء فاتورة</p>
                </a>
                <a href="{{ route('accountant.pos.create') }}" class="bg-purple-100 text-purple-800 hover:bg-purple-200 rounded-lg p-4 text-center transition flex flex-col items-center justify-center">
                    <i class="fas fa-store text-xl mb-2"></i>
                    <p>إضافة نقطة بيع</p>
                </a>
                <a href="{{ route('accountant.invoices.index') }}" class="bg-orange-100 text-orange-800 hover:bg-orange-200 rounded-lg p-4 text-center transition flex flex-col items-center justify-center">
                    <i class="fas fa-list text-xl mb-2"></i>
                    <p>عرض الفواتير</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection