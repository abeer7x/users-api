<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{

    use AuthorizesRequests;
    protected UserService $userService;

    /**
     * Inject UserService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * List users
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = $this->userService->getUsers();
        return $this->success(UserResource::collection($users));
    }

    /**
     * Show user by model binding
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user = $this->userService->getUser($user);
        return $this->success(new UserResource($user));
    }

    /**
     * Update user
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user = $this->userService->updateUser($user, $request->validated());
        return $this->success(new UserResource($user));
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->userService->deleteUser($user);
        return $this->success(['message' => 'User deleted successfully']);
    }

    /**
     * Assign Admin role
     */
    public function makeAdmin(User $user)
    {
        $this->authorize('assignAdmin', User::class);

        $user = $this->userService->assignAdmin($user);

        return $this->success([
            'message' => 'User promoted to Admin',
            'user' => new UserResource($user),
        ]);
    }
}
