<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
  <title>@yield('title','لوحة تحكم الوكيل لشبكة المفتاح')</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Icons + Font -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet"/>

  <style>
    :root { --nav-h: 64px; }
    html, body { height: 100%; }
    html { -webkit-tap-highlight-color: transparent; }
    body {
      font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      background: radial-gradient(1200px 600px at 85% -10%, #dbeafe 0%, transparent 60%),
                  radial-gradient(900px 500px at -10% 110%, #e9d5ff 0%, transparent 60%),
                  #f6f7fb;
      overflow-x: hidden;
    }

    /* iOS: منع تكبير المدخلات */
    input, select, textarea { font-size: 16px !important; }

    /* زجاجية أنيقة */
    .glass {
      background: rgba(255,255,255,.65);
      border: 1px solid rgba(255,255,255,.35);
      -webkit-backdrop-filter: blur(14px);
      backdrop-filter: blur(14px);
    }

    /* سلايد للدرج الجانبي */
    .slide-enter   { transform: translateX(100%); opacity: 0; }
    .slide-enter-active { transform: translateX(0); opacity: 1; transition: transform .28s ease, opacity .2s ease; }
    .slide-leave-active { transform: translateX(100%); opacity: 0; transition: transform .24s ease, opacity .18s ease; }

    /* حركات خفيفة للبطاقات */
    .card-anim { transition: transform .2s ease, box-shadow .2s ease; }
    .card-anim:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0,0,0,.06); }

    @media (prefers-reduced-motion: reduce) {
      .slide-enter, .slide-enter-active, .slide-leave-active, .card-anim { transition: none !important; }
    }
  </style>
</head>
<body class="min-h-[100svh]">

  <!-- شريط علوي زجاجي ثابت -->
  <header class="fixed top-0 inset-x-0 z-50 glass shadow">
    <div class="h-[var(--nav-h)] max-w-7xl mx-auto px-3 sm:px-5 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <button id="btn-open" class="lg:hidden text-blue-700 p-2 rounded hover:bg-blue-50" aria-label="فتح القائمة">
          <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <a href="{{ route('accountant.dashboard') }}" class="flex items-center gap-2 text-blue-700 font-extrabold">
          <i class="fa-solid fa-calculator"></i>
          <span class="truncate">@yield('title','لوحة تحكم المحاسب')</span>
        </a>
      </div>

      <div class="flex items-center gap-4">
        {{-- زر الإشعارات (قائمة + رابط احتياطي) --}}
        @if (View::exists('layouts.partials.notifications'))
            @include('layouts.partials.notifications')
        @else
            @php $unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0; @endphp
            <a href="{{ route('notifications.index') }}"
               class="relative text-gray-700 hover:text-blue-700 p-2 rounded hover:bg-gray-50"
               aria-label="الإشعارات">
              <i class="fa-regular fa-bell text-xl"></i>
              @if($unread > 0)
                <span class="absolute -top-1 -left-1 min-w-[18px] h-[18px] px-1 text-[10px] leading-[18px] text-white bg-rose-600 rounded-full text-center">
                  {{ $unread }}
                </span>
              @endif
            </a>
        @endif

        @auth
        <div class="flex items-center gap-3">
          <!-- صورة خاصة بالمحاسب فقط عبر راوت محمي -->
          <a href="{{ route('accountant.profile.edit') }}" class="shrink-0" title="إعدادات الحساب">
            <img src="{{ route('accountant.profile.avatar.show') }}?t={{ now()->timestamp }}"
                 class="w-10 h-10 rounded-full border-2 border-blue-200 object-cover"
                 alt="صورة {{ Auth::user()->name }}" loading="lazy">
          </a>
          <div class="hidden sm:block text-right leading-tight">
            <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-600">{{ Auth::user()->getRoleNames()->first() }}</p>
          </div>
        </div>
        @endauth
      </div>
    </div>
  </header>

  <!-- غطاء التعتيم للموبايل -->
  <div id="backdrop" class="fixed inset-0 z-40 bg-black/40 opacity-0 pointer-events-none transition-opacity"></div>

  <!-- مخطط الصفحة -->
  <div class="pt-[var(--nav-h)]">
    <div class="max-w-7xl mx-auto lg:grid lg:grid-cols-[18rem_1fr] lg:gap-6 px-3 sm:px-5">

      <!-- Sidebar -->
      <aside id="sidebar"
             class="glass shadow-lg rounded-s-2xl
                    fixed top-[var(--nav-h)] right-0 w-72 h-[calc(100svh-var(--nav-h))]
                    overflow-y-auto z-50 slide-enter
                    lg:static lg:h-auto lg:rounded-2xl lg:shadow lg:translate-x-0 lg:opacity-100">
        <nav class="p-4">
          <p class="font-extrabold text-blue-700 mb-2">القائمة الرئيسية</p>
          <ul class="space-y-1">
            <li>
              <a href="{{ route('accountant.dashboard') }}"
                 class="flex items-center gap-3 p-3 rounded-xl card-anim
                        {{ request()->routeIs('accountant.dashboard') ? 'bg-gradient-to-l from-blue-600 to-blue-500 text-white' : 'hover:bg-blue-50 text-gray-800' }}">
                <i class="fa-solid fa-house"></i><span>الرئيسية</span>
              </a>
            </li>
            <li>
              <a href="{{ route('accountant.pos.index') }}"
                 class="flex items-center gap-3 p-3 rounded-xl card-anim
                        {{ request()->routeIs('accountant.pos.*') ? 'bg-gradient-to-l from-blue-600 to-blue-500 text-white' : 'hover:bg-blue-50 text-gray-800' }}">
                <i class="fa-solid fa-store"></i><span>نقاط البيع</span>
              </a>
            </li>
            <li>
              <a href="{{ route('accountant.invoices.index') }}"
                 class="flex items-center gap-3 p-3 rounded-xl card-anim
                        {{ request()->routeIs('accountant.invoices.*') ? 'bg-gradient-to-l from-blue-600 to-blue-500 text-white' : 'hover:bg-blue-50 text-gray-800' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i><span>الفواتير</span>
              </a>
            </li>
            <li>
              <a href="{{ route('accountant.recharges.index') }}"
                 class="flex items-center gap-3 p-3 rounded-xl card-anim
                        {{ request()->routeIs('accountant.recharges.*') ? 'bg-gradient-to-l from-blue-600 to-blue-500 text-white' : 'hover:bg-blue-50 text-gray-800' }}">
                <i class="fa-solid fa-money-bill-wave"></i><span>عمليات الشحن</span>
              </a>
            </li>
            <!-- جديد: الإعدادات -->
            <li>
              <a href="{{ route('accountant.profile.edit') }}"
                 class="flex items-center gap-3 p-3 rounded-xl card-anim
                        {{ request()->routeIs('accountant.profile.edit') ? 'bg-gradient-to-l from-blue-600 to-blue-500 text-white' : 'hover:bg-blue-50 text-gray-800' }}">
                <i class="fa-solid fa-user-gear"></i><span>الإعدادات</span>
              </a>
            </li>
          </ul>
          <div class="my-4 border-t border-white/50"></div>
          <form method="POST" action="{{ route('logout') }}" class="pt-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 text-gray-800">
              <i class="fa-solid fa-right-from-bracket"></i><span>تسجيل الخروج</span>
            </button>
          </form>
        </nav>
      </aside>

      <!-- المحتوى -->
      <main id="main"
            class="min-w-0 py-4 lg:py-6">
        <!-- بطاقات علوية -->
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-4">
          @yield('stats')
        </section>

        <!-- غلاف المحتوى الحقيقي -->
        <section id="content-wrapper" class="glass rounded-2xl shadow p-3 sm:p-5">
          @yield('content')
        </section>
      </main>

    </div>
  </div>

  <footer class="no-print text-center py-4 mt-10">
    <div class="max-w-7xl mx-auto px-4">
      <p class="font-medium text-gray-400">جميع الحقوق محفوظة &copy; {{ date('Y') }} برمجة :عبدالرحمن منير</p>
      <p class="text-sm text-gray-500 mt-1">الإصدار 3.1 Final</p>
    </div>
  </footer>

  <!-- AlpineJS للقائمة المنسدلة في الإشعارات -->
  <script src="https://unpkg.com/alpinejs" defer></script>

  <script>
    const sidebar  = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    const openBtn  = document.getElementById('btn-open');

    // افتح الدرج
    function openSidebar() {
      sidebar.classList.remove('slide-leave-active');
      sidebar.classList.add('slide-enter-active');
      backdrop.classList.remove('pointer-events-none');
      backdrop.classList.add('opacity-100');
    }
    // أغلق الدرج
    function closeSidebar() {
      sidebar.classList.remove('slide-enter-active');
      sidebar.classList.add('slide-leave-active');
      backdrop.classList.add('pointer-events-none');
      backdrop.classList.remove('opacity-100');
    }

    openBtn?.addEventListener('click', openSidebar);
    backdrop.addEventListener('click', closeSidebar);
    window.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

    // اغلاق تلقائي عند اتساع الشاشة
    matchMedia('(min-width:1024px)').addEventListener('change', e => { if (e.matches) closeSidebar(); });

    // لفّ تلقائي للجداول لمنع القصّ
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('#content-wrapper table').forEach(tbl => {
        if (!tbl.parentElement || !tbl.parentElement.classList.contains('tw-table-wrap')) {
          const wrap = document.createElement('div');
          wrap.className = 'tw-table-wrap overflow-x-auto -mx-2 sm:mx-0';
          tbl.parentNode.insertBefore(wrap, tbl);
          wrap.appendChild(tbl);
        }
        tbl.classList.add('min-w-full');
      });
    });
  </script>
</body>
</html>
