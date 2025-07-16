@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">إنشاء فاتورة جديدة</h1>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-file-invoice mr-2"></i> بيانات الفاتورة
            </h2>
        </div>
        
        <form action="{{ route('accountant.invoices.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- نقطة البيع --}}
                <div>
                    <label for="pos_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-store mr-2"></i> نقطة البيع
                    </label>
                    <select name="pos_id" id="pos_id" required
                        class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر نقطة البيع</option>
                        @foreach($points as $point)
                            <option value="{{ $point->id }}">
                                {{ $point->name }} ({{ $point->location }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- المبلغ --}}
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2"></i> المبلغ
                    </label>
                    <input type="number" name="amount" id="amount" min="1" required
                        class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="أدخل المبلغ">
                </div>

                {{-- الوصف --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2"></i> الوصف
                    </label>
                    <textarea name="description" id="description" rows="3" required
                        class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="أدخل وصف الفاتورة"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i> حفظ الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
