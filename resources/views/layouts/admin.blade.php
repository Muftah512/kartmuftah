<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','لوحة التحكم - نظام المفتاح')</title>

  <!-- Fonts + Tailwind v2 CDN -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>

  <style>
    :root{
      --bg:#f1f5f9; --text:#1e293b; --panel:#ffffff; --muted:#64748b;
      --grad1:#0f172a; --grad2:#1e293b; --sidebar:#1e293b;
      --primary:#4361ee; --secondary:#3f37c9;
      --badge:#dc2626; --hover:#f1f5f9;
      --shadow:rgba(0,0,0,.1);
    }
    .dark{
      --bg:#0b1220; --text:#e5e7eb; --panel:#0f172a; --muted:#94a3b8;
      --grad1:#0b1220; --grad2:#111827; --sidebar:#0f172a;
      --hover:#111827; --shadow:rgba(0,0,0,.35);
    }
    *{font-family:'Tajawal',sans-serif}
    html,body{height:100%;overscroll-behavior:none}
    body{background:var(--bg);color:var(--text);overflow-x:hidden}

    /* Sidebar */
    .sidebar{
      background:linear-gradient(180deg,var(--sidebar),var(--grad1));
      width:260px; transition:transform .3s,width .3s; box-shadow:0 0 20px rgba(0,0,0,.2); z-index:1300;
    }
    .sidebar .nav-link{position:relative;overflow:hidden;border-radius:.75rem;margin-bottom:.5rem;transition:all .3s}
    .sidebar .nav-link::before{content:'';position:absolute;top:0;inset-inline-start:0;width:0;height:100%;background:rgba(255,255,255,.08);transition:width .3s;z-index:-1}
    .sidebar .nav-link:hover::before{width:100%}
    .sidebar .nav-link.active{background:linear-gradient(90deg,var(--primary),var(--secondary));box-shadow:0 4px 15px rgba(67,97,238,.3)}
    .user-avatar{width:40px;height:40px;border:2px solid rgba(255,255,255,.3);box-shadow:0 0 10px rgba(0,0,0,.2)}

    /* Mini (سطح المكتب) */
    @media (min-width:1024px){
      .mini .sidebar{width:80px}
      .mini .logo-text,.mini .nav-text{display:none}
      .mini .nav-icon{margin-right:0;font-size:1.45rem}
      .mini .nav-link{justify-content:center;padding:1.1rem 0}
    }

    /* جوال: عرض 85% أو 320px */
    @media (max-width:1024px){
      .sidebar{width:min(85vw,20rem);position:fixed;top:0;bottom:0;right:0;transform:translateX(100%)}
    }

    /* Topbar */
    .topbar{background:linear-gradient(90deg,var(--grad1),var(--grad2));height:70px;box-shadow:0 2px 10px var(--shadow);z-index:1100;position:sticky;top:0}

    .main-content{background:linear-gradient(135deg,var(--bg),#e2e8f0);min-height:calc(100vh - 70px)}
    .dark .main-content{background:linear-gradient(135deg,var(--bg),#0e1629)}
    .page-header{background:var(--panel);border-radius:1rem;box-shadow:0 4px 20px var(--shadow);padding:1.5rem;margin-bottom:1.5rem}
    .breadcrumb-item{position:relative;padding-inline-start:1.25rem}
    .breadcrumb-item:after{content:"";position:absolute;top:50%;inset-inline-end:-10px;transform:translateY(-50%);width:5px;height:5px;background-color:var(--muted);border-radius:50%}
    .breadcrumb-item:last-child:after{display:none}

    .notification-badge{position:absolute;top:-.35rem;inset-inline-end:-.35rem;font-size:10px;background:var(--badge);color:#fff;border-radius:9999px;padding:.15rem .35rem}

    .sidebar-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1200;opacity:0;pointer-events:none;transition:opacity .2s}
    .sidebar-backdrop.show{opacity:1;pointer-events:auto}

    .icon-btn{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:9999px;border:1px solid rgba(255,255,255,.15);color:#fff;transition:.2s}
    .icon-btn:hover{background:rgba(255,255,255,.12)}
  </style>
</head>

<body
  x-data="layoutState()"
  x-init="init()"
  :class="(sidebarOpen ? 'overflow-hidden touch-none ' : '') + (dark ? 'dark ' : '') + (mini ? 'mini ' : '')"
  class="relative min-h-screen">

  <!-- زر القائمة (يختفي عند الفتح) -->
  <button @click="sidebarOpen = true" class="icon-btn lg:hidden" style="position:fixed;top:20px;right:20px;z-index:1400"
          x-show="!sidebarOpen" x-transition.opacity aria-label="فتح القائمة" :aria-expanded="sidebarOpen.toString()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Backdrop للجوال -->
  <div x-show="sidebarOpen" class="sidebar-backdrop lg:hidden" @click="sidebarOpen=false" x-transition.opacity aria-hidden="true"></div>

  <div class="flex">
    <!-- Sidebar -->
    <aside class="sidebar fixed inset-y-0 right-0 transform lg:relative lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
           @keydown.escape.window="sidebarOpen=false"
           role="dialog" aria-label="القائمة الجانبية">
      <!-- مهم: flex عمودي بدون absolute -->
      <div class="h-full flex flex-col">
        <!-- Header داخل الشريط -->
        <div class="p-5 pt-8">
          <div class="flex items-center">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
              <i class="fas fa-key text-white text-xl"></i>
            </div>
            <h1 class="text-xl font-bold text-white mr-3 logo-text">بطاقة المفتاح</h1>
          </div>
        </div>

        <!-- NAV -->
        <nav class="flex-1 overflow-y-auto px-5">
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

            <!-- تقارير: قائمة فرعية مستقرة -->
            <li class="mb-2" x-data="{open: {{ Route::is('admin.reports.*') ? 'true' : 'false' }} }">
              <button type="button" class="w-full flex items-center justify-between py-3 px-4 nav-link"
                      @click="open=!open" :aria-expanded="open.toString()" aria-controls="submenu-reports">
                <span class="flex items-center">
                  <i class="fas fa-chart-bar nav-icon text-gray-300 mr-3"></i>
                  <span class="text-gray-200 nav-text">التقارير والإحصائيات</span>
                </span>
                <i class="fas fa-chevron-down transform transition" :class="open ? 'rotate-180' : ''"></i>
              </button>
              <ul id="submenu-reports" x-show="open" x-transition class="mt-1 mr-10 mb-3 space-y-1">
                <li>
                  <a href="{{ route('admin.reports.sales') }}" class="block text-gray-200 hover:text-white text-sm py-1.5">
                    تقارير المبيعات
                  </a>
                </li>
                <!-- أضف روابط فرعية أخرى إن وجدت -->
              </ul>
            </li>

            <!-- تقارير المحاسبين -->
            <li class="mb-2">
              <a href="{{ route('admin.accountants.topups.index') }}" class="flex items-center py-3 px-4 nav-link @if(Route::is('admin.accountants.topups.*')) active @endif">
                <i class="fas fa-file-invoice-dollar nav-icon text-gray-300 mr-3"></i>
                <span class="text-gray-200 nav-text">تقارير المحاسبين</span>
              </a>
            </li>

            <!-- إعدادات: قائمة فرعية (تُفتح تلقائياً عند التواجد على admin.profile.*) -->
            <li class="mb-2" x-data="{open: {{ Route::is('admin.profile.*') ? 'true' : 'false' }} }">
              <button type="button" class="w-full flex items-center justify-between py-3 px-4 nav-link"
                      @click="open=!open" :aria-expanded="open.toString()" aria-controls="submenu-settings">
                <span class="flex items-center">
                  <i class="fas fa-cog nav-icon text-gray-300 mr-3"></i>
                  <span class="text-gray-200 nav-text">الإعدادات</span>
                </span>
                <i class="fas fa-chevron-down transform transition" :class="open ? 'rotate-180' : ''"></i>
              </button>
              <ul id="submenu-settings" x-show="open" x-transition class="mt-1 mr-10 mb-3 space-y-1">
                <li>
                  <a href="{{ route('admin.profile.edit') }}"
                     class="block text-gray-200 hover:text-white text-sm py-1.5 @if(Route::is('admin.profile.edit')) font-bold text-white @endif">
                    حسابي
                  </a>
                </li>
                <li><a href="#" class="block text-gray-200 hover:text-white text-sm py-1.5">عام</a></li>
                <li><a href="#" class="block text-gray-200 hover:text-white text-sm py-1.5">الصلاحيات</a></li>
              </ul>
            </li>
          </ul>
        </nav>

        <!-- Footer ثابت أسفل الشريط (بدون absolute) -->
        @auth
        <div class="p-5 border-t border-white border-opacity-10">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <a href="{{ route('admin.profile.edit') }}" class="shrink-0" title="إعدادات الحساب">
                <img class="h-10 w-10 rounded-full user-avatar"
                     src="{{ route('admin.profile.avatar.show') }}?t={{ now()->timestamp }}"
                     alt="صورة {{ auth()->user()->name }}">
              </a>
              <div class="mr-3">
                <p class="text-sm font-medium text-gray-200">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">{{ auth()->user()->getRoleNames()->first() ?? 'مستخدم' }}</p>
              </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="text-gray-300 hover:text-white" title="تسجيل الخروج">
                <i class="fas fa-sign-out-alt"></i>
              </button>
            </form>
          </div>
        </div>
        @endauth
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Topbar -->
      <header class="topbar">
        <div class="flex justify-between items-center px-4 md:px-6 h-full">
          <div class="flex items-center gap-2">
            <!-- زر Mini (سطح المكتب فقط) -->
            <button class="icon-btn hidden lg:inline-flex" @click="mini = !mini" :title="mini ? 'إلغاء التصغير' : 'تصغير الشريط'">
              <i class="fas" :class="mini ? 'fa-arrow-right' : 'fa-arrow-left'"></i>
            </button>
            <h1 class="text-xl font-bold text-white">@yield('page-title','لوحة التحكم')</h1>
          </div>

          <div class="flex items-center space-x-3 space-x-reverse">
            <!-- Dark Mode -->
            <button class="icon-btn" @click="toggleDark()" :title="dark ? 'وضع فاتح' : 'وضع ليلي'">
              <i class="fas" :class="dark ? 'fa-sun' : 'fa-moon'"></i>
            </button>

            @if(View::exists('layouts.partials.notifications'))
              @include('layouts.partials.notifications')
            @else
              @php
                $unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
                $indexUrl = Illuminate\Support\Facades\Route::has('notifications.index') ? route('notifications.index') : url('/notifications');
              @endphp
              <a href="{{ $indexUrl }}" class="relative inline-flex items-center text-gray-200 hover:text-white" title="الإشعارات">
                <i class="fas fa-bell text-xl"></i>
                @if($unread > 0)
                  <span class="notification-badge">{{ $unread }}</span>
                @endif
              </a>
            @endif

            <a href="{{ $indexUrl ?? url('/notifications') }}" class="text-gray-200 hover:text-white" title="الرسائل">
              <i class="fas fa-comment-alt text-xl"></i>
            </a>

            @auth
            <div class="relative" x-data="{ open:false }">
              <button class="icon-btn" @click.stop="open=!open" :aria-expanded="open.toString()" aria-haspopup="menu" title="حسابي">
                <i class="fas fa-user"></i>
              </button>
              <div class="absolute right-0 mt-2 bg-white dark:bg-[color:var(--panel)] rounded-md shadow-lg py-2 z-50"
                   x-show="open" x-transition @click.outside="open=false" x-cloak>
                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <i class="fas fa-user-circle mr-2 text-blue-500"></i>الملف الشخصي
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <i class="fas fa-cog mr-2 text-gray-500"></i>الإعدادات
                </a>
                <a href="{{ route('admin.profile.edit') }}#security" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                  <i class="fas fa-shield-alt mr-2 text-purple-500"></i>الأمان
                </a>
                <div class="border-t my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                    <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>تسجيل الخروج
                  </button>
                </form>
              </div>
            </div>
            @endauth
          </div>
        </div>
      </header>

      <!-- Page -->
      <main class="flex-1 overflow-y-auto p-6 main-content">
        <div class="page-header">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
              <h1 class="text-2xl font-bold" style="color:var(--text)">@yield('page-title','لوحة التحكم')</h1>
              <p class="mt-2" style="color:var(--muted)">@yield('page-description','مرحبًا بك في لوحة التحكم، هنا يمكنك إدارة النظام')</p>
            </div>
            <div class="mt-4 md:mt-0">
              <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 space-x-reverse">
                  <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-500" style="color:var(--muted)">
                      <i class="fas fa-home mr-1"></i>الرئيسية
                    </a>
                  </li>
                  <li class="breadcrumb-item">
                    <span class="font-medium" style="color:var(--text)">@yield('current-page','لوحة التحكم')</span>
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>

        @yield('content')

        <footer class="mt-10 py-6 text-center text-sm" style="color:var(--muted)">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>برمجة :عبدالرحمن منير &copy; {{ date('Y') }} جميع الحقوق محفوظة</div>
            <div class="mt-2 md:mt-0">
              <span class="mx-2">الإصدار 2.1.0</span>
              <span class="mx-2">|</span>
              <a href="#" class="mx-2" style="color:var(--primary)">الشروط والأحكام</a>
              <span class="mx-2">|</span>
              <a href="#" class="mx-2" style="color:var(--primary)">سياسة الخصوصية</a>
            </div>
          </div>
        </footer>
      </main>
    </div>
  </div>

  <!-- Alpine v3 -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    function layoutState(){
      return {
        sidebarOpen:false,
        mini:false,
        dark:false,
        init(){
          this.dark = localStorage.getItem('km_dark') === '1';
          this.mini = localStorage.getItem('km_mini') === '1';
          this.$watch('dark', v => localStorage.setItem('km_dark', v ? '1' : '0'));
          this.$watch('mini', v => localStorage.setItem('km_mini', v ? '1' : '0'));
        },
        toggleDark(){ this.dark = !this.dark; }
      }
    }
  </script>
</body>
</html>
