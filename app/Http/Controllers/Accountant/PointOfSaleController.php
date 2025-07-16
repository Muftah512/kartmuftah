<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accountant\StorePointOfSaleRequest;
use App\Models\PointOfSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PosCredentialsMail; // Added missing import

class PointOfSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:accountant']);
    }

    public function index()
    {
$points = PointOfSale::select('id', 'name', 'balance')
    ->where('accountant_id', Auth::id())
    ->get();

        $points = PointOfSale::where('accountant_id', Auth::id())
            ->with('user') // Eager load user relationship
            ->latest() // Better readability than orderBy
            ->paginate(10);

        return view('accountant.pos.index', compact('points'));
    }

    public function create()
    {
        return view('accountant.pos.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name'                  => 'required|string|max:255',
        'email'                 => 'required|email|unique:point_of_sales,email',
        'password'              => 'required|string|min:6|confirmed',
        'location'              => 'required|string|max:255',
        'phone'                 => 'required|string|max:255',
        'is_active'             => 'sometimes|boolean',
    ]);

    // تأكد أن المستخدم الحالي محاسب
    if (auth()->user()->hasRole('accountant')) {
        $validated['accountant_id'] = auth()->id();
    } else {
        // اختياري: في حالة دخول من ليس محاسباً
        return back()->withErrors('غير مسموح.');
    }

    // إضافة نقطة البيع
    $pos = PointOfSale::create([
        'name'          => $validated['name'],
        'email'         => $validated['email'],
        'password'      => bcrypt($validated['password']),
        'location'      => $validated['location'],
        'phone'         => $validated['phone'],
        'is_active'     => $request->has('is_active'),
        'accountant_id' => $validated['accountant_id'],
    ]);

    // يمكنك إضافة تعيين صلاحية "pos" للمستخدم إذا كان هناك جدول مستخدمين منفصل
    // أو أي عمليات أخرى تريدها

    return redirect()->route('accountant.pos.index')
                     ->with('success', 'تمت إضافة نقطة البيع بنجاح وربطها بك!');
}

    public function show(PointOfSale $pointOfSale)
    {
        $this->authorize('view', $pointOfSale);
        return view('accountant.pos.show', compact('pointOfSale'));
    }

    public function resetPassword(Request $request, PointOfSale $pointOfSale)
    {
        $this->authorize('update', $pointOfSale);
        
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $password = $request->new_password;
        $pointOfSale->user->update([
            'password' => Hash::make($password),
        ]);

        $mailSent = $this->sendCredentialsEmail(
            $pointOfSale, 
            $password,
            'كلمة المرور الجديدة لنقطة البيع',
            true
        );

        return back()
            ->with('success', 'تم إعادة تعيين كلمة المرور بنجاح')
            ->with('mail_status', $mailSent ? 'sent' : 'failed');
    }

    /**
     * Send POS credentials email with common template
     */
    private function sendCredentialsEmail(
        PointOfSale $pos,
        string $password,
        string $subject,
        bool $isReset
    ): bool {
        try {
            Mail::to($pos->email)->send(new PosCredentialsMail([
                'title'           => $subject,
                'name'            => $pos->name,
                'email'           => $pos->email,
                'password'        => $password,
                'login_url'       => route('login'),
                'accountant_name' => Auth::user()->name,
                'is_reset'        => $isReset,
            ]));
            return true;
        } catch (\Exception $e) {
            \Log::error('POS email failed: '.$e->getMessage());
            return false;
        }
    }
}