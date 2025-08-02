<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Models\PointOfSale;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * عرض قائمة المستخدمين وإحصائيات سريعة
     */
    public function index()
    {
        $totalUsers      = User::count();
        $activeUsers     = User::where('is_active', true)->count();
        $adminUsers      = User::role('admin')->count();
        $accountantUsers = User::role('accountant')->count();
        $posUsers        = User::role('pos')->count();

        $users = User::with('pointOfSale')->paginate(20);
        $roles = Role::pluck('name');

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'adminUsers',
            'accountantUsers',
            'posUsers',
            'roles'
        ));
    }

    /**
     * نموذج إنشاء مستخدم جديد
     */
    public function create()
    {
        $roles        = ['admin', 'accountant', 'pos'];
        $pointsOfSale = PointOfSale::all();

        return view('admin.users.create', compact('roles', 'pointsOfSale'));
    }

    /**
     * حفظ مستخدم جديد
     */
public function store(StoreUserRequest $request)
{
    $validated = $request->validated();

    $validated['password']  = Hash::make($validated['password']);
    $validated['is_active'] = true;

    $user = User::create($validated);

    // فقط عيّن الدور باستخدام Spatie
    $user->assignRole($validated['role']);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'تم إنشاء المستخدم بنجاح');
}

    /**
     * نموذج تعديل مستخدم
     */
    public function edit(User $user)
    {
        $roles        = Role::pluck('name');
        $pointsOfSale = PointOfSale::all();

        return view('admin.users.edit', compact('user', 'roles', 'pointsOfSale'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // تحديث الحقول العامة
        $user->name             = $data['name'];
        $user->email            = $data['email'];
        $user->point_of_sale_id = $data['point_of_sale_id'] ?? null;
        $user->is_active        = $data['is_active'] ?? $user->is_active;

        // إذا تم تمرير كلمة مرور جديدة، فنحن نشفرها
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // تحديث الدور إذا تغيّر
        if (! $user->hasRole($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم تعديل بيانات المستخدم بنجاح');
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            abort(403, 'لا يمكنك حذف مستخدم برتبة "admin".');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * تبديل حالة التفعيل (نشط / معطل)
     */
    public function toggleStatus(User $user)
    {
        if ($user->hasRole('admin')) {
            abort(403, 'لا يمكنك تغيير حالة مستخدم برتبة "admin".');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $message = $user->is_active
            ? 'تم تفعيل المستخدم بنجاح'
            : 'تم إلغاء تفعيل المستخدم بنجاح';

        return back()->with('success', $message);
    }
}
