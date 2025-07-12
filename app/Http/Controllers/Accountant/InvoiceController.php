<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('pointOfSale')
            ->latest()
            ->paginate(10);
            
        return view('accountant.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('pointOfSale', 'transactions');
        return view('accountant.invoices.show', compact('invoice'));
    }
    
    public function download(Invoice $invoice)
    {
        // توليد ملف PDF للفاتورة
        return $this->pdfService->generateInvoice($invoice);
    }
}