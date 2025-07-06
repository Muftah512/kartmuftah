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
    /**
     * Apply auth and admin role middleware.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of users with summary stats.
     */
    public function index()
    {
        $totalUsers      = User::count();
        $activeUsers     = User::where('is_active', true)->count();
        $adminUsers      = User::role('admin')->count();
        $accountantUsers = User::role('accountant')->count();
        $posUsers        = User::role('pos')->count();

        $users = User::paginate(15);
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
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles         = ['admin', 'accountant', 'pos'];
        $pointsOfSale  = PointOfSale::all();

        return view('admin.users.create', compact('roles', 'pointsOfSale'));
    }

    /**
     * Store a newly created user in storage.
     */
public function store(StoreUserRequest $request)
{
    // تجميع البيانات المسموح بها
    $data = $request->validated();

    dd($data['role'], $data);

    // تشفير كلمة المرور
    $data['password']  = Hash::make($data['password']);
    $data['is_active'] = true;   // أو احذف هذا السطر إذا وضعت الافتراضي في الموديل

    // إنشاء المستخدم
    $user = User::create($data);

    // تعيين الدور
    $user->assignRole($data['role']);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'تم إنشاء المستخدم بنجاح');
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles        = Role::pluck('name');
        $pointsOfSale = PointOfSale::all();

        return view('admin.users.edit', compact('user', 'roles', 'pointsOfSale'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'phone'            => $validated['phone'],
            'point_of_sale_id' => $validated['point_of_sale_id'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        if (!$user->hasRole($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم تعديل المستخدم بنجاح');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            abort(403, 'لا يمكنك حذف مستخدم برتبة مدراء النظام.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * Toggle the active status of the specified user.
     */
    public function toggleStatus(User $user)
    {
        if ($user->hasRole('admin')) {
            abort(403, 'لا يمكنك تغيير حالة مستخدم برتبة مدراء النظام.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $message = $user->is_active
            ? 'تم تفعيل المستخدم بنجاح'
            : 'تم إلغاء تفعيل المستخدم بنجاح';

        return back()->with('success', $message);
    }
}
