<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\PointOfSale;
use App\Models\InternetCard;
use App\Models\Transaction;

class DashboardController extends Controller
{
    /**
     * معاملات شحن الرصيد (إضافة رصيد لنقطة البيع) = credit
     */
    private array $TOPUP_TYPES = ['credit'];

    /**
     * Apply authentication and role middleware.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display the admin dashboard with metrics and charts.
     */
    public function index()
    {
        $from = now()->startOfMonth();
        $to   = now()->endOfDay();

        // Metrics
        $totalAccountants = User::role('accountant')->count();
        $totalPoints      = PointOfSale::count();
        $totalCards       = InternetCard::count();
        $totalUsers       = User::count();

        // المبيعات اليومية = عمليات الخصم (debit) فقط
        $dailySales = Transaction::where('type', 'debit')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        // شحنات اليوم = عمليات الإضافة (credit) فقط
        $dailyTopups = Transaction::whereIn('type', $this->TOPUP_TYPES)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        // أحدث الكروت + أحدث الشحنات
        $recentCards  = InternetCard::with(['pos', 'package'])->latest()->take(5)->get();
        $recentTopups = Transaction::with(['pos'])
            ->whereIn('type', $this->TOPUP_TYPES)
            ->latest()
            ->take(5)
            ->get();

        // بيانات الرسوم
        $salesChartData    = $this->buildSalesChartData();      // يعتمد debit فقط
        $locationChartData = $this->buildLocationChartData();

        // ملخص الشحن لكل محاسب + رسم أعلى 7 محاسبين (credit فقط)
        $accountantTopupSummary     = $this->buildAccountantTopupSummary($from, $to);
        $topupByAccountantChartData = $this->buildTopupByAccountantChartData($from, $to, 7);

        return view('admin.dashboard', compact(
            'totalAccountants',
            'totalPoints',
            'totalCards',
            'totalUsers',
            'dailySales',
            'dailyTopups',
            'recentCards',
            'recentTopups',
            'salesChartData',
            'locationChartData',
            'accountantTopupSummary',
            'topupByAccountantChartData'
        ));
    }

    /**
     * Build chart data for monthly sales (debit only) over the last 6 months.
     *
     * @return array{labels: string[], datasets: array[]}
     */
    protected function buildSalesChartData(): array
    {
        $salesData = Transaction::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('type', 'debit')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $arabicMonths = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];

        $labels = [];
        $data   = [];

        for ($i = 5; $i >= 0; $i--) {
            $dt    = now()->subMonths($i);
            $month = $dt->month;
            $labels[] = $arabicMonths[$month] ?? $dt->format('M');
            $entry    = $salesData->firstWhere('month', $month);
            $data[]   = $entry->total ?? 0;
        }

        return [
            'labels'   => $labels,
            'datasets' => [[
                'label'           => 'المبيعات الشهرية (debit)',
                'data'            => $data,
                'backgroundColor' => 'rgba(67, 97, 238, 0.2)',
                'borderColor'     => 'rgba(67, 97, 238, 1)',
                'borderWidth'     => 2,
            ]],
        ];
    }

    /**
     * Build chart data for top 5 POS locations by card count.
     *
     * @return array{labels: string[], datasets: array[]}
     */
    protected function buildLocationChartData(): array
    {
        $locations = PointOfSale::select('point_of_sales.name', DB::raw('COUNT(internet_cards.id) as total'))
            ->leftJoin('internet_cards', 'point_of_sales.id', '=', 'internet_cards.pos_id')
            ->groupBy('point_of_sales.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $labels = $locations->pluck('name')->toArray();
        $data   = $locations->pluck('total')->toArray();

        $backgroundColors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
        ];

        $borderColors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
        ];

        return [
            'labels'   => $labels,
            'datasets' => [[
                'label'           => 'أعلى 5 نقاط بيع',
                'data'            => $data,
                'backgroundColor' => $backgroundColors,
                'borderColor'     => $borderColors,
                'borderWidth'     => 1,
            ]],
        ];
    }

    /**
     * ملخص شحن الرصيد (credit) لكل محاسب خلال فترة.
     *
     * @param \Illuminate\Support\Carbon $from
     * @param \Illuminate\Support\Carbon $to
     * @return array<int, array<string, mixed>>
     */
    protected function buildAccountantTopupSummary(Carbon $from, Carbon $to): array
    {
        $accountants = User::role('accountant')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $posTotalAgg = DB::table('point_of_sales')
            ->select('accountant_id', DB::raw('COUNT(*) as pos_total'))
            ->groupBy('accountant_id')
            ->get()
            ->keyBy('accountant_id');

        $posCreatedAgg = DB::table('point_of_sales')
            ->whereBetween('created_at', [$from, $to])
            ->select('accountant_id', DB::raw('COUNT(*) as pos_created_in_range'))
            ->groupBy('accountant_id')
            ->get()
            ->keyBy('accountant_id');

        $topupAgg = DB::table('transactions as t')
            ->join('point_of_sales as pos', 'pos.id', '=', 't.pos_id')
            ->whereIn('t.type', $this->TOPUP_TYPES) // credit
            ->whereBetween('t.created_at', [$from, $to])
            ->groupBy('pos.accountant_id')
            ->select([
                'pos.accountant_id',
                DB::raw('COUNT(t.id) as topup_ops'),
                DB::raw('COALESCE(SUM(t.amount),0) as topup_total'),
            ])
            ->get()
            ->keyBy('accountant_id');

        $rows = [];
        foreach ($accountants as $a) {
            $rows[] = [
                'accountant_id'        => $a->id,
                'accountant_name'      => $a->name,
                'accountant_email'     => $a->email,
                'pos_total'            => $posTotalAgg[$a->id]->pos_total ?? 0,
                'pos_created_in_range' => $posCreatedAgg[$a->id]->pos_created_in_range ?? 0,
                'topup_ops'            => $topupAgg[$a->id]->topup_ops ?? 0,
                'topup_total'          => $topupAgg[$a->id]->topup_total ?? 0,
            ];
        }

        usort($rows, fn($x, $y) => ($y['topup_total'] <=> $x['topup_total']));
        return $rows;
    }

    /**
     * رسم بياني Top-N للمحاسبين حسب إجمالي الشحن (credit) خلال الفترة.
     *
     * @param \Illuminate\Support\Carbon $from
     * @param \Illuminate\Support\Carbon $to
     * @param int $limit
     * @return array{labels: string[], datasets: array[]}
     */
    protected function buildTopupByAccountantChartData(Carbon $from, Carbon $to, int $limit = 7): array
    {
        $rows = DB::table('users as u')
            ->join('point_of_sales as pos', 'pos.accountant_id', '=', 'u.id')
            ->join('transactions as t', 't.pos_id', '=', 'pos.id')
            ->whereIn('t.type', $this->TOPUP_TYPES) // credit
            ->whereBetween('t.created_at', [$from, $to])
            ->groupBy('u.id', 'u.name')
            ->select([
                'u.name as accountant_name',
                DB::raw('COALESCE(SUM(t.amount),0) as topup_total'),
            ])
            ->orderByDesc('topup_total')
            ->limit($limit)
            ->get();

        return [
            'labels'   => $rows->pluck('accountant_name')->toArray(),
            'datasets' => [[
                'label'           => 'إجمالي الشحن (الشهر الحالي - credit)',
                'data'            => $rows->pluck('topup_total')->toArray(),
                'backgroundColor' => 'rgba(16, 185, 129, 0.25)', // أخضر شفاف
                'borderColor'     => 'rgba(16, 185, 129, 1)',
                'borderWidth'     => 2,
            ]],
        ];
    }
}
