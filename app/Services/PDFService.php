<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;

class PDFService
{
    public function generateInvoice(Invoice $invoice)
    {
        $invoice->load('pointOfSale', 'transactions');
        
        $pdf = PDF::loadView('pdf.invoice', [
            'invoice' => $invoice
        ]);
        
        return $pdf->download('invoice-'.$invoice->id.'.pdf');
    }
}