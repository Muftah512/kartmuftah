<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>@yield('title','لوحة تحكم المحاسب')</title>

  <!-- Tailwind CDN (يدعم القيم المخصّصة مثل [100svh]) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- أيقونات + خط -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root { --nav-h: 64px; }
    html, body { height: 100%; }
    body {
      font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      background: #f5f7fb;
      overflow-x: hidden; /* يمنع قطع العرض أفقيًا */
    }
    /* تمنع تكبير الحقول في iOS */
    input, select, textarea { font-size: 16px !important; }

    /* الدرج الجانبي (الجوال) + ثابت على الشاشات الكبيرة */
    #sidebar {
      transform: translateX(100%);             /* خارج الشاشة من جهة اليمين (RTL) */
      will-change: transform;
    }
    #sidebar.open { transform: translateX(0) !important; }
    @media (min-width: 1024px) {
      #sidebar { transform: translateX(0) !important; }
    }
  </style>
</head>

<body class="min-h-[100svh] bg-gray-50">

  <!-- شريط علوي ثابت -->
  <nav class="fixed top-0 right-0 left-0 z-40 bg-white/95 backdrop-blur shadow"
       style="height: var(--nav-h);">
    <div class="h-full max-w-7xl mx-auto flex items-center justify-between px-3 sm:px-4">
      <div class="flex items-center gap-3">
        <button id="openSidebar" class="lg:hidden text-blue-600 p-2 rounded hover:bg-blue-50" aria-label="فتح القائمة">
          <i class="fas fa-bars text-xl"></i>
        </button>
        <a href="{{ route('accountant.dashboard') }}" class="flex items-center gap-2 text-blue-600 font-bold text-base sm:text-lg">
          <i class="fas fa-calculator"></i>
          <span class="truncate">@yield('title','لوحة تحكم المحاسب')</span>
        </a>
      </div>

      <div class="flex items-center gap-4">
        <button class="relative text-gray-600 hover:text-blue-600 p-2 rounded hover:bg-gray-50" aria-label="الإشعارات">
          <i class="fas fa-bell text-xl"></i>
          <span class="absolute -top-0.5 -left-0.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        @auth
        <div class="flex items-center gap-3">
          <img src="{{ Auth::user()->profile_photo_url }}" alt="صورة المستخدم" class="w-10 h-10 rounded-full border-2 border-blue-200 object-cover">
          <div class="hidden sm:block text-right leading-tight">
            <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-600">{{ Auth::user()->getRoleNames()->first() }}</p>
          </div>
        </div>
        @endauth
      </div>
    </div>
  </nav>

  <!-- طبقة التعتيم للجوال -->
  <div id="backdrop" class="fixed inset-0 z-30 bg-black/40 opacity-0 pointer-events-none transition-opacity lg:hidden"></div>

  <!-- الهيكل العام -->
  <div class="pt-[var(--nav-h)]">
    <!-- Sidebar ثابت يمين الشاشة على الشاشات الكبيرة،
         Drawer على الجوال. ارتفاعه = الشاشة - الشريط -->
    <aside id="sidebar"
           class="fixed right-0 z-40 w-72 bg-white border-s shadow-lg overflow-y-auto
                  transition-transform duration-200 ease-in-out
                  lg:top-[var(--nav-h)] lg:bottom-0 lg:h-[calc(100svh-var(--nav-h))]
                  top-[var(--nav-h)] bottom-0 h-[calc(100svh-var(--nav-h))]">
      <div class="p-4 border-b">
        <h2 class="font-bold text-lg text-blue-600">القائمة الرئيسية</h2>
      </div>
      <nav class="py-2">
        <a href="{{ route('accountant.dashboard') }}"
           class="flex items-center gap-3 p-3 hover:bg-gray-50
           {{ request()->routeIs('accountant.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
          <i class="fas fa-home"></i><span>الرئيسية</span>
        </a>
        <a href="{{ route('accountant.pos.index') }}"
           class="flex items-center gap-3 p-3 hover:bg-gray-50
           {{ request()->routeIs('accountant.pos.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
          <i class="fas fa-store"></i><span>نقاط البيع</span>
        </a>
        <a href="{{ route('accountant.invoices.index') }}"
           class="flex items-center gap-3 p-3 hover:bg-gray-50
           {{ request()->routeIs('accountant.invoices.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
          <i class="fas fa-file-invoice-dollar"></i><span>الفواتير</span>
        </a>
        <a href="{{ route('accountant.recharges.index') }}"
           class="flex items-center gap-3 p-3 hover:bg-gray-50
           {{ request()->routeIs('accountant.recharges.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
          <i class="fas fa-money-bill-wave"></i><span>عمليات الشحن</span>
        </a>

        <div class="my-4 border-t"></div>

        <form method="POST" action="{{ route('logout') }}" class="p-3">
          @csrf
          <button type="submit" class="w-full flex items-center gap-3 p-3 rounded hover:bg-red-50 text-gray-700">
            <i class="fas fa-sign-out-alt"></i><span>تسجيل الخروج</span>
          </button>
        </form>
      </nav>
    </aside>

    <!-- المحتوى الرئيسي
         ملاحظة: margin-right يفسح مكانًا للـ Sidebar الثابت على الشاشات الكبيرة -->
    <main class="min-h-[calc(100svh-var(--nav-h))]
                 w-full px-3 sm:px-4 lg:px-6 pb-6
                 lg:mr-72"> 
      <!-- تغليف تلقائي لأي جدول لمنع القطع على الجوال -->
      <div id="content-wrapper">
        @yield('content')
      </div>
    </main>
  </div>

  <script>
    const sidebar  = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    const openBtn  = document.getElementById('openSidebar');

    function open() {
      sidebar.classList.add('open');
      backdrop.classList.remove('pointer-events-none');
      requestAnimationFrame(()=> backdrop.classList.add('opacity-100'));
    }
    function close() {
      sidebar.classList.remove('open');
      backdrop.classList.add('pointer-events-none');
      backdrop.classList.remove('opacity-100');
    }

    openBtn?.addEventListener('click', open);
    backdrop.addEventListener('click', close);
    window.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });

    // عند اتساع الشاشة، أغلق الدرج (ونبقي الهامش للمحتوى)
    matchMedia('(min-width:1024px)').addEventListener('change', e => { if (e.matches) close(); });

    // لفّ كل الجداول تلقائيًا داخل عنصر تمرير أفقي لمنع ظهورها «نصفها»
    document.querySelectorAll('main table').forEach(tbl => {
      const wrap = document.createElement('div');
      wrap.className = 'overflow-x-auto -mx-1 sm:mx-0';
      tbl.parentNode.insertBefore(wrap, tbl);
      wrap.appendChild(tbl);
    });
  </script>
</body>
</html>
