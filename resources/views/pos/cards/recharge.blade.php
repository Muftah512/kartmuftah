@extends('layouts.pos')

@section('title', 'إعادة شحن كرت')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <span class="text-gray-600">/</span>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('pos.cards.recharge') }}" class="text-gray-700 hover:text-blue-600">إعادة شحن كرت</a>
    </li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">إعادة شحن كرت إنترنت</h1>
        </div>

        <div class="p-6">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('pos.cards.recharge.submit') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="username" class="block text-gray-700 font-medium mb-2">اسم المستخدم القديم</label>
                    <input type="text" id="username" name="username"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="أدخل اسم المستخدم" required>
                </div>

                <div class="mb-6">
                    <label for="package_id" class="block text-gray-700 font-medium mb-2">اختر الباقة الجديدة</label>
                    <select name="package_id" id="package_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">
                                {{ $package->name }} - {{ number_format($package->price) }} ريال
                                ({{ $package->validity_days }} يوم)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label for="customer_phone" class="block text-gray-700 font-medium mb-2">رقم هاتف العميل (اختياري)</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            +967
                        </span>
                        <input type="text" id="customer_phone" name="customer_phone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="7xxxxxxxx" pattern="[0-9]{9}">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">سيتم إرسال الكرت الجديد عبر واتساب لهذا الرقم</p>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                الرصيد الحالي: <span class="font-bold">{{ number_format(auth()->user()->pointOfSale->first()->balance) }} ريال يمني</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 flex items-center">
                        إعادة شحن الكرت
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
