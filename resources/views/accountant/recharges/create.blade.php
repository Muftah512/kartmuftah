@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">شحن رصيد جديد</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('accountant.recharges.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="point_of_sale_id" class="block text-sm font-medium text-gray-700 mb-1">نقطة البيع</label>
                    <select name="point_of_sale_id" id="point_of_sale_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">اختر نقطة البيع</option>
                        @foreach($points as $point)
                        <option value="{{ $point->id }}" {{ request('point_id') == $point->id ? 'selected' : '' }}>
                            {{ $point->name }} ({{ $point->location }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">المبلغ (ريال يمني)</label>
                    <input type="number" name="amount" id="amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" min="1" required>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">طريقة الدفع</label>
                    <select name="payment_method" id="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="cash">نقدي</option>
                        <option value="bank_transfer">تحويل بنكي</option>
                        <option value="card">بطاقة</option>
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات (اختياري)</label>
                    <textarea name="notes" id="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> تأكيد الشحن
                </button>
            </div>
        </form>
    </div>
</div>
@endsection