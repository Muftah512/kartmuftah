<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\PointOfSale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Accountant\StorePointOfSaleRequest;
use Spatie\Permission\Models\Role;

class PointOfSaleController extends Controller
{
    public function index()
    {
        $points = PointOfSale::with('users')->paginate(10);
        return view('accountant.pos.index', compact('points'));
    }

    public function create()
    {
        $supervisors = User::role('ÇáãÔÑÝ')->get();
        return view('accountant.pos.create', compact('supervisors'));
    }

    public function store(StorePointOfSaleRequest $request)
    {
        $validated = $request->validated();
        
        // ÅäÔÇÁ äÞØÉ ÇáÈíÚ
        $pos = PointOfSale::create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'supervisor_id' => $validated['supervisor_id'],
            'balance' => 0,
            'is_active' => true
        ]);

        // ÅäÔÇÁ ãÓÊÎÏã áäÞØÉ ÇáÈíÚ
        $user = User::create([
            'name' => $validated['pos_user_name'],
            'email' => $validated['pos_user_email'],
            'password' => bcrypt($validated['pos_user_password']),
            'point_of_sale_id' => $pos->id
        ]);

        // ÊÚííä ÏæÑ "äÞØÉ ÇáÈíÚ"
        $user->assignRole('äÞØÉ ÇáÈíÚ');

        return redirect()->route('accountant.pos.index')->with('success', 'Êã ÅäÔÇÁ äÞØÉ ÇáÈíÚ ÈäÌÇÍ');
    }

    public function show(PointOfSale $pos)
    {
        $transactions = $pos->transactions()->latest()->paginate(10);
        return view('accountant.pos.show', compact('pos', 'transactions'));
    }
}