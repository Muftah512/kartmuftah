    @extends('layouts.pos')

    @section('title', 'تفاصيل الكرت')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-white">تفاصيل الكرت</h1>
                        <div class="flex items-center space-x-2">
                            @if($card->customer_phone)
                            <form action="{{ route('pos.cards.send-whatsapp', $card) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                                    <i class="fab fa-whatsapp mr-2"></i> إرسال عبر واتساب
                                </button>
                            </form>
                            @endif
                            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-print mr-2"></i> طباعة
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6 print-content">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">معلومات الكرت</h2>
                            <p class="text-gray-600">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-3">
                            <div class="text-center">
                                <div class="text-xs text-gray-500">نقطة البيع</div>
                                <div class="font-bold">{{ auth()->user()->pointOfSale->first()->name ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                        <div class="text-center mb-4">
                            <div class="text-sm text-gray-500">اسم المستخدم</div>
                            <div class="text-3xl font-bold text-gray-800 tracking-wider">{{ $card->username }}</div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-500">الباقة</div>
                                <div class="font-bold">{{ $card->package->name ?? 'غير محدد' }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-500">السعر</div>
                                <div class="font-bold">{{ number_format($card->package->price ?? 0) }} ريال</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-500">تاريخ الانتهاء</div>
                                <div class="font-bold">{{ $card->expiration_date ? $card->expiration_date->format('d/m/Y H:i') : 'غير محدد' }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-500">الحالة</div>
                                <div class="font-bold text-green-600">{{ $card->status === 'active' ? 'نشط' : 'معاد شحنه' }}</div>
                            </div>
                        </div>
                        
                        @if($card->customer_phone)
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-blue-500 mr-3"></i>
                                <div>
                                    <div class="text-sm text-gray-500">رقم العميل</div>
                                    <div class="font-bold">+967{{ $card->customer_phone }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="text-center text-sm text-gray-500 mt-6">
                            شكرا لاستخدامكم نظام كرت المفتاح
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('pos.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300 flex items-center">
                            العودة للرئيسية
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        
                        @if(!$card->customer_phone)
                        <button id="whatsappBtn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 flex items-center">
                            <i class="fab fa-whatsapp mr-2"></i> إرسال عبر واتساب
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- نموذج إرسال الواتساب -->
        @if(!$card->customer_phone)
        <div id="whatsappModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
                <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4 rounded-t-xl">
                    <h2 class="text-xl font-bold text-white">إرسال عبر واتساب</h2>
                </div>
                
                
                        
                        <div class="mb-6">
                            <label for="whatsapp_phone" class="block text-gray-700 font-medium mb-2">رقم هاتف العميل</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    +967
                                </span>
                                <input type="text" id="whatsapp_phone" name="whatsapp_phone"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="7xxxxxxxx" pattern="[0-9]{9}" required>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">سيتم إرسال تفاصيل الكرت إلى هذا الرقم</p>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelWhatsapp" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg">
                                إلغاء
                            </button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i> إرسال
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-content, .print-content * {
                visibility: visible;
            }
            .print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
    
    <script>
        // إظهار/إخفاء نموذج الواتساب
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappBtn = document.getElementById('whatsappBtn');
            const whatsappModal = document.getElementById('whatsappModal');
            const cancelWhatsapp = document.getElementById('cancelWhatsapp');
            
            if (whatsappBtn) {
                whatsappBtn.addEventListener('click', function() {
                    whatsappModal.classList.remove('hidden');
                    whatsappModal.classList.add('flex');
                });
            }
            
            if (cancelWhatsapp) {
                cancelWhatsapp.addEventListener('click', function() {
                    whatsappModal.classList.add('hidden');
                    whatsappModal.classList.remove('flex');
                });
            }
            
            // إغلاق النموذج عند النقر خارج المحتوى
            window.addEventListener('click', function(event) {
                if (event.target === whatsappModal) {
                    whatsappModal.classList.add('hidden');
                    whatsappModal.classList.remove('flex');
                }
            });
        });
    </script>
    @endsection
    