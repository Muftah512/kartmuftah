<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accountant\InvoiceRequest;
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
    public function create()
    {
        $points = PointOfSale::where('accountant_id', auth()->id())->get();
        return view('accountant.invoices.create', compact('points'));
    }

    /**
     * Store a newly created invoice.
     */
public function store(Request $request)
    {

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
