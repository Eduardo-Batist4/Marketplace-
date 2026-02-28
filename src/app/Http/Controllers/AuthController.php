<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RefreshTokenResource;
use App\Services\AuthService;
use App\Services\UserService;

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

        return RefreshTokenResource::make($result);
    }

    public function updateAccessToken(RefreshTokenRequest $request): RefreshTokenResource
    {
        $validatedData = $request->validated();
        $token = $this->authService->updateAccessToken($validatedData);

        return RefreshTokenResource::make($token);
    }
}
