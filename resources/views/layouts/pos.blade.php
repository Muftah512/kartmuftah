<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>نظام نقاط البيع - @yield('title','لوحة التحكم')</title>

  <!-- Tailwind v3 -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tajawal -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

  <style>
    :root { --nav-h: 64px; }
    html, body { height: 100%; }
    body { font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#f5f7fa; }
    /* تجنّب تكبير الحقول في iOS */
    input, select, textarea { font-size:16px !important; }
    /* لإظهار الدرج من جهة اليمين (RTL) على الشاشات الصغيرة */
    #sidebar { transform: translateX(100%); transition: transform .25s ease; }
    #sidebar.open { transform: translateX(0); }
  </style>

  @stack('styles')
</head>
<body class="min-h-[100svh] bg-gray-50">

  <!-- Header -->
  <header class="no-print bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
      <div class="h-[var(--nav-h)] flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button id="sidebarToggle" class="md:hidden p-2 rounded hover:bg-white/10" aria-label="فتح القائمة">
            <i class="fas fa-bars text-xl"></i>
          </button>
          <h1 class="text-lg sm:text-xl font-bold">نظام إدارة نقاط البيع</h1>
        </div>

        <div class="flex items-center gap-4">
          <div class="hidden md:flex items-center gap-2">
            <span>{{ auth()->user()->name }}</span>
            @if(auth()->user()->getRoleNames()->isNotEmpty())
              <span class="bg-blue-500/90 text-xs px-2 py-1 rounded-full">{{ auth()->user()->getRoleNames()->first() }}</span>
            @else
              <span class="bg-gray-500/80 text-xs px-2 py-1 rounded-full">لا يوجد دور</span>
            @endif
          </div>

          <div class="relative">
            <button class="block h-9 w-9 rounded-full overflow-hidden ring-2 ring-white/30">
              <img class="h-full w-full object-cover"
                   src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random"
                   alt="صورة المستخدم">
            </button>
          </div>

          <!-- تأكيد: تسجيل الخروج يجب أن يكون POST -->
          <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
            @csrf
            <button type="submit" class="p-2 hover:text-gray-200" title="تسجيل الخروج">
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </form>
          <!-- بديل للجوال -->
          <form method="POST" action="{{ route('logout') }}" class="md:hidden">
            @csrf
            <button type="submit" class="p-2 hover:text-gray-200" title="تسجيل الخروج">
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <!-- Overlay للجوال -->
  <div id="overlay" class="fixed inset-0 z-40 bg-black/40 opacity-0 pointer-events-none transition-opacity md:hidden"></div>

  <!-- Layout -->
  <div class="max-w-7xl mx-auto w-full md:grid md:grid-cols-[16rem_1fr] md:gap-6 px-4 pt-6 md:pt-8">

    <!-- Sidebar -->
    <aside id="sidebar"
      class="no-print fixed top-[calc(var(--nav-h))] right-0 z-50 w-64 h-[calc(100svh-var(--nav-h))]
             bg-white shadow-xl md:shadow-none md:static md:translate-x-0 md:h-auto md:w-auto
             md:rounded-lg rounded-s-xl overflow-y-auto">
      <div class="p-4 border-b">
        <div class="flex items-center gap-3">
          <div class="bg-gray-200 border-2 border-dashed rounded-xl w-14 h-14"></div>
          <div>
            @php $firstPos = auth()->user()->pointOfSale()->first(); @endphp
            @if($firstPos)
              <p class="font-semibold">{{ $firstPos->name }}</p>
              <p class="text-sm text-gray-600">{{ auth()->user()->name }}</p>
            @else
              <p class="font-semibold">لا توجد نقطة بيع مرتبطة</p>
              <p class="text-sm text-gray-600">{{ auth()->user()->name }}</p>
            @endif
          </div>
        </div>

        <div class="mt-4 bg-blue-50 p-3 rounded-lg">
          <p class="text-sm text-gray-600">الرصيد الحالي</p>
          <p class="text-xl font-bold {{ $firstPos ? 'text-green-600' : 'text-gray-500' }}">
            {{ $firstPos ? number_format($firstPos->balance) : '0' }} ريال
          </p>
        </div>
      </div>

      <nav class="p-4">
        <ul class="space-y-2">
          <li>
            <a href="{{ route('pos.dashboard') }}"
               class="flex items-center gap-2 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 {{ request()->routeIs('pos.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
              <i class="fas fa-tachometer-alt"></i><span>لوحة التحكم</span>
            </a>
          </li>
          <li>
            <a href="{{ route('pos.cards.generate') }}"
               class="flex items-center gap-2 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 {{ request()->routeIs('pos.cards.generate') ? 'bg-blue-50 text-blue-700' : '' }}">
              <i class="fas fa-sim-card"></i><span>إنشاء كرت جديد</span>
            </a>
          </li>
          <li>
            <a href="{{ route('pos.cards.recharge') }}"
               class="flex items-center gap-2 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 {{ request()->routeIs('pos.cards.recharge') ? 'bg-blue-50 text-blue-700' : '' }}">
              <i class="fas fa-redo"></i><span>إعادة شحن كرت</span>
            </a>
          </li>
          <li>
            <a href="{{ route('pos.sales') }}"
               class="flex items-center gap-2 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 {{ request()->routeIs('pos.sales') ? 'bg-blue-50 text-blue-700' : '' }}">
              <i class="fas fa-chart-line"></i><span>تقارير المبيعات</span>
            </a>
          </li>
          <li>
            <a href="{{ route('pos.transactions') }}"
               class="flex items-center gap-2 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 {{ request()->routeIs('pos.transactions') ? 'bg-blue-50 text-blue-700' : '' }}">
              <i class="fas fa-history"></i><span>سجل المعاملات</span>
            </a>
          </li>
          <li>
            <a href="#"
               class="flex items-center gap-2 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600">
              <i class="fas fa-cog"></i><span>الإعدادات</span>
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

    <!-- Main -->
    <main class="flex-1 min-w-0">
      <div class="py-6">
        <!-- breadcrumb -->
        <div class="mb-6 no-print">
          <nav class="flex" aria-label="مسار التنقل">
            <ol class="inline-flex items-center gap-2 md:gap-3">
              <li class="inline-flex items-center">
                <a href="{{ route('pos.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                  <i class="fas fa-home ms-0 me-2"></i> الرئيسية
                </a>
              </li>
              @yield('breadcrumb')
            </ol>
          </nav>
        </div>

        <!-- alerts -->
        @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">نجاح!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
          </div>
        @endif
        @if(session('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">خطأ!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
          </div>
        @endif

        <!-- content -->
        <div id="content-wrapper" class="bg-white rounded-xl shadow-md p-4 sm:p-6">
          @yield('content')
        </div>
      </div>
    </main>
  </div>

  <!-- Footer -->
  <footer class="no-print bg-gray-800 text-white py-4 text-center">
    <div class="max-w-7xl mx-auto px-4">
      <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام إدارة نقاط البيع</p>
      <p class="text-sm text-gray-400 mt-1">الإصدار 2.1.0</p>
    </div>
  </footer>

  <!-- JS: بدون jQuery -->
  <script>
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('overlay');
    const toggleBtn= document.getElementById('sidebarToggle');

    function openSidebar(){
      sidebar.classList.add('open');
      overlay.classList.remove('pointer-events-none');
      overlay.classList.add('opacity-100');
    }
    function closeSidebar(){
      sidebar.classList.remove('open');
      overlay.classList.add('pointer-events-none');
      overlay.classList.remove('opacity-100');
    }
    toggleBtn?.addEventListener('click', openSidebar);
    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('keydown', e=>{ if(e.key==='Escape') closeSidebar(); });
    matchMedia('(min-width:768px)').addEventListener('change', e=>{ if(e.matches) closeSidebar(); });
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch(console.error);
    });
  }
    // لفّ أي جدول تلقائيًا لمنع قصّ البيانات على الجوال
    document.addEventListener('DOMContentLoaded', ()=>{
      document.querySelectorAll('#content-wrapper table').forEach(t=>{
        if(!t.parentElement.classList.contains('tw-wrap')){
          const w=document.createElement('div');
          w.className='tw-wrap overflow-x-auto -mx-2 sm:mx-0';
          t.parentNode.insertBefore(w,t);
          w.appendChild(t);
        }
        t.classList.add('min-w-full');
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
