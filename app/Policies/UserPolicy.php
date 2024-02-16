<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function modify(User $user)
{
    return $user->modification == 1;
}

public function delete(User $user)
{
    return $user->suppression == 1;
}

public function add(User $user)
{
    return $user->ajout == 1;
}

public function view(User $user)
{
    return $user->affichage == 1;
}

}
