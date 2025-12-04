<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * Inject repository.
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Register new user and generate token.
     */
    public function register(array $data)
    {
        $user = $this->userRepo->create($data);
        $this->userRepo->assignRole($user);

        $token = JWTAuth::fromUser($user);

        return compact('user', 'token');
    }

    /**
     * Login user using credentials.
     */
    public function login(array $data)
    {
        if (!$token = JWTAuth::attempt($data)) {
            throw new \Exception('Invalid credentials', 401);
        }

        return [
            'user' => auth('api')->user(),
            'token' => $token
        ];
    }

    /**
     * Get current authenticated user.
     */
    public function me()
    {
        return auth('api')->user();
    }

    /**
     * Refresh JWT token.
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();

        return JWTAuth::refresh($token);
    }

    /**
     * Invalidate current JWT token.
     */
    public function logout()
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
    }
}
