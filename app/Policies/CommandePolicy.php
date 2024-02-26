<?php

namespace App\Policies;

use App\Models\User;

class CommandePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // public function view(User $user)
    // {
    //     return $user->hasPermissionTo('view_commandes');
    // }

    // public function create(User $user)
    // {
    //     return $user->hasPermissionTo('create_commandes');
    // }

    // public function update(User $user)
    // {
    //     return $user->hasPermissionTo('update_commandes');
    // }

    // public function delete(User $user)
    // {
    //     return $user->hasPermissionTo('delete_commandes');
    // }
}
