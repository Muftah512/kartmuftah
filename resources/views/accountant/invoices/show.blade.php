@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تفاصيل الفاتورة #{{ $invoice->id }}</h1>
        <a href="{{ route('accountant.invoices.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <i class="fas fa-arrow-left mr-1"></i> رجوع
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">معلومات الفاتورة</h2>
                <p><span class="font-medium">رقم الفاتورة:</span> #{{ $invoice->id }}</p>
                <p><span class="font-medium">التاريخ:</span> {{ $invoice->created_at->format('d/m/Y') }}</p>
                <p><span class="font-medium">تاريخ الاستحقاق:</span> {{ $invoice->due_date->format('d/m/Y') }}</p>
                <p class="mt-2">
                    <span class="font-medium">الحالة:</span>
                    @if($invoice->status === 'paid')
                        <span class="text-green-600 font-bold">مدفوعة</span>
                    @elseif($invoice->status === 'pending')
                        <span class="text-yellow-600 font-bold">معلقة</span>
                    @else
                        <span class="text-red-600 font-bold">متأخرة</span>
                    @endif
                </p>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">الطرفان</h2>
                <div class="mb-4">
                    <p class="font-medium">المحاسب:</p>
                    <p>{{ $invoice->accountant->name }}</p>
                    <p>{{ $invoice->accountant->email }}</p>
                </div>
                <div>
                    <p class="font-medium">نقطة البيع:</p>
                    <p>{{ $invoice->pointOfSale->name }}</p>
                    <p>{{ $invoice->pointOfSale->location }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">تفاصيل الفاتورة</h2>
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="font-medium">الوصف:</p>
                <p>{{ $invoice->description }}</p>
            </div>
            <div class="flex justify-end">
                <div class="w-64">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">المبلغ:</span>
                        <span>{{ number_format($invoice->amount) }} ر.ي</span>
                    </div>
                    @if($invoice->status === 'paid')
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">تاريخ الدفع:</span>
                        {{-- Corrected line: Use optional() to safely access format() --}}
                        <span>{{ optional($invoice->paid_at)->format('d/m/Y') ?? 'غير متاح' }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">طريقة الدفع:</span>
                        <span>{{ $invoice->payment_method }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-200 mt-4 pt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>الإجمالي:</span>
                            <span>{{ number_format($invoice->amount) }} ر.ي</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4">
        <button class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-print mr-2"></i> طباعة الفاتورة
        </button>
        @if($invoice->status !== 'paid')
        <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> تسديد الفاتورة
        </button>
        @endif
    </div>
</div>
@endsection
