<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Models\Transaction;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $posId;

    public function __construct($startDate, $endDate, $posId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->posId = $posId;
    }

    public function collection()
    {
        return Transaction::with('pointOfSale')
            ->where('type', 'debit')
            ->when($this->posId, function ($query) {
                return $query->where('pos_id', $this->posId);
            })
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'نقطة البيع',
            'المبلغ',
            'التاريخ',
            'الوصف'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->pointOfSale->name ?? 'غير معروف',
            $transaction->amount,
            $transaction->created_at->format('Y-m-d H:i'),
            $transaction->description
        ];
    }
}