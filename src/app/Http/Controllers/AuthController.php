<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RefreshTokenResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(protected UserService $userServices, protected AuthService $authService) {}

    public function login(LoginRequest $request): RefreshTokenResource
    {
        $validatedData = $request->validated();
        $token = $this->authService->login($validatedData);

        return RefreshTokenResource::make($token);
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['image_path'] = $request->file('image_path');

        $result = $this->authService->register($validatedData);

        return response()->json([
            'user' => UserResource::make($result['user']),
            'access_token' => $result['token'],
            'refresh_token' => $result['refresh_token'],
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], 201);
    }

    public function updateAccessToken(RefreshTokenRequest $request): RefreshTokenResource
    {
        $validatedData = $request->validated();
        $token = $this->authService->updateAccessToken($validatedData);

        return RefreshTokenResource::make($token);
    }
}
