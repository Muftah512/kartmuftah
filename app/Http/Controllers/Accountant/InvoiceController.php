<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PointOfSale;
use App\Models\Transaction;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::where('accountant_id', auth()->id())
                           ->with('pointOfSale')
                           ->latest()
                           ->paginate(10);

        return view('accountant.invoices.index', compact('invoices'));
    } // <-- كان ناقصاً هذا القوس

    /**
     * Show the form for creating a new invoice.
     */
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
        // كود تخزين الفاتورة هنا
        $validated = $request->validate([
            // قواعد التحقق
        ]);

        // ... عملية إنشاء الفاتورة

        return redirect()->route('accountant.invoices.index')
                         ->with('success', 'تم إنشاء الفاتورة بنجاح');
    } // <-- كان ناقصاً هذا القوس

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('pointOfSale', 'transactions');
        return view('accountant.invoices.show', compact('invoice'));
    } // <-- كان ناقصاً هذا القوس

    // يمكنك إضافة المزيد من الدوال هنا (edit, update, destroy)
}