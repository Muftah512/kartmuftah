<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $posId;

    public function __construct($posId = null)
    {
        $this->posId = $posId;
    }

    public function collection()
    {
        $query = Transaction::with(['user', 'pointOfSale'])
            ->orderBy('created_at', 'desc');
        
        if ($this->posId) {
            $query->where('pos_id', $this->posId);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'äæÚ ÇáãÚÇãáÉ',
            'ÇáãÈáÛ',
            'äÞØÉ ÇáÈíÚ',
            'ÇáãÓÊÎÏã',
            'ÇáæÕÝ',
            'ÇáÊÇÑíÎ'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->type == 'debit' ? 'ÎÕã' : 'ÔÍä',
            number_format($transaction->amount, 2),
            $transaction->pointOfSale->name ?? '--',
            $transaction->user->name,
            $transaction->description,
            $transaction->created_at->format('Y-m-d H:i')
        ];
    }
}