<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct(protected UserService $userServices) {}

    public function login(LoginRequest $request): AuthResource|JsonResponse
    {
        try {
            $validateData = $request->validated();

            if(!$token = JWTAuth::attempt($validateData)) {
                throw new InvalidCredentialsException();
            }

            return AuthResource::make(['token' => $token]);
        } catch (JWTException $error) {
            return response()->json($error, 500);
        }
    }

    public function register(RegisterRequest $request): AuthResource|JsonResponse
    {
        DB::beginTransaction();
        try {
            $validateData = $request->validated();

            if (!empty($validatedata['image_path'])) {
                $imageName = Str::uuid() . '.' . $request->file('image_path')->getClientOriginalExtension();

                $path = Storage::putFileAs('public/profiles', $request->file('image_path'), $imageName);

                $validateData['image_path'] = $path;
            } else {
                unset($validateData['image_path']);
            }

            $validateData['role'] = 'client';

            $user = $this->userServices->createUser($validateData);
            $token = JWTAuth::fromUser($user);

            DB::commit();
            return AuthResource::make([
                'token' => $token,
                'user' => $user
            ]);
        } catch (JWTException $error) {
            DB::rollback();
            return response()->json($error, 500);
        }
    }
}
