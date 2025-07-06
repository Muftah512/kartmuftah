<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\PointOfSale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\Accountant\RechargeRequest;

class RechargeController extends Controller
{
    public function index()
    {
        $recharges = Transaction::where('type', 'credit')
            ->with('pointOfSale')
            ->latest()
            ->paginate(10);
            
        return view('accountant.recharges.index', compact('recharges'));
    }

    public function create()
    {
        $points = PointOfSale::where('is_active', true)->get();
        return view('accountant.recharges.create', compact('points'));
    }

    public function store(RechargeRequest $request)
    {
        $validated = $request->validated();
        
        $pos = PointOfSale::findOrFail($validated['pos_id']);
        
        // ÒíÇÏÉ ÇáÑÕíÏ
        $pos->balance += $validated['amount'];
        $pos->save();
        
        // ÅäÔÇÁ ÇáÝÇÊæÑÉ
        $invoice = $pos->invoices()->create([
            'amount' => $validated['amount'],
            'description' => 'ÔÍä ÑÕíÏ',
            'status' => 'paid'
        ]);
        
        // ÊÓÌíá ÇáãÚÇãáÉ
        Transaction::create([
            'point_of_sale_id' => $pos->id,
            'type' => 'credit',
            'amount' => $validated['amount'],
            'description' => 'ÔÍä ÑÕíÏ ÈæÇÓØÉ ÇáãÍÇÓÈ',
            'balance_after' => $pos->balance,
            'invoice_id' => $invoice->id
        ]);

        return redirect()->route('accountant.recharges.index')->with('success', 'Êã ÔÍä ÇáÑÕíÏ ÈäÌÇÍ');
    }
}