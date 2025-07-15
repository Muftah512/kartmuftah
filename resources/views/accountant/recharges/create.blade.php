@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">شحن رصيد جديد</h1>

    {{-- رسالة النجاح --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- عرض أخطاء Validation --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('accountant.recharges.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- نقطة البيع --}}
                <div>
                    <label for="pos_id" class="block text-sm font-medium mb-1">نقطة البيع</label>
                    <select name="pos_id" id="pos_id" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر نقطة البيع</option>
                        @foreach($points as $point)
                            <option value="{{ $point->id }}"
                                {{ old('pos_id') == $point->id ? 'selected' : '' }}>
                                {{ $point->name }} ({{ $point->location }})
                            </option>
                        @endforeach
                    </select>
                    @error('pos_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- المبلغ --}}
                <div>
                    <label for="amount" class="block text-sm font-medium mb-1">المبلغ</label>
                    <input type="number" name="amount" id="amount" min="1" required
                        value="{{ old('amount') }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('amount')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- طريقة الدفع --}}
                <div>
                    <label for="payment_method" class="block text-sm font-medium mb-1">طريقة الدفع</label>
                    <select name="payment_method" id="payment_method" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="cash"          {{ old('payment_method')=='cash' ? 'selected' : '' }}>نقداً</option>
                        <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                        <option value="card"          {{ old('payment_method')=='card' ? 'selected' : '' }}>بطاقة</option>
                    </select>
                    @error('payment_method')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ملاحظات --}}
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium mb-1">ملاحظات (اختياري)</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> تأكيد الشحن
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
