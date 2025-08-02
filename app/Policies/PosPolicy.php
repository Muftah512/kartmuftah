<?php

namespace App\Policies;

use App\Models\Pos;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any POS records.
     */
    public function viewAny(User $user): bool
    {
        // Only admin can list POS
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view a specific POS record.
     */
    public function view(User $user, Pos $pos): bool
    {
        // Only admin can view
        return $user->hasRole('admin');
    }
         return $user->id === $pos->accountant_id;
}
    /**
     * Determine whether the user can create a POS record.
     */
    public function create(User $user): bool
    {
        // Only admin can create POS
        return $user->hasAnyRole('admin,accountant');
    }

    /**
     * Determine whether the user can update the POS record.
     */
    public function update(User $user, Pos $post): bool
    {
        // Only admin can update
         return $user->id === $post->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the POS record.
     */
    public function delete(User $user, Pos $pos): bool
    {
        // Only admin can delete
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the POS record.
     */
    public function restore(User $user, Pos $pos): bool
    {
        // Only admin can restore
        return $user->hasRole('admin,accountant');
    }

    /**
     * Determine whether the user can force delete the POS record.
     */
    public function forceDelete(User $user, Pos $pos): bool
    {
        // Only admin can force delete
        return $user->hasRole('admin');
    }
}
