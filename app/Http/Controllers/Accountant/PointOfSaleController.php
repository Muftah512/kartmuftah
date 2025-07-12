<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accountant\StorePointOfSaleRequest;
use App\Models\PointOfSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointOfSaleController extends Controller
{
    public function index()
    {
        $points = PointOfSale::where('accountant_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('accountant.pos.index', compact('points'));
    }

    public function create()
    {
        return view('accountant.pos.create');
    }

    public function store(StorePointOfSaleRequest $request)
    {
        $data = $request->validated();
        $data['accountant_id'] = Auth::id();
        $data['balance'] = 0;
        
        // إنشاء مستخدم جديد لنقطة البيع باستخدام كلمة المرور المدخلة
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // استخدام كلمة المرور المدخلة
            'role' => 'pos',
            'phone' => $data['phone'],
        ]);
        
        // ربط المستخدم بنقطة البيع
        $data['user_id'] = $user->id;
        
        // إنشاء نقطة البيع
        $pointOfSale = PointOfSale::create($data);
        
        // إرسال بيانات الدخول عبر البريد الإلكتروني
        try {
            Mail::to($pointOfSale->email)->send(new PosCredentialsMail([
                'title' => 'بيانات الدخول لنقطة البيع',
                'name' => $pointOfSale->name,
                'email' => $pointOfSale->email,
                'password' => $data['password'], // إرسال كلمة المرور المدخلة
                'login_url' => route('login'),
                'accountant_name' => Auth::user()->name,
            ]));
            
            $mailSent = true;
        } catch (\Exception $e) {
            $mailSent = false;
            \Log::error('Failed to send POS credentials email: ' . $e->getMessage());
        }
        
        return redirect()->route('accountant.pos.index')
            ->with('success', 'تم إنشاء نقطة البيع بنجاح')
            ->with('mail_sent', $mailSent);
    }

     public function resetPassword(Request $request, PointOfSale $pointOfSale)
    {
        $this->authorize('update', $pointOfSale);
        
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        // تحديث كلمة مرور المستخدم
        $pointOfSale->user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        // إرسال كلمة المرور الجديدة
        try {
            Mail::to($pointOfSale->email)->send(new PosCredentialsMail([
                'title' => 'كلمة المرور الجديدة لنقطة البيع',
                'name' => $pointOfSale->name,
                'email' => $pointOfSale->email,
                'password' => $request->new_password,
                'login_url' => route('login'),
                'accountant_name' => Auth::user()->name,
                'is_reset' => true
            ]));
            
            $mailSent = true;
        } catch (\Exception $e) {
            $mailSent = false;
            \Log::error('Failed to send POS password reset email: ' . $e->getMessage());
        }
        
        return back()->with('success', 'تم إعادة تعيين كلمة المرور بنجاح')
            ->with('mail_sent', $mailSent);
    }

    public function show(PointOfSale $pointOfSale)
    {
        $this->authorize('view', $pointOfSale);
        
        return view('accountant.pos.show', compact('pointOfSale'));
    }
}