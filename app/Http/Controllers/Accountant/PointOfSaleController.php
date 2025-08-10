<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accountant\StorePointOfSaleRequest;
use App\Models\PointOfSale;
use App\Mail\PosCredentialsMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        
        // إنشاء نقطة البيع أولاً
        $pointOfSale = PointOfSale::create($data);
        
        // إنشاء المستخدم مع ربطه بنقطة البيع
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pos',
            'point_of_sale_id'  => $pointOfSale->id, // استخدام ID نقطة البيع
            'phone' => $data['phone'],
        ]);
        
        // تحديث نقطة البيع بربطها بالمستخدم
        $pointOfSale->update(['user_id' => $user->id]);
        $user->assignRole('pos');
        
        // إرسال البريد الإلكتروني باستخدام بيانات نقطة البيع
        try {
            Mail::to($pointOfSale->email)->send(new PosCredentialsMail([
                'title' => 'بيانات الدخول لنقطة البيع',
                'name' => $pointOfSale->name,
                'email' => $pointOfSale->email,
                'password' => $data['password'],
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
        
        // تحديث كلمة مرور المستخدم المرتبط
        $pointOfSale->user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
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