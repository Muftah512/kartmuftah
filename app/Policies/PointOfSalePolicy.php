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
        return $user->hasRole('ÇáãÏíÑ ÇáÚÇã') || 
               $user->hasRole('ÇáãÔÑÝ') || 
               $user->hasRole('ÇáãÍÇÓÈ');
    }

    public function view(User $user, PointOfSale $pos)
    {
        if ($user->hasRole('ÇáãÏíÑ ÇáÚÇã')) {
            return true;
        }

        if ($user->hasRole('ÇáãÔÑÝ')) {
            return $user->supervisedPoints->contains($pos->id);
        }

        if ($user->hasRole('ÇáãÍÇÓÈ')) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasRole('ÇáãÏíÑ ÇáÚÇã');
    }

    public function update(User $user, PointOfSale $pos)
    {
        if ($user->hasRole('ÇáãÏíÑ ÇáÚÇã')) {
            return true;
        }

        if ($user->hasRole('ÇáãÔÑÝ')) {
            return $user->supervisedPoints->contains($pos->id);
        }

        return false;
    }

    public function delete(User $user)
    {
        return $user->hasRole('ÇáãÏíÑ ÇáÚÇã');
    }

    public function recharge(User $user, PointOfSale $pos)
    {
        return $user->hasRole('ÇáãÍÇÓÈ');
    }
}