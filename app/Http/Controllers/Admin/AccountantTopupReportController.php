<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class AccountantTopupReportController extends Controller
{
    /** معاملات الشحن (إضافة رصيد) = credit */
    private array $TOPUP_TYPES = ['credit'];

    public function index(Request $request)
    {
        $dateFrom = Carbon::parse($request->input('date_from', now()->startOfMonth()->toDateString()))->startOfDay();
        $dateTo   = Carbon::parse($request->input('date_to',   now()->endOfDay()->toDateString()))->endOfDay();
        $accId    = $request->input('accountant_id');

        $accountants = User::role('accountant')->select('id','name','email')->orderBy('name')->get();

        // إجمالي نقاط البيع لكل محاسب (مدى الحياة)
        $posTotalAgg = DB::table('point_of_sales')
            ->select('accountant_id', DB::raw('COUNT(*) as pos_total'))
            ->groupBy('accountant_id')
            ->get()->keyBy('accountant_id');

        // نقاط البيع المُنشأة داخل الفترة
        $posInRangeAgg = DB::table('point_of_sales')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('accountant_id', DB::raw('COUNT(*) as pos_created_in_range'))
            ->groupBy('accountant_id')
            ->get()->keyBy('accountant_id');

        // إجمالي الشحن (credit) لكل محاسب داخل الفترة
        $topupAgg = DB::table('transactions as t')
            ->join('point_of_sales as pos', 'pos.id', '=', 't.pos_id')
            ->whereIn('t.type', $this->TOPUP_TYPES)  // credit
            ->whereBetween('t.created_at', [$dateFrom, $dateTo])
            ->groupBy('pos.accountant_id')
            ->select([
                'pos.accountant_id',
                DB::raw('COUNT(t.id) as topup_ops'),
                DB::raw('COALESCE(SUM(t.amount),0) as topup_total'),
            ])
            ->get()->keyBy('accountant_id');

        // بناء ملخص لكل محاسب
        $summaryRows = [];
        foreach ($accountants as $a) {
            $summaryRows[] = [
                'accountant_id'        => $a->id,
                'accountant_name'      => $a->name,
                'accountant_email'     => $a->email,
                'pos_total'            => $posTotalAgg[$a->id]->pos_total ?? 0,
                'pos_created_in_range' => $posInRangeAgg[$a->id]->pos_created_in_range ?? 0,
                'topup_ops'            => $topupAgg[$a->id]->topup_ops ?? 0,
                'topup_total'          => $topupAgg[$a->id]->topup_total ?? 0,
            ];
        }

        // تفصيل نقاط البيع للمحاسب المحدد
        $perPos = collect();
        $itemsByPos = collect();

        if ($accId) {
            $perPos = DB::table('point_of_sales as pos')
                ->leftJoin('transactions as t', function($join) use ($dateFrom, $dateTo) {
                    $join->on('pos.id', '=', 't.pos_id')
                         ->whereBetween('t.created_at', [$dateFrom, $dateTo]);
                })
                ->where('pos.accountant_id', $accId)
                ->where(function($q){
                    $q->whereIn('t.type', ['credit'])->orWhereNull('t.id');
                })
                ->groupBy('pos.id','pos.name','pos.email','pos.phone','pos.created_at')
                ->select([
                    'pos.id','pos.name','pos.email','pos.phone','pos.created_at',
                    DB::raw('COUNT(t.id) as topup_ops'),
                    DB::raw('COALESCE(SUM(CASE WHEN t.type = "credit" THEN t.amount ELSE 0 END),0) as topup_total'),
                    DB::raw('MAX(CASE WHEN t.type = "credit" THEN t.created_at ELSE NULL END) as last_topup_at'),
                ])
                ->orderByDesc('topup_total')
                ->get();

            $posIds = $perPos->pluck('id')->all();
            if ($posIds) {
                $itemsByPos = DB::table('transactions')
                    ->whereIn('pos_id', $posIds)
                    ->whereIn('type', $this->TOPUP_TYPES) // credit
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->orderByDesc('created_at')
                    ->get()
                    ->groupBy('pos_id');
            }
        }

        return view('admin.accountants.topups.index', [
            'accountants' => $accountants,
            'filters'     => [
                'date_from'     => $dateFrom->toDateString(),
                'date_to'       => $dateTo->toDateString(),
                'accountant_id' => $accId,
            ],
            'summaryRows' => $summaryRows,
            'perPos'      => $perPos,
            'itemsByPos'  => $itemsByPos,
        ]);
    }

    public function export(Request $request)
    {
        $response = $this->index($request);
        $data = $response->getData();

        $filename = 'accountant_topups_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');

            // ملخص لكل محاسب
            fputcsv($out, ['--- Summary per accountant ---']);
            fputcsv($out, ['Accountant ID','Name','Email','POS Total','POS Created In Range','Topup Ops (credit)','Topup Total (credit)']);
            foreach ($data->summaryRows as $r) {
                fputcsv($out, [
                    $r['accountant_id'], $r['accountant_name'], $r['accountant_email'],
                    $r['pos_total'], $r['pos_created_in_range'], $r['topup_ops'], $r['topup_total'],
                ]);
            }

            // تفصيل نقاط البيع للمحاسب المختار
            if (!empty($data->perPos)) {
                fputcsv($out, []);
                fputcsv($out, ['--- POS detail for selected accountant ---']);
                fputcsv($out, ['POS ID','POS Name','Email','Phone','Created At','Topup Ops (credit)','Topup Total (credit)','Last Topup']);
                foreach ($data->perPos as $p) {
                    fputcsv($out, [
                        $p->id, $p->name, $p->email, $p->phone, $p->created_at,
                        $p->topup_ops, $p->topup_total, $p->last_topup_at,
                    ]);

                    if (!empty($data->itemsByPos[$p->id])) {
                        foreach ($data->itemsByPos[$p->id] as $it) {
                            fputcsv($out, ['', '', '', '', '', 'ITEM', $it->amount, $it->created_at]);
                        }
                    }
                }
            }
            fclose($out);
        }, $filename, $headers);
    }
}
