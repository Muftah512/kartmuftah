<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام نقاط البيع - @yield('title', 'لوحة التحكم')</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- شريط التنقل العلوي -->
    <header class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg no-print">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="md:hidden text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold">نظام إدارة نقاط البيع</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block">
                        <span class="ml-2">{{ auth()->user()->name }}</span>
                        <span class="bg-blue-500 text-xs px-2 py-1 rounded-full">نقطة بيع</span>
                    </div>
                    
                    <div class="relative">
                        <button class="relative z-10 block h-8 w-8 rounded-full overflow-hidden focus:outline-none">
                            <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="صورة المستخدم">
                        </button>
                    </div>
                    
                    <a href="{{ route('logout') }}" class="text-white hover:text-gray-200">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- الهيكل الرئيسي -->
    <div class="flex">
        <!-- القائمة الجانبية -->
        <aside id="sidebar" class="bg-white w-64 min-h-screen shadow-md no-print sidebar transform -translate-x-full md:translate-x-0">
            <div class="p-4 border-b">
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                    <div>
                        <p class="font-semibold">{{ auth()->user()->pointOfSale->name }}</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                
                <div class="mt-4 bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">الرصيد الحالي</p>
                    <p class="text-xl font-bold text-green-600">{{ number_format(auth()->user()->pointOfSale->balance) }} ريال</p>
                </div>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('pos.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-tachometer-alt ml-2"></i>
                            <span>لوحة التحكم</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.cards.generate') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-sim-card ml-2"></i>
                            <span>إنشاء كرت جديد</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.cards.recharge') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-redo ml-2"></i>
                            <span>إعادة شحن كرت</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.sales') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-chart-line ml-2"></i>
                            <span>تقارير المبيعات</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-history ml-2"></i>
                            <span>سجل المعاملات</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-cog ml-2"></i>
                            <span>الإعدادات</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="p-4 border-t mt-auto">
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <p class="text-sm text-gray-600">رقم الدعم الفني</p>
                    <p class="font-bold">773377968</p>
                </div>
            </div>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="flex-1">
            <div class="container mx-auto px-4 py-6">
                <!-- مسار التنقل -->
                <div class="mb-6 no-print">
                    <nav class="flex" aria-label="مسار التنقل">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('pos.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-home mr-2"></i>
                                    الرئيسية
                                </a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                
                <!-- رسائل التنبيه -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <strong class="font-bold">نجاح!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <strong class="font-bold">خطأ!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <!-- محتوى الصفحة -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 text-center no-print">
        <div class="container mx-auto px-4">
            <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام إدارة نقاط البيع</p>
            <p class="text-sm text-gray-400 mt-1">الإصدار 2.1.0</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // تبديل القائمة الجانبية على الهواتف
        $(document).ready(function() {
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('-translate-x-full');
            });
            
            // إغلاق القائمة عند النقر خارجها
            $(document).click(function(event) {
                if (!$(event.target).closest('#sidebar, #sidebarToggle').length) {
                    if ($(window).width() < 768) {
                        $('#sidebar').addClass('-translate-x-full');
                    }
                }
            });
            
            // منع إغلاق القائمة عند النقر داخلها
            $('#sidebar').click(function(event) {
                event.stopPropagation();
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>