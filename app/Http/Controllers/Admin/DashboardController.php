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
        // Metrics
        $totalAccountants     = User::role('accountant')->count();
        $totalPoints          = PointOfSale::count();
        $totalCards           = InternetCard::count();
        $totalUsers           = User::count();
        $dailySales           = Transaction::whereDate('created_at', Carbon::today())->sum('amount');
        $recentCards          = InternetCard::with(['pos', 'package'])
                                           ->latest()
                                           ->take(5)
                                           ->get();

        // Chart data
        $salesChartData      = $this->buildSalesChartData();
        $locationChartData   = $this->buildLocationChartData();

        return view('admin.dashboard', compact(
            'totalAccountants',
            'totalPoints',
            'totalCards',
            'totalUsers',
            'dailySales',
            'recentCards',
            'salesChartData',
            'locationChartData'
        ));
    }

    /**
     * Build chart data for monthly sales over the last 6 months.
     *
     * @return array{labels: string[], datasets: array[]}
     */
    protected function buildSalesChartData(): array
    {
        // Fetch aggregated sales per month for current year
        $salesData = Transaction::selectRaw(
                'MONTH(created_at) as month, SUM(amount) as total'
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Arabic month names
        $arabicMonths = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس',    4 => 'أبريل',
            5 => 'مايو',   6 => 'يونيو',  7 => 'يوليو',  8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر',11 => 'نوفمبر',12 => 'ديسمبر'
        ];

        $labels = [];
        $data   = [];

        // Build last 6 months labels and data
        for ($i = 5; $i >= 0; $i--) {
            $dt    = now()->subMonths($i);
            $month = $dt->month;
            $labels[] = $arabicMonths[$month] ?? $dt->format('M');
            $entry    = $salesData->firstWhere('month', $month);
            $data[]   = $entry->total ?? 0;
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'المبيعات الشهرية',
                    'data'            => $data,
                    'backgroundColor' => 'rgba(67, 97, 238, 0.2)',
                    'borderColor'     => 'rgba(67, 97, 238, 1)',
                    'borderWidth'     => 2,
                ],
            ],
        ];
    }

    /**
     * Build chart data for top 5 point-of-sale locations by card count.
     *
     * @return array{labels: string[], datasets: array[]}
     */
    protected function buildLocationChartData(): array
    {
        // Fetch top 5 locations by card count
        $locations = PointOfSale::select('point_of_sales.name', DB::raw('COUNT(internet_cards.id) as total'))
            ->leftJoin('internet_cards', 'point_of_sales.id', '=', 'internet_cards.pos_id')
            ->groupBy('point_of_sales.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $labels = $locations->pluck('name')->toArray();
        $data   = $locations->pluck('total')->toArray();

        // Colors for bars
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
            'datasets' => [
                [
                    'label'           => 'أعلى 5 نقاط بيع',
                    'data'            => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor'     => $borderColors,
                    'borderWidth'     => 1,
                ],
            ],
        ];
    }
}
