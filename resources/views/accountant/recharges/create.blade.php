<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شحن رصيد جديد - نظام كرت مفتاح</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3490dc',
                        secondary: '#6574cd',
                        success: '#38a169',
                        danger: '#e3342f',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-coins mr-2"></i> شحن رصيد جديد
            </h1>
            <a href="{{ route('accountant.dashboard') }}" class="text-primary hover:text-primary-dark">
                <i class="fas fa-arrow-left mr-1"></i> العودة للرئيسية
            </a>
        </div>

        {{-- رسالة النجاح --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle text-xl mr-3 text-green-500"></i>
            <div>
                <p class="font-semibold">{{ session('success') }}</p>
                @if(session('new_balance'))
                <p class="mt-1">الرصيد الجديد: <span class="font-bold">{{ number_format(session('new_balance')) }} د.ل</span></p>
                @endif
            </div>
        </div>
        @endif

        {{-- عرض أخطاء Validation --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-xl mr-2 text-red-500"></i>
                <h3 class="font-bold">حدثت الأخطاء التالية:</h3>
            </div>
            <ul class="list-disc list-inside mt-2 pl-4">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-secondary p-6">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-info-circle mr-2"></i> معلومات الشحن
                </h2>
            </div>
            
            <form action="{{ route('accountant.recharges.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- نقطة البيع --}}
                    <div>
                        <label for="pos_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-store mr-2"></i> نقطة البيع
                        </label>
                        <div class="relative">
                            <select name="pos_id" id="pos_id" required
                                class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-primary focus:border-transparent appearance-none pr-10">
                                <option value="">اختر نقطة البيع</option>
                                @foreach($points as $point)
                                <option value="{{ $point->id }}"
                                    {{ old('pos_id') == $point->id ? 'selected' : '' }}>
                                    {{ $point->name }} ({{ $point->location }})
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        @error('pos_id')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- معلومات الرصيد الحالي --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-wallet mr-2"></i> الرصيد الحالي
                        </label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p id="current-balance" class="text-xl font-bold text-gray-800">--</p>
                            <p class="text-sm text-gray-600 mt-1">سيتم تحديث الرصيد بعد اختيار نقطة البيع</p>
                        </div>
                    </div>

                    {{-- المبلغ --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-2"></i> المبلغ
                        </label>
                        <div class="relative">
                            <input type="number" name="amount" id="amount" min="1" required
                                value="{{ old('amount') }}"
                                class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="أدخل المبلغ">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">د.ل</span>
                            </div>
                        </div>
                        @error('amount')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- طريقة الدفع --}}
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2"></i> طريقة الدفع
                        </label>
                        <div class="relative">
                            <select name="payment_method" id="payment_method" required
                                class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-primary focus:border-transparent appearance-none pr-10">
                                <option value="cash"          {{ old('payment_method')=='cash' ? 'selected' : '' }}>نقداً</option>
                                <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                <option value="card"          {{ old('payment_method')=='card' ? 'selected' : '' }}>بطاقة</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        @error('payment_method')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- ملاحظات --}}
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i> ملاحظات (اختياري)
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- ملخص الشحن --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-receipt mr-2"></i> ملخص الشحن
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                            <div class="mr-3">
                                <p class="text-sm text-gray-600">نقطة البيع</p>
                                <p id="summary-pos" class="font-medium">--</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                            <div class="mr-3">
                                <p class="text-sm text-gray-600">الرصيد الحالي</p>
                                <p id="summary-current-balance" class="font-medium">--</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                            <div class="mr-3">
                                <p class="text-sm text-gray-600">الرصيد الجديد</p>
                                <p id="summary-new-balance" class="font-medium">--</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-100 pt-6">
                    <button type="reset"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition w-full sm:w-auto">
                        <i class="fas fa-undo mr-2"></i> إعادة تعيين
                    </button>
                    <button type="submit"
                        class="bg-gradient-to-r from-success to-green-600 hover:from-success hover:to-green-700 text-white px-6 py-3 rounded-lg flex items-center justify-center w-full sm:w-auto">
                        <i class="fas fa-check-circle mr-2"></i> تأكيد عملية الشحن
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const posSelect = document.getElementById('pos_id');
            const currentBalance = document.getElementById('current-balance');
            const amountInput = document.getElementById('amount');
            const summaryPos = document.getElementById('summary-pos');
            const summaryCurrentBalance = document.getElementById('summary-current-balance');
            const summaryNewBalance = document.getElementById('summary-new-balance');
            
            // بيانات نقاط البيع من السيرفر
            const points = @json($points->keyBy('id')->toArray());
            
            // تحديث معلومات الرصيد عند اختيار نقطة بيع
            posSelect.addEventListener('change', function() {
                const selectedPosId = this.value;
                if (selectedPosId && points[selectedPosId]) {
                    const point = points[selectedPosId];
                    currentBalance.textContent = `${parseFloat(point.balance).toLocaleString('ar')} د.ل`;
                    
                    // تحديث ملخص الشحن
                    summaryPos.textContent = `${point.name} (${point.location})`;
                    summaryCurrentBalance.textContent = `${parseFloat(point.balance).toLocaleString('ar')} د.ل`;
                    
                    // إذا كان هناك مبلغ محدد، حساب الرصيد الجديد
                    if (amountInput.value) {
                        const newBalance = parseFloat(point.balance) + parseFloat(amountInput.value);
                        summaryNewBalance.textContent = `${newBalance.toLocaleString('ar')} د.ل`;
                    } else {
                        summaryNewBalance.textContent = '--';
                    }
                } else {
                    currentBalance.textContent = '--';
                    summaryPos.textContent = '--';
                    summaryCurrentBalance.textContent = '--';
                    summaryNewBalance.textContent = '--';
                }
            });
            
            // تحديث الرصيد الجديد عند تغيير المبلغ
            amountInput.addEventListener('input', function() {
                const selectedPosId = posSelect.value;
                if (selectedPosId && points[selectedPosId]) {
                    const point = points[selectedPosId];
                    if (this.value) {
                        const newBalance = parseFloat(point.balance) + parseFloat(this.value);
                        summaryNewBalance.textContent = `${newBalance.toLocaleString('ar')} د.ل`;
                    } else {
                        summaryNewBalance.textContent = '--';
                    }
                }
            });
            
            // إذا كان هناك رسالة نجاح، تحديث الرصيد تلقائياً
            @if(session('success') && session('pos_id') && session('new_balance'))
                // تحديد نقطة البيع التي تم شحنها
                const rechargedPosId = "{{ session('pos_id') }}";
                if (points[rechargedPosId]) {
                    // تحديث الرصيد في بيانات نقاط البيع
                    points[rechargedPosId].balance = parseFloat("{{ session('new_balance') }}");
                    
                    // تحديث الخيار المحدد
                    posSelect.value = rechargedPosId;
                    
                    // إعادة حساب الرصيد
                    const event = new Event('change');
                    posSelect.dispatchEvent(event);
                }
            @endif
            
            // إذا كانت هناك أخطاء، تحديث القيم تلقائياً
            @if(old('pos_id'))
                posSelect.value = "{{ old('pos_id') }}";
                const event = new Event('change');
                posSelect.dispatchEvent(event);
            @endif
        });
    </script>
</body>
</html>