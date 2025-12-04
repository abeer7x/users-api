<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
      /**
     * Register a new user and return JWT token.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request){
        $user = User::create($request->only(['name','email','password']));

    if (User::count() === 1) {
        $user->assignRole('Admin');
    } else {
        $user->assignRole('User');
    }

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return $this->error(['error' => 'Could not create token'], 500);
        }
        return $this->success([
            'user' => new UserResource($user),
            'access_token' => $token
        ], 201);
    }
        /**
     * Authenticate user and return JWT token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
        public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return $this->error(['error' => 'Could not create token'], 500);
        }

           $user = auth('api')->user();


        return $this->success([
               'user' => new UserResource($user),
            'token' => $token ,
            
        ]);
    }

        /**
     * Return authenticated user data.
     *
     * @return JsonResponse
     */
    public function me() 
    {
        return $this->success(new UserResource(auth('api')->user()));
    }

    /**
     * Refresh JWT token.
     *
     * @return JsonResponse
     */
    public function refresh() 
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        return $this->success([
            'access_token' => $newToken,
            
        ]);
    }

    public function logout(Request $request)
{
    try {
 
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);

        return $this->success([
            'message' => 'Logged out successfully'
        ]);

    } catch (\Exception $e) {
        return $this->error([
            'error' => 'Something went wrong, token invalid or missing'
        ], 500);
    }
}


}
