<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Models\PointOfSale;
use App\Models\Transaction;
use App\Models\InternetCard;
use App\Models\Package;
use App\Models\User;
use App\Exports\SalesExport;
use App\Exports\CardsExport;
use App\Exports\FinancialExport;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * عرض تقرير المبيعات
     */
    public function salesReport(Request $request)
    {
        $startDate    = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate      = $request->input('end_date', now()->format('Y-m-d'));
        $posId        = $request->input('pos_id');
        $pointsOfSale = PointOfSale::all();

        $transactions = Transaction::with('pointOfSale')
            ->where('type', 'debit')
            ->when($posId, fn($q) => $q->where('pos_id', $posId))
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $totalAmount = $transactions->sum('amount');

        return view('admin.reports.sales', compact(
            'transactions',
            'totalAmount',
            'startDate',
            'endDate',
            'pointsOfSale',
            'posId'
        ));
    }

    /**
     * تصدير تقرير المبيعات لـ Excel
     */
    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));
        $posId     = $request->input('pos_id');

        return Excel::download(
            new SalesExport($startDate, $endDate, $posId),
            'sales-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * عرض تقرير المبيعات بصيغة PDF
     */
    public function pdfSalesReport(Request $request)
    {
        $startDate    = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate      = $request->input('end_date', now()->format('Y-m-d'));
        $posId        = $request->input('pos_id');
        $pointsOfSale = PointOfSale::all();

        $transactions = Transaction::with('pointOfSale')
            ->where('type', 'debit')
            ->when($posId, fn($q) => $q->where('pos_id', $posId))
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAmount = $transactions->sum('amount');
        $pointOfSale = $posId ? PointOfSale::find($posId) : null;

        $pdf = PDF::loadView('admin.reports.pdf.sales', compact(
            'transactions',
            'totalAmount',
            'startDate',
            'endDate',
            'pointOfSale',
            'pointsOfSale',
            'posId'
        ));

        return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * عرض تقرير بطاقات الإنترنت
     */
    public function cardsReport(Request $request)
    {
        $startDate    = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate      = $request->input('end_date', now()->format('Y-m-d'));
        $posId        = $request->input('pos_id');
        $packageId    = $request->input('package_id');
        $pointsOfSale = PointOfSale::all();
        $packages     = Package::all();

        $cards = InternetCard::with(['package', 'pointOfSale'])
            ->when($posId, fn($q) => $q->where('pos_id', $posId))
            ->when($packageId, fn($q) => $q->where('package_id', $packageId))
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        // إحصائيات سريعة
        $todayCards  = InternetCard::whereDate('created_at', Carbon::today())->count();
        $totalPeriod = InternetCard::whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])->count();
        $daysDiff    = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) ?: 1;

        $activeCards  = $totalPeriod;
        $usedCards    = InternetCard::whereNotNull('used_at')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])->count();
        $averageDaily = round($totalPeriod / $daysDiff, 2);

        return view('admin.reports.cards', compact(
            'cards',
            'startDate',
            'endDate',
            'pointsOfSale',
            'packages',
            'posId',
            'packageId',
            'todayCards',
            'activeCards',
            'usedCards',
            'averageDaily'
        ));
    }

    /**
     * تصدير تقرير بطاقات الإنترنت لـ Excel
     */
    public function exportCardsReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));
        $posId     = $request->input('pos_id');
        $packageId = $request->input('package_id');

        return Excel::download(
            new CardsExport($startDate, $endDate, $posId, $packageId),
            'cards-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * عرض التقرير المالي
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));

        $income = Transaction::where('type', 'credit')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])->sum('amount');

        $expenses = Transaction::where('type', 'debit')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])->sum('amount');

        $netProfit = $income - $expenses;

        $topPos = PointOfSale::withSum(['transactions' => fn($q) =>
                $q->where('type', 'debit')
                  ->whereBetween('created_at', [
                      Carbon::parse($startDate)->startOfDay(),
                      Carbon::parse($endDate)->endOfDay(),
                  ])
            ], 'amount')
            ->orderByDesc('transactions_sum_amount')
            ->take(5)
            ->get();

        $packageSales = Package::withSum(['cards' => fn($q) =>
                $q->whereBetween('created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay(),
                ])
            ], 'price')
            ->orderByDesc('cards_sum_price')
            ->pluck('cards_sum_price', 'name');

        return view('admin.reports.financial', compact(
            'income',
            'expenses',
            'netProfit',
            'topPos',
            'packageSales',
            'startDate',
            'endDate'
        ));
    }

    /**
     * تصدير التقرير المالي لـ Excel
     */
    public function exportFinancialReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));

        return Excel::download(
            new FinancialExport($startDate, $endDate),
            'financial-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * تقرير المستخدمين
     */
    public function usersReport(Request $request)
    {
        $role   = $request->input('role');
        $status = $request->input('status');

        $users = User::with('pointOfSale', 'roles')
            ->when($role, fn($q) => $q->role($role))
            ->when($status, fn($q) => $q->where('is_active', $status === 'active'))
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $rolesList = [
            'admin'      => 'المدير العام',
            'supervisor' => 'المشرف',
            'accountant' => 'المحاسب',
            'pos'        => 'نقطة البيع',
        ];

        $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط'];

        return view('admin.reports.users', compact(
            'users',
            'role',
            'status',
            'rolesList',
            'statuses'
        ));
    }
}
