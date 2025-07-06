<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PointOfSale;
use App\Models\InternetCard;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * لوحة التحكم
     */
    public function dashboard()
    {
        // إحصائيات سريعة
        $stats = [
            'total_users'   => User::count(),
            'total_pos'     => PointOfSale::count(),
            'active_pos'    => PointOfSale::where('is_active', true)->count(),
            'total_cards'   => InternetCard::count(),
            'revenue'       => Transaction::sum('amount') ?? 0,
            'recent_transactions' => Transaction::with(['pointOfSale', 'user'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        // الإيرادات الشهرية للرسم البياني
        $revenueByMonth = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'revenueByMonth'));
    }

    /**
     * قائمة المحاسبين ونقاط البيع
     */
    public function manageUsers()
    {
        $users = User::whereIn('role', ['accountant', 'pos'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض نموذج الإنشاء
     */
    public function createUser()
    {
        $pointsOfSale = PointOfSale::where('is_active', true)->get();
        return view('admin.users.create', compact('pointsOfSale'));
    }

    /**
     * حفظ المستخدم الجديد
     */
    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'role'                  => 'required|in:accountant,pos',
            'pos_id'                => 'nullable|exists:point_of_sales,id',
        ]);

        $userData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
            'role'      => $data['role'],
            'is_active' => true,
        ];

        // ربط المستخدم بنقطة البيع إذا كان دور نقطة بيع
        if ($data['role'] === 'pos' && !empty($data['pos_id'])) {
            $userData['pos_id'] = $data['pos_id'];
        }

        User::create($userData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم إنشاء الحساب بنجاح');
    }
    
    /**
     * تفعيل/تعطيل المستخدم
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'تم تفعيل' : 'تم تعطيل';
        return back()->with('success', $status . ' المستخدم بنجاح');
    }
    
    /**
     * تحديث معلومات المستخدم
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'pos_id'   => 'nullable|exists:point_of_sales,id',
        ]);

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        if ($user->role === 'pos' && !empty($data['pos_id'])) {
            $updateData['pos_id'] = $data['pos_id'];
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم تحديث معلومات المستخدم بنجاح');
    }
    
    /**
     * إدارة نقاط البيع
     */
    public function managePointsOfSale()
    {
        $points = PointOfSale::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.pos.index', compact('points'));
    }
    
    /**
     * إدارة التقارير
     */
    public function reports()
    {
        $transactions = Transaction::with(['pointOfSale', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);
            
        $revenueByMonth = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COALESCE(SUM(amount), 0) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        return view('admin.reports', compact('transactions', 'revenueByMonth'));
    }
}