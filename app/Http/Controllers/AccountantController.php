<?php

namespace App\Http\Controllers;

use App\Models\PointOfSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ActivityLogger;

class AccountantController extends Controller
{
    public function __construct()
    {
        $this->middleware('accountant');
    }

public function exportTransactionsExcel($posId = null)
{
    $filename = $posId 
        ? 'معاملات_نقطة_بيع_' . $posId . '_' . now()->format('Y_m_d') . '.xlsx'
        : 'جميع_المعاملات_' . now()->format('Y_m_d') . '.xlsx';
    
    return Excel::download(new TransactionsExport($posId), $filename);
}
public function exportPosReport()
{
    $pointsOfSale = PointOfSale::with('creator')->get();
    
    $pdf = PDF::loadView('accountant.reports.pos', [
        'pointsOfSale' => $pointsOfSale,
        'date' => now()->format('Y-m-d')
    ]);
    
    return $pdf->download('نقاط_البيع_' . now()->format('Y_m_d') . '.pdf');
}

public function exportTransactionsReport($posId = null)
{
    $query = Transaction::with(['user', 'pointOfSale'])
        ->orderBy('created_at', 'desc');
    
    if ($posId) {
        $query->where('pos_id', $posId);
    }
    
    $transactions = $query->get();
    
    $pdf = PDF::loadView('accountant.reports.transactions', [
        'transactions' => $transactions,
        'date' => now()->format('Y-m-d')
    ]);
    
    $filename = $posId 
        ? 'معاملات_نقطة_بيع_' . $posId . '_' . now()->format('Y_m_d') . '.pdf'
        : 'جميع_المعاملات_' . now()->format('Y_m_d') . '.pdf';
    
    return $pdf->download($filename);
}
class AccountantController extends Controller
{
    public function __construct()
    {
        $this->middleware('accountant');
    }

    // عرض قائمة نقاط البيع
    public function index()
    {
        $pointsOfSale = PointOfSale::with('creator')->get();
        return view('accountant.pos.index', compact('pointsOfSale'));
    }

    // عرض نموذج إنشاء نقطة بيع
    public function create()
    {
        return view('accountant.pos.create');
    }

    // حفظ نقطة البيع الجديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'initial_balance' => 'required|numeric|min:0',
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|unique:users,email',
        ]);

        // إنشاء نقطة البيع
        $pos = PointOfSale::create([
            'name' => $request->name,
            'location' => $request->location,
            'balance' => $request->initial_balance,
            'created_by' => auth()->id(),
        ]);

        // إنشاء مستخدم مدير لنقطة البيع
        $password = Str::random(10);
        $user = User::create([
            'name' => $request->manager_name,
            'email' => $request->manager_email,
            'password' => Hash::make($password),
            'role' => 'pos',
            'pos_id' => $pos->id,
        ]);

        return redirect()->route('accountant.pos.index')
            ->with('success', 'تم إنشاء نقطة البيع بنجاح');
    }
}
    // قائمة نقاط البيع
    public function listPos()
    {
        $posList = PointOfSale::with('creator')->get();
        return response()->json($posList);
    }
}