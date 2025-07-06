<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\PointOfSale;
use App\Models\Package;

class FinancialExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return collect([$this->getFinancialData()]);
    }

    public function headings(): array
    {
        return [
            'ÇáÝÊÑÉ',
            'ÇáÅíÑÇÏÇÊ',
            'ÇáãÕÑæÝÇÊ',
            'ÕÇÝí ÇáÑÈÍ',
            'ÃÝÖá äÞØÉ ÈíÚ',
            'ÞíãÉ ãÈíÚÇÊåÇ',
            'ÃÝÖá ÈÇÞÉ',
            'ÞíãÉ ãÈíÚÇÊåÇ'
        ];
    }

    public function map($row): array
    {
        return [
            $row['period'],
            $row['income'],
            $row['expenses'],
            $row['net_profit'],
            $row['top_pos'],
            $row['top_pos_amount'],
            $row['top_package'],
            $row['top_package_amount']
        ];
    }

    private function getFinancialData()
    {
        $income = Transaction::where('type', 'credit')
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->sum('amount');
        
        $expenses = Transaction::where('type', 'debit')
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->sum('amount');
        
        $netProfit = $income - $expenses;
        
        // ÃÝÖá äÞØÉ ÈíÚ
        $topPos = PointOfSale::withSum(['transactions' => function($query) {
            $query->where('type', 'debit')
                ->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
        }], 'amount')
        ->orderBy('transactions_sum_amount', 'desc')
        ->first();
        
        // ÃÝÖá ÈÇÞÉ
        $topPackage = Package::withSum(['cards' => function($query) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }], 'price')
        ->orderBy('cards_sum_price', 'desc')
        ->first();

        return [
            'period' => "{$this->startDate} Åáì {$this->endDate}",
            'income' => $income,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'top_pos' => $topPos->name ?? 'áÇ íæÌÏ',
            'top_pos_amount' => $topPos->transactions_sum_amount ?? 0,
            'top_package' => $topPackage->name ?? 'áÇ íæÌÏ',
            'top_package_amount' => $topPackage->cards_sum_price ?? 0
        ];
    }
}