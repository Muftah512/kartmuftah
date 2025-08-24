<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>نظام نقاط البيع - @yield('title','لوحة التحكم')</title>

  <!-- Tailwind v2 CDN (متوافق مع الأصناف المستخدمة) -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <!-- خط Tajawal -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

  <style>
    /* ===== 🔮 متغيرات تصميم الوهج المستقبلي v2 🔮 ===== */
    :root {
      --nav-h: 64px;
      --primary: #22d3ee;      /* سماوي حيوي */
      --accent:  #a78bfa;       /* بنفسجي فاتح */
      --primary-glow: 0 0 25px -5px var(--primary);
      --accent-glow:  0 0 25px -5px var(--accent);
      --bg-dark: #111827;      /* رمادي-900 */
      --bg-surface: #1f2937;   /* رمادي-800 */
      --text-light: #f9fafb;   /* رمادي-50 */
      --text-muted: #9ca3af;   /* رمادي-400 */
      --glass: rgba(31, 41, 55, 0.5);        /* زجاج داكن */
      --glass-border: rgba(255, 255, 255, .1);
    }

    @keyframes gradient-animation {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* ===== ✨ الأساس العام ✨ ===== */
    html, body { height: 100%; }
    body {
      font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      color: var(--text-light);
      background: linear-gradient(-45deg, #111827, #1e283c, #111827, #281f3a);
      background-size: 400% 400%;
      animation: gradient-animation 15s ease infinite;
    }
    .min-h-dvh{ min-height: 100dvh; }
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    .text-primary{ color: var(--primary) !important; }
    .bg-primary{ background-color: var(--primary) !important; }

    /* ===== 💎 الهيدر الزجاجي 💎 ===== */
    .header-glass { backdrop-filter: blur(12px) saturate(150%); background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1.25rem; box-shadow: 0 12px 40px rgba(0,0,0,.3); }
    .pill{ display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1rem; border-radius:9999px; background:rgba(255,255,255,.05); color:#fff; border:1px solid rgba(255,255,255,.1); backdrop-filter: blur(5px); transition:all .2s; }
    .pill:hover{ background:rgba(255,255,255,.1); transform:translateY(-2px); box-shadow: var(--primary-glow); }

    /* ===== 캡슐 كبسولات الأيقونات ===== */
    .icon-capsule{ display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:.75rem; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); font-size:1.1rem; transition:all .2s; }
    .side-link{ display:flex; align-items:center; gap:.75rem; padding:.5rem; border-radius:.75rem; color:var(--text-muted); transition:all .2s; }
    .side-link:hover{ color:var(--text-light); background:rgba(255,255,255,.03); }
    .side-link:hover .icon-capsule{ background:rgba(255,255,255,.1); border-color:rgba(255,255,255,.15); transform:scale(1.08); }
    .side-link.active{ color:#fff; font-weight:700; background:linear-gradient(90deg, var(--primary), var(--accent)); box-shadow:var(--primary-glow); }
    .side-link.active .icon-capsule{ background:#fff; color:var(--primary); box-shadow:0 0 15px -2px #fff; }

    /* ===== 🌌 السايدبار والتخطيط 🌌 ===== */
    #sidebar{ background:var(--bg-surface); border:1px solid var(--glass-border); }
    .soft-card{ background:var(--bg-surface); border-radius:1rem; border:1px solid var(--glass-border); box-shadow:0 10px 30px rgba(0,0,0,.2); }
    .balance-card{ background:linear-gradient(135deg, var(--primary), var(--accent)); color:#fff; text-shadow:0 2px 5px rgba(0,0,0,.2); box-shadow:0 8px 25px -8px #000; }

    /* تخطيط الشبكة */
    .layout{ display:block; }
    @media (min-width:768px){ .layout{ display:grid; grid-template-columns: 16rem 1fr; grid-gap: 1.5rem; } }

    /* درج للجوال */
    @media (max-width: 767px){
      #sidebar{ position: fixed; top:0; right:0; height:100dvh; width:18rem; z-index:60; transform: translateX(100%); transition: transform .25s ease; border-top-left-radius:.75rem; border-bottom-left-radius:.75rem; }
      #sidebar.open{ transform: translateX(0); }
    }

    /* Overlay */
    #overlay{ position:fixed; inset:0; background:rgba(0,0,0,.4); opacity:0; pointer-events:none; transition:opacity .2s; z-index:50; backdrop-filter: blur(4px); }
    #overlay.show{ opacity:1; pointer-events:auto; }

    /* مسار التنقل */
    .breadcrumb a { color: var(--text-muted); transition: color .2s; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb span { color: var(--text-light); font-weight: 600; }
    .breadcrumb i { color: var(--text-muted); }

    /* 📱 شريط سفلي + زر طافٍ */
    .fab{ background:linear-gradient(135deg, var(--primary), var(--accent)); color:#fff; box-shadow: var(--accent-glow); transition: transform .2s; }
    .fab:hover{ transform: translateY(-2px); }
    .mobile-nav{ background:rgba(31,41,55,.7); backdrop-filter: blur(12px); border-top:1px solid var(--glass-border); height:64px; }
    .mobile-nav a{ display:flex; flex-direction:column; align-items:center; gap:.25rem; font-size:.75rem; color:var(--text-muted); }
    .mobile-nav a i{ font-size:1.2rem; }
    .mobile-nav a.active{ color: var(--primary); }
    .mobile-nav a.active i{ text-shadow: var(--primary-glow); }
    @media (max-width: 767px){ main{ padding-bottom: 5rem; } }

    /* تحسينات وصولية */
    .focus-ring{ outline:none; box-shadow:0 0 0 3px rgba(34,211,238,.35); }
    .pill:focus-visible, a:focus-visible, button:focus-visible{ outline: none; box-shadow: 0 0 0 3px rgba(34,211,238,.35); border-radius: .75rem; }
  </style>
</head>
<body class="min-h-dvh pb-safe">

  <!-- Header -->
  <header class="header-wrap no-print">
    <div class="max-w-7xl mx-auto px-4 py-3">
      <div class="header-glass px-3 sm:px-4 py-2">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2 space-x-reverse">
            <button id="sidebarToggle" class="pill" aria-label="فتح القائمة" aria-expanded="false" title="القائمة">
              <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <a href="{{ route('pos.dashboard') }}" class="pill hidden sm:inline-flex" title="الرئيسية">
              <i class="fa-solid fa-wifi text-primary"></i>
              <strong>نظام نقاط البيع</strong>
            </a>
          </div>

          <div class="flex items-center space-x-2 space-x-reverse">
            <a href="{{ route('pos.profile.edit') }}" class="pill" title="الإعدادات">
              <i class="fa-solid fa-gear"></i><span class="hidden sm:inline">الإعدادات</span>
            </a>
            <a href="{{ route('pos.profile.edit') }}" class="pill hidden md:inline-flex" title="حسابي">
              <i class="fa-solid fa-user"></i><span class="hidden sm:inline">حسابي</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline"> @csrf
              <button type="submit" class="pill" title="خروج">
                <i class="fa-solid fa-right-from-bracket"></i><span class="hidden sm:inline">خروج</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Overlay للجوال -->
  <div id="overlay" class="md:hidden" aria-hidden="true"></div>

  <!-- Layout -->
  <div id="layout" class="layout max-w-7xl mx-auto w-full px-4 pt-6 md:pt-8">

    <!-- Sidebar -->
    <aside id="sidebar" class="rounded-lg" aria-label="القائمة الجانبية">
      <div class="p-4 border-b border-gray-700">
        <div class="flex items-center space-x-3 space-x-reverse">
          <img src="{{ route('pos.profile.avatar.show') }}?t={{ now()->timestamp }}" alt="صورة {{ auth()->user()->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-gray-600" loading="lazy">
          <div>
            @php $firstPos = auth()->user()->pointOfSale()->first(); @endphp
            @if($firstPos)
              <p class="font-bold text-white">{{ $firstPos->name }}</p>
              <p class="text-sm text-gray-400">{{ auth()->user()->name }}</p>
            @else
              <p class="font-semibold text-white">لا توجد نقطة بيع</p>
            @endif
          </div>
        </div>
        <div class="mt-4 balance-card p-3 rounded-lg">
          <p class="text-sm opacity-90">الرصيد الحالي</p>
          <p class="text-2xl font-extrabold mt-1">{{ $firstPos ? number_format($firstPos->balance) : '0' }} <span class="text-sm">ريال</span></p>
        </div>
      </div>

      <nav class="p-2">
        <ul class="space-y-1">
          <li><a href="{{ route('pos.dashboard') }}"      class="side-link {{ request()->routeIs('pos.dashboard') ? 'active' : '' }}" @if(request()->routeIs('pos.dashboard')) aria-current="page" @endif><span class="icon-capsule"><i class="fa-solid fa-gauge-high"></i></span><span>لوحة التحكم</span></a></li>
          <li><a href="{{ route('pos.cards.generate') }}" class="side-link {{ request()->routeIs('pos.cards.generate') ? 'active' : '' }}" @if(request()->routeIs('pos.cards.generate')) aria-current="page" @endif><span class="icon-capsule"><i class="fa-solid fa-sim-card"></i></span><span>إنشاء كرت</span></a></li>
          <li><a href="{{ route('pos.cards.recharge') }}" class="side-link {{ request()->routeIs('pos.cards.recharge') ? 'active' : '' }}" @if(request()->routeIs('pos.cards.recharge')) aria-current="page" @endif><span class="icon-capsule"><i class="fa-solid fa-rotate-right"></i></span><span>إعادة شحن</span></a></li>
          <li><a href="{{ route('pos.sales') }}"          class="side-link {{ request()->routeIs('pos.sales') ? 'active' : '' }}" @if(request()->routeIs('pos.sales')) aria-current="page" @endif><span class="icon-capsule"><i class="fa-solid fa-chart-line"></i></span><span>التقارير</span></a></li>
          <li><a href="{{ route('pos.transactions') }}"   class="side-link {{ request()->routeIs('pos.transactions') ? 'active' : '' }}" @if(request()->routeIs('pos.transactions')) aria-current="page" @endif><span class="icon-capsule"><i class="fa-solid fa-clock-rotate-left"></i></span><span>السجل</span></a></li>
          <li><a href="{{ route('pos.profile.edit') }}"   class="side-link {{ request()->routeIs('pos.profile.edit') ? 'active' : '' }}" @if(request()->routeIs('pos.profile.edit')) aria-current="page" @endif><span class="icon-capsule"><i class="fa-solid fa-gear"></i></span><span>الإعدادات</span></a></li>
        </ul>
      </nav>

      <div class="p-4 mt-auto">
        <div class="soft-card p-3 text-center border-dashed border-gray-600">
          <p class="text-sm text-gray-400">رقم الدعم الفني</p>
          <p class="font-bold text-white text-lg tracking-wider">773377968</p>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 min-w-0">
      <div class="mb-6 no-print breadcrumb">
        <nav class="flex" aria-label="مسار التنقل">
          <ol class="inline-flex items-center space-x-2 space-x-reverse md:space-x-3">
            <li class="inline-flex items-center">
              <a href="{{ route('pos.dashboard') }}" class="inline-flex items-center text-sm font-medium">
                <i class="fa-solid fa-house ml-2"></i> الرئيسية
              </a>
            </li>
            @yield('breadcrumb')
          </ol>
        </nav>
      </div>

      @if(session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-300 rounded-lg bg-green-900 bg-opacity-50 border border-green-500" role="alert">
          <i class="fa-solid fa-check-circle ml-3"></i>
          <div><strong class="font-bold">نجاح!</strong> {{ session('success') }}</div>
        </div>
      @endif
      @if(session('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-300 rounded-lg bg-red-900 bg-opacity-50 border border-red-500" role="alert">
          <i class="fa-solid fa-triangle-exclamation ml-3"></i>
          <div><strong class="font-bold">خطأ!</strong> {{ session('error') }}</div>
        </div>
      @endif

      <div id="content-wrapper" class="soft-card p-4 sm:p-6">
        @yield('content')
      </div>
    </main>
  </div>

  <!-- Floating Action Button -->
  <a href="{{ route('pos.cards.generate') }}" class="fab fixed right-4 bottom-20 z-50 flex items-center gap-2 p-3 rounded-full" title="إنشاء كرت جديد">
    <i class="fa-solid fa-plus text-xl"></i><span class="hidden">إنشاء كرت</span>
  </a>

  <!-- Mobile Bottom Nav -->
  <nav class="mobile-nav fixed right-0 left-0 bottom-0 z-40 md:hidden no-print" aria-label="التنقل السفلي للجوال">
    <ul class="flex justify-around items-center p-2">
      <li><a href="{{ route('pos.dashboard') }}"    class="{{ request()->routeIs('pos.dashboard') ? 'active' : '' }}"      @if(request()->routeIs('pos.dashboard')) aria-current="page" @endif><i class="fa-solid fa-house"></i><span>الرئيسية</span></a></li>
      <li><a href="{{ route('pos.sales') }}"        class="{{ request()->routeIs('pos.sales') ? 'active' : '' }}"          @if(request()->routeIs('pos.sales')) aria-current="page" @endif><i class="fa-solid fa-chart-line"></i><span>المبيعات</span></a></li>
      <li><a href="{{ route('pos.transactions') }}" class="{{ request()->routeIs('pos.transactions') ? 'active' : '' }}"   @if(request()->routeIs('pos.transactions')) aria-current="page" @endif><i class="fa-solid fa-clock-rotate-left"></i><span>السجل</span></a></li>
      <li><a href="{{ route('pos.profile.edit') }}" class="{{ request()->routeIs('pos.profile.*') ? 'active' : '' }}"      @if(request()->routeIs('pos.profile.*')) aria-current="page" @endif><i class="fa-solid fa-gear"></i><span>الإعدادات</span></a></li>
    </ul>
  </nav>

  <!-- Footer -->
  <footer class="no-print text-center py-4 mt-10">
    <div class="max-w-7xl mx-auto px-4">
      <p class="font-medium text-gray-400">جميع الحقوق محفوظة &copy; {{ date('Y') }} برمجة: عبدالرحمن منير</p>
      <p class="text-sm text-gray-500 mt-1">الإصدار 3.1 Final</p>
    </div>
  </footer>

  <!-- JS: فتح/إغلاق السايدبار + تحسينات وصولية -->
  <script>
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('overlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function openSidebar(){
      sidebar.classList.add('open');
      overlay.classList.add('show');
      toggleBtn?.setAttribute('aria-expanded','true');
      document.body.style.overflow = 'hidden';
    }
    function closeSidebar(){
      sidebar.classList.remove('open');
      overlay.classList.remove('show');
      toggleBtn?.setAttribute('aria-expanded','false');
      document.body.style.overflow = '';
    }

    toggleBtn?.addEventListener('click', (e)=>{ e.preventDefault(); openSidebar(); });
    overlay?.addEventListener('click', closeSidebar);
    window.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeSidebar(); });

    // إغلاق عند اختيار رابط داخل السايدبار على الجوال
    document.querySelectorAll('#sidebar a').forEach(a=>{
      a.addEventListener('click', ()=>{ if (window.matchMedia('(max-width:767px)').matches) closeSidebar(); });
    });
  </script>

  @stack('scripts')
</body>
</html>