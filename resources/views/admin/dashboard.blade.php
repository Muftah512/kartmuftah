@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">لوحة تحكم المدير العام</h1>
        <div class="text-sm text-gray-500">
            آخر دخول: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y H:i') : 'أول دخول' }}
        </div>
    </div>

    {{-- بطاقات الملخص --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-semibold">نقاط البيع</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalPoints }}</p>
                </div>
                <i class="fas fa-store text-3xl opacity-80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.pos.index') }}" class="text-blue-100 hover:text-white flex items-center">
                    عرض الكل
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-semibold">المستخدمين</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
                </div>
                <i class="fas fa-users text-3xl opacity-80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-green-100 hover:text-white flex items-center">
                    عرض الكل
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-semibold">المبيعات اليومية</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($dailySales) }} ريال</p>
                </div>
                <i class="fas fa-chart-line text-3xl opacity-80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.reports.sales') }}" class="text-purple-100 hover:text-white flex items-center">
                    عرض التقارير
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-semibold">الكروت المولدة</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalCards }}</p>
                </div>
                <i class="fas fa-sim-card text-3xl opacity-80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.reports.cards') }}" class="text-red-100 hover:text-white flex items-center">
                    عرض التفاصيل
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>

        {{-- خانة "تقارير المحاسبين" المطلوبة --}}
        <div class="bg-gradient-to-r from-amber-500 to-amber-700 rounded-xl shadow-lg p-6 text-white lg:col-span-2">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-lg font-semibold">تقارير المحاسبين</p>
                    <div class="mt-2 space-y-1">
                        <p class="text-sm text-amber-100">عدد المحاسبين: <span class="font-bold text-white">{{ $totalAccountants }}</span></p>
                        <p class="text-sm text-amber-100">شحنات اليوم (recharge): <span class="font-bold text-white">{{ number_format($dailyTopups ?? 0) }}</span> ريال</p>
                    </div>
                </div>
                <i class="fas fa-file-invoice-dollar text-3xl opacity-90"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.accountants.topups.index') }}" class="text-amber-100 hover:text-white flex items-center">
                    فتح تقرير المحاسبين
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- أحدث الكروت --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">أحدث الكروت المولدة</h2>
            <a href="{{ route('admin.reports.cards') }}" class="text-blue-600 hover:text-blue-800">عرض الكل</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الباقة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نقطة البيع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentCards as $card)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $card->username }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ optional($card->package)->name }}</div>
                            @if(optional($card->package)->price !== null)
                                <div class="text-sm text-gray-500">{{ number_format(optional($card->package)->price) }} ريال</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ optional($card->pos)->name }}</div>
                            <div class="text-sm text-gray-500">{{ optional($card->pos)->location }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $card->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $card->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $card->status === 'active' ? 'نشط' : 'معاد شحنه' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ملخص تقارير المحاسبين (الشهر الحالي) --}}
    @isset($accountantTopupSummary)
    @php
        $accRows = array_slice($accountantTopupSummary, 0, 10);
    @endphp
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">ملخص تقارير المحاسبين (الشهر الحالي)</h2>
            <a href="{{ route('admin.accountants.topups.index') }}" class="text-amber-600 hover:text-amber-800 flex items-center">
                فتح التقرير الكامل
                <i class="fas fa-arrow-left mr-2"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-right">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">المحاسب</th>
                        <th class="px-4 py-3">نقاط البيع (إجمالي)</th>
                        <th class="px-4 py-3">نقاط أُنشئت</th>
                        <th class="px-4 py-3">عمليات الشحن (recharge)</th>
                        <th class="px-4 py-3">إجمالي الشحن (ريال)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accRows as $i => $r)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $i+1 }}</td>
                            <td class="px-4 py-2 font-medium">{{ $r['accountant_name'] }}</td>
                            <td class="px-4 py-2">{{ $r['pos_total'] }}</td>
                            <td class="px-4 py-2">{{ $r['pos_created_in_range'] }}</td>
                            <td class="px-4 py-2">{{ $r['topup_ops'] }}</td>
                            <td class="px-4 py-2">{{ number_format($r['topup_total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات في الشهر الحالي.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endisset

    {{-- الرسوم البيانية --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">إحصائيات المبيعات</h2>
            <canvas id="salesChart" height="300"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">نقاط البيع حسب الموقع</h2>
            <canvas id="locationsChart" height="300"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // مخطط المبيعات
  new Chart(
    document.getElementById('salesChart').getContext('2d'),
    {
      type: 'bar',
      data: {
        labels: @json($salesChartData['labels']),
        datasets: [{
          label: 'إجمالي المبيعات (بطاقة)',
          data:  @json($salesChartData['datasets'][0]['data']),
          backgroundColor: 'rgba(67, 97, 238, 0.2)',
          borderColor: 'rgba(67, 97, 238, 1)',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true, ticks: { font: { family: 'Tajawal, sans-serif' } } },
          x: { ticks: { font: { family: 'Tajawal, sans-serif' } } }
        },
        plugins: { legend: { labels: { font: { family: 'Tajawal, sans-serif' } } } }
      }
    }
  );

  // مخطط المواقع
  new Chart(
    document.getElementById('locationsChart').getContext('2d'),
    {
      type: 'pie',
      data: {
        labels: @json($locationChartData['labels']),
        datasets: [{
          data: @json($locationChartData['datasets'][0]['data']),
          backgroundColor: @json($locationChartData['datasets'][0]['backgroundColor']),
          borderColor: @json($locationChartData['datasets'][0]['borderColor']),
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { font: { family: 'Tajawal, sans-serif' } } } }
      }
    }
  );
</script>
@endsection
