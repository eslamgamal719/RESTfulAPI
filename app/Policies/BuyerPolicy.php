<?php

namespace App\Policies;

use App\Buyer;
use App\Traits\AdminActions;
use App\User;
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

    /**
     * Determine whether the user can update the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function update(User $user, Buyer $buyer)
    {
        //
    }

    /**
     * Determine whether the user can delete the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function delete(User $user, Buyer $buyer)
    {
        //
    }

    /**
     * Determine whether the user can restore the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function restore(User $user, Buyer $buyer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function forceDelete(User $user, Buyer $buyer)
    {
        //
    }
}
