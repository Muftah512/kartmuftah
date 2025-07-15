<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PointOfSale;
use Illuminate\Auth\Access\HandlesAuthorization;

class PointOfSalePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || 
               $user->hasRole('supervisor') || 
               $user->hasRole('accountant');
    }

public function view(User $user, PointOfSale $pos)
{
    if ($user->hasRole('admin')) {
        return true;
    }

    if ($user->hasRole('accountant')) {
        return $user->id === $pos->accountant_id;
    }

    return false;
}

    public function create(User $user)
    {
        return $user->hasRole('admin');

        return $user->id === $pos->accountant_id;

    }
    public function update(User $user, PointOfSale $pos)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

            return $user->hasRole('accountant') && $user->id === $pos->accountant_id;
    }
        return false;
    }

    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }

    public function recharge(User $user, PointOfSale $pos)
    {
    return $user->hasRole('accountant') && $user->id === $pos->accountant_id;
 }
}