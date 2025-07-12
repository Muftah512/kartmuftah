<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InternetCard;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any Internet cards.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') \
            || $user->hasRole('accountant') \
            || $user->hasRole('pos');
    }

    /**
     * Determine whether the user can view the Internet card.
     */
    public function view(User $user, InternetCard $card): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('accountant')) {
            return true;
        }
        if ($user->hasRole('pos')) {
            return $card->pos_id === $user->point_of_sale_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create Internet cards.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('pos');
    }

    /**
     * Determine whether the user can update the Internet card.
     */
    public function update(User $user, InternetCard $card): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the Internet card.
     */
    public function delete(User $user, InternetCard $card): bool
    {
        return $user->hasRole('admin');
    }
}
