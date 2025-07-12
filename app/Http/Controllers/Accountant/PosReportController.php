<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\PointOfSale;
use Illuminate\Http\Request;

class PosReportController extends Controller
{
    public function index(Request $request)
    {
        // تأكد من الصلاحية
        $this->authorize('view pos reports');

        // جلب نقاط البيع، هنا مثال بسيط
        $poses = PointOfSale::withCount('transactions')
                   ->paginate(15);

        return view('accountant.pos.reports', compact('poses'));
    }
}
