<!DOCTYPE html> 
<html lang="ar" dir="rtl"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'لوحة التحكم - بطاقة المفتاح')</title>
    
    <!-- Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --sidebar-color: #1e293b;
            --topbar-color: #0f172a;
        }
        
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f1f5f9;
            color: #1e293b;
            overflow-x: hidden;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-color), #0f172a);
            width: 260px;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            z-index: 100;
        }
        
        .sidebar-mini {
            width: 80px;
        }
        
        .sidebar-mini .logo-text,
        .sidebar-mini .nav-text {
            display: none;
        }
        
        .sidebar-mini .nav-icon {
            margin-right: 0;
            font-size: 1.5rem;
        }
        
        .sidebar-mini .nav-link {
            justify-content: center;
            padding: 1.25rem 0;
        }
        
        .topbar {
            background: linear-gradient(90deg, var(--topbar-color), var(--sidebar-color));
            height: 70px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 90;
        }
        
        .nav-link {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: width 0.3s ease;
            z-index: -1;
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .dropdown-menu {
            min-width: 200px;
            border-radius: 0.75rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: none;
            overflow: hidden;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-item:hover {
            background: #f1f5f9;
            padding-right: 1.75rem;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: white;
        }
        
        .toggle-sidebar {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 110;
        }
        
        .toggle-sidebar:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(180deg);
        }
        
        .main-content {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            min-height: calc(100vh - 70px);
        }
        
        .page-header {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .breadcrumb-item {
            position: relative;
            padding-left: 1.5rem;
        }
        
        .breadcrumb-item:after {
            content: "";
            position: absolute;
            top: 50%;
            right: -10px;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            background-color: #94a3b8;
            border-radius: 50%;
        }
        
        .breadcrumb-item:last-child:after {
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
            }
            
            .sidebar-open {
                transform: translateX(0);
            }
            
            .sidebar-mini {
                width: 260px;
            }
            
            .sidebar-mini .logo-text,
            .sidebar-mini .nav-text {
                display: block;
            }
            
            .sidebar-mini .nav-icon {
                margin-right: 0.75rem;
                font-size: 1.25rem;
            }
            
            .sidebar-mini .nav-link {
                justify-content: flex-start;
                padding: 0.75rem 1.5rem;
            }
            
            .breadcrumb-item {
                padding-left: 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Toggle Sidebar Button -->
        <button class="toggle-sidebar lg:hidden">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="p-5 pt-8">
                <div class="flex items-center justify-center mb-8 relative">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-key text-white text-xl"></i>
                        </div>
                        <h1 class="text-xl font-bold text-white mr-3 logo-text">بطاقة المفتاح</h1>
                    </div>
                </div>
                
                <nav>
                    <ul>
                        <li class="mb-2">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 nav-link @if(Route::is('admin.dashboard')) active @endif">
                                <i class="fas fa-tachometer-alt nav-icon text-gray-300 mr-3"></i>
                                <span class="text-gray-200 nav-text">لوحة التحكم</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center py-3 px-4 nav-link @if(Route::is('admin.users.*')) active @endif">
                                <i class="fas fa-users nav-icon text-gray-300 mr-3"></i>
                                <span class="text-gray-200 nav-text">إدارة المستخدمين</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('admin.pos.index') }}" class="flex items-center py-3 px-4 nav-link @if(Route::is('admin.pos.*')) active @endif">
                                <i class="fas fa-store nav-icon text-gray-300 mr-3"></i>
                                <span class="text-gray-200 nav-text">نقاط البيع</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('admin.packages.index') }}" class="flex items-center py-3 px-4 nav-link @if(Route::is('admin.packages.*')) active @endif">
                                <i class="fas fa-sim-card nav-icon text-gray-300 mr-3"></i>
                                <span class="text-gray-200 nav-text">الباقات</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('admin.reports.sales') }}" class="flex items-center py-3 px-4 nav-link @if(Route::is('admin.reports.*')) active @endif">
                                <i class="fas fa-chart-bar nav-icon text-gray-300 mr-3"></i>
                                <span class="text-gray-200 nav-text">التقارير والإحصائيات</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="flex items-center py-3 px-4 nav-link">
                                <i class="fas fa-cog nav-icon text-gray-300 mr-3"></i>
                                <span class="text-gray-200 nav-text">الإعدادات</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Sidebar Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-5 border-t border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full user-avatar" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4361ee&color=fff" alt="User avatar">
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-200">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400">
                                    @php
                                        $role = auth()->user()->roles->first();
                                        echo $role ? $role->name : 'مستخدم';
                                    @endphp
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('logout') }}" class="text-gray-400 hover:text-gray-200">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="topbar">
                <div class="flex justify-between items-center px-6 h-full">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-white">@yield('page-title', 'لوحة التحكم')</h1>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <button class="text-gray-300 hover:text-white relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="notification-badge">3</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-300 hover:text-white">
                                <i class="fas fa-comment-alt text-xl"></i>
                                <span class="notification-badge">5</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button id="user-menu" class="flex items-center focus:outline-none">
                                <div class="flex items-center">
                                    <div class="mr-3 text-right">
                                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-300">
                                            @php
                                                $role = auth()->user()->roles->first();
                                                echo $role ? $role->name : 'مستخدم';
                                            @endphp
                                        </p>
                                    </div>
                                    <img class="h-10 w-10 rounded-full user-avatar" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4361ee&color=fff" alt="User avatar">
                                </div>
                            </button>
                            <div id="user-menu-dropdown" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg py-2 z-50 dropdown-menu">
                                <a href="#" class="block dropdown-item">
                                    <i class="fas fa-user-circle mr-2 text-blue-500"></i>الملف الشخصي
                                </a>
                                <a href="#" class="block dropdown-item">
                                    <i class="fas fa-cog mr-2 text-gray-500"></i>الإعدادات
                                </a>
                                <a href="#" class="block dropdown-item">
                                    <i class="fas fa-shield-alt mr-2 text-purple-500"></i>الأمان
                                </a>
                                <div class="border-t my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 dropdown-item">
                                        <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 main-content">                             
                <div class="page-header">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'لوحة التحكم')</h1>
                            <p class="text-gray-600 mt-2">@yield('page-description', 'مرحبًا بك في لوحة التحكم، هنا يمكنك إدارة النظام')</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    <li class="breadcrumb-item">
                                        <div>
                                            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-blue-600">
                                                <i class="fas fa-home mr-1"></i>الرئيسية
                                            </a>
                                        </div>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <div class="flex items-center">
                                            <span class="text-gray-700 font-medium">@yield('current-page', 'لوحة التحكم')</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
                @yield('content')
                
                <!-- Footer -->
                <footer class="mt-10 py-6 text-center text-gray-500 text-sm">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            نظام بطاقة المفتاح &copy; {{ date('Y') }} جميع الحقوق محفوظة
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="mx-2">الإصدار 2.1.0</span>
                            <span class="mx-2">|</span>
                            <a href="#" class="text-blue-600 hover:text-blue-800 mx-2">الشروط والأحكام</a>
                            <span class="mx-2">|</span>
                            <a href="#" class="text-blue-600 hover:text-blue-800 mx-2">سياسة الخصوصية</a>
                        </div>
                    </div>
                </footer>                       
            </main>
        </div>
    </div>

    <script>
        document.getElementById('reportsToggle').addEventListener('click', function() {
            document.getElementById('reportsMenu').classList.toggle('hidden');
        });
        document.getElementById('userToggle').addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('hidden');
        });
        // User menu dropdown
        document.getElementById('user-menu').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('user-menu-dropdown');
            dropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const dropdown = document.getElementById('user-menu-dropdown');
            
            if (!userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        // Toggle sidebar on mobile 
        const toggleBtn = document.querySelector('.toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-open');
        });
        
        // Toggle sidebar size on desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('sidebar-open');
            }
        });
        
        // Auto-hide sidebar on mobile when clicking a link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('sidebar-open');
                }
            });
        });
    </script>
</body>
</html>