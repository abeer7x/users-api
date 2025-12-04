<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * Inject AuthService.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register new user and return JWT token.
     */
    public function register(RegisterUserRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return $this->success([
            'user' => new UserResource($result['user']),
            'access_token' => $result['token'],
        ], 201);
    }

    /**
     * Authenticate user and return JWT token.
     */
    public function login(LoginUserRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return $this->success([
            'user' => new UserResource($result['user']),
            'access_token' => $result['token'],
        ]);
    }

    /**
     * Get authenticated user profile.
     */
    public function me()
    {
        return $this->success(new UserResource($this->authService->me()));
    }

    /**
     * Refresh JWT token.
     */
    public function refresh()
    {
        $token = $this->authService->refresh();

        return $this->success([
            'access_token' => $token
        ]);
    }

    /**
     * Logout and invalidate current token.
     */
    public function logout()
    {
        $this->authService->logout();

        return $this->success([
            'message' => 'Logged out successfully'
        ]);
    }
}
