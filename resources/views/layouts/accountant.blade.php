<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة تحكم المحاسب')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        body { font-family: 'Tajawal', sans-serif; background-color: #f5f7fb; }
        /* ... بقية الأنماط ... */
    </style>
</head>
<body class="bg-gray-50">
    <!-- شريط التنقل العلوي -->
    <nav class="bg-white p-4 shadow fixed top-0 left-0 right-0 z-20 flex justify-between items-center">
        <div class="flex items-center">
            <button id="sidebarToggle" class="md:hidden mr-4 text-blue-600">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <a href="{{ route('accountant.dashboard') }}" class="font-bold text-xl text-blue-600 flex items-center">
                <i class="fas fa-calculator mr-2"></i>
                <span>@yield('title', 'لوحة تحكم المحاسب')</span>
            </a>
        </div>
        <div class="flex items-center">
            <div class="relative mr-4">
                <button class="text-gray-600 hover:text-blue-600">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            </div>
            <div class="flex items-center">
                @auth
                <img src="{{ Auth::user()->profile_photo_url }}" alt="صورة المستخدم" class="w-10 h-10 rounded-full border-2 border-blue-200">
                <div class="mr-3 hidden md:block">
                    <p class="font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->getRoleNames()->first() }}</p>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- الهيكل الرئيسي -->
    <div class="flex mt-16">
        <!-- القائمة الجانبية -->
        <aside class="sidebar bg-white w-64 min-h-screen shadow-md fixed md:relative z-30 md:block overflow-y-auto" style="height: calc(100vh - 4rem);">
            <div class="p-4 border-b">
                <h2 class="font-bold text-lg text-blue-600">القائمة الرئيسية</h2>
            </div>
            <ul class="py-4">
                <li>
                    <a href="{{ route('accountant.dashboard') }}" class="nav-item {{ request()->routeIs('accountant.dashboard') ? 'active' : '' }} flex items-center p-4 text-gray-700">
                        <i class="fas fa-home ml-3"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('accountant.pos.index') }}" class="nav-item {{ request()->routeIs('accountant.pos.*') ? 'active' : '' }} flex items-center p-4 text-gray-700">
                        <i class="fas fa-store ml-3"></i>
                        <span>نقاط البيع</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('accountant.invoices.index') }}" class="nav-item {{ request()->routeIs('accountant.invoices.*') ? 'active' : '' }} flex items-center p-4 text-gray-700">
                        <i class="fas fa-file-invoice-dollar ml-3"></i>
                        <span>الفواتير</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('accountant.recharges.index') }}" class="nav-item {{ request()->routeIs('accountant.recharges.*') ? 'active' : '' }} flex items-center p-4 text-gray-700">
                        <i class="fas fa-money-bill-wave ml-3"></i>
                        <span>عمليات الشحن</span>
                    </a>
                </li>
                <!-- بقية الروابط -->
                <li class="mt-8 border-t pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item flex items-center p-4 text-gray-700 w-full text-right">
                            <i class="fas fa-sign-out-alt ml-3"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- طبقة التعتيم للجوال -->
    <div class="overlay" id="overlay"></div>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        });
        document.getElementById('overlay').addEventListener('click', function() {
            this.classList.remove('active');
            document.querySelector('.sidebar').classList.remove('active');
        });
    </script>
</body>
</html>
