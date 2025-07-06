<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'accountant' => redirect()->route('accountant.dashboard'),
            'pos' => redirect()->route('pos.dashboard'),
            default => abort(403),
        };
    }
}
