class CardPolicy
{
    public function view(User $user, Card $card)
    {
        if ($user->hasRole('ÇáãÏíÑ ÇáÚÇã')) return true;
        return $user->pointOfSale?->id === $card->pos_id;
    }

    public function recharge(User $user, Card $card)
    {
        return $user->can('recharge_existing_card') && 
               $user->pointOfSale?->id === $card->pos_id;
    }
}