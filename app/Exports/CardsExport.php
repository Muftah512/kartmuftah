<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Models\InternetCard;

class CardsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $posId;
    protected $packageId;
    protected $status;

    public function __construct($startDate, $endDate, $posId = null, $packageId = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->posId = $posId;
        $this->packageId = $packageId;
        $this->status = $status;
    }

    public function collection()
    {
        return InternetCard::with(['package', 'pointOfSale'])
            ->when($this->posId, function ($query) {
                return $query->where('pos_id', $this->posId);
            })
            ->when($this->packageId, function ($query) {
                return $query->where('package_id', $this->packageId);
            })
            ->when($this->status, function ($query) {
                if ($this->status === 'active') {
                    return $query->where('expires_at', '>', now());
                } elseif ($this->status === 'expired') {
                    return $query->where('expires_at', '<=', now());
                }
                return $query;
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
            'الباقة',
            'الكود',
            'نقطة البيع',
            'السعر',
            'تاريخ الإنشاء',
            'تاريخ الانتهاء',
            'الحالة'
        ];
    }

    public function map($card): array
    {
        $status = $card->expires_at > now() ? 'نشطة' : 'منتهية';
        
        return [
            $card->id,
            $card->package->name ?? 'غير معروف',
            $card->code,
            $card->pointOfSale->name ?? 'غير معروف',
            $card->price,
            $card->created_at->format('Y-m-d H:i'),
            $card->expires_at->format('Y-m-d H:i'),
            $status
        ];
    }
}