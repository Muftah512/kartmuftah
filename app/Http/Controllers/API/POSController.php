<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\InternetCard;
use App\Models\Package;

class POSController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'pos') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return response()->json(['token' => $user->createToken("pos-app")->plainTextToken]);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function generateCard(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::find($request->package_id);
        $username = rand(1000000, 9999999999);

        // send to MikroTik API (assume handled in a service class)

        InternetCard::create([
            'username' => $username,
            'package_id' => $package->id,
            'created_by' => auth()->id()
        ]);

        return response()->json(['username' => $username, 'package' => $package->name]);
    }

    public function rechargeCard(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::find($request->package_id);
        $username = $request->username;

        // check + send to MikroTik recharge logic

        return response()->json(['message' => 'Card recharged', 'username' => $username]);
    }

    public function salesReport()
    {
        $cards = InternetCard::where('created_by', auth()->id())->get();
        return response()->json($cards);
    }
}
