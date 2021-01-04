<?php

namespace App\Policies;

use App\User;
use App\Buyer;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;


class BuyerPolicy
{

    use HandlesAuthorization, AdminActions;


    public function view(User $user, Buyer $buyer)
    {
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can purchase something.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function purchase(User $user, Buyer $buyer)
    {
        return $user->id === $buyer->id;
    }


}
