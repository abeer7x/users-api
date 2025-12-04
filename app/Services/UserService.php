<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Fetch paginated list of users with roles
     */
    public function getUsers(): LengthAwarePaginator
    {
        return User::with(['roles:id,name'])
            ->select('id', 'name', 'email', 'created_at')
            ->paginate(15);
    }

    /**
     * Fetch a single user instance
     *
     * @param User $user
     * @return User
     */
    public function getUser(User $user): User
    {
        return $user->load('roles:id,name');
    }

    /**
     * Update user record using validated data
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user->load('roles:id,name');
    }

    /**
     * Delete a user record
     *
     * @param User $user
     * @return bool|null
     */
    public function deleteUser(User $user): ?bool
    {
        return $user->delete();
    }

    /**
     * Promote user to Admin role
     *
     * @param User $user
     * @return User
     */
    public function assignAdmin(User $user): User
    {
        $user->syncRoles(['Admin']);
        return $user->load('roles:id,name');
    }
}
