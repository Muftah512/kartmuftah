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

    return $user->id === $pointOfSale->accountant_id;

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('supervisor')) {
            // تحقق إذا كان المشرف مسؤول عن نقطة البيع هذه
            return $user->supervisedPoints->contains($pos->id);
        }

        if ($user->hasRole('accountant')) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, PointOfSale $pos)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('supervisor')) {
            // تحقق إذا كان المشرف مسؤول عن نقطة البيع هذه
            return $user->supervisedPoints->contains($pos->id);
        }

        return false;
    }

    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }

    public function recharge(User $user, PointOfSale $pos)
    {
        return $user->hasRole('accountant');
    }
}