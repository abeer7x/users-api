<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

$users = User::with(['roles:id,name'])
    ->select('id', 'name', 'email', 'created_at')
    ->paginate(15);
        return $this->success(
          UserResource::collection($users));
    }


    /**
     * Show a single user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user) 
    {
                try {
         
            if (!$user) {
                return $this->error(['error' => 'User not found'], 404);
            }
        return $this->success(new UserResource($user));
        } catch (JWTException $e) {
            return $this->error(['error' => 'Failed to fetch user profile'], 500);
        }

    }

    /**
     * Update a user. Owner or admin should be allowed.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user) 
    {
        // Optionally enforce policy: $this->authorize('update', $user);

        $data = $request->validated();

         
        $user->fill($data);
        $user->save();

        return $this->success(new UserResource($user));
    }

    /**
     * Delete a user (admin or owner).
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user) 
    {
        $user->delete();
        return $this->success(['message' => 'User deleted successfully']);
    }

    public function makeAdmin(User $user)
{
    $this->authorize('assignAdmin', User::class);

    $user->syncRoles(['Admin']);

    return $this->success([
        'message' => 'User promoted to Admin',
        'user' =>new UserResource($user)
    ]);
}

}
