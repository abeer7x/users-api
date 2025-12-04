<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Create a new user record in the database.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Assign user role depending on total users count.
     * First registered user => Admin
     * Others => User
     *
     * @param User $user
     * @return void
     */
    public function assignRole(User $user): void
    {
        if (User::count() === 1) {
            $user->assignRole('Admin');
        } else {
            $user->assignRole('User');
        }
    }
   
}
