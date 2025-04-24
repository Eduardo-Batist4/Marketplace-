<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\CartService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct(protected UserService $userServices, protected CartService $cartService) {}

    public function login(LoginRequest $request) 
    {
        try {
            $validateData = $request->validated();
    
            if(!$token = JWTAuth::attempt($validateData)) {
                return response()->json([
                    'error' => 'Incorrect credentials!',
                ], 400);
            }
    
            return response()->json([
                'message' => 'Successfully login',
                'token' => $token
            ], 200);
        } catch (JWTException $error) {
            return response()->json([
                'error' => 'Could not create token',
                $error
            ], 500);
        }
    }

    public function register(RegisterRequest $request) 
    {
        DB::beginTransaction();
        try {
            $validateData = $request->validated();
    
            $imageName = Str::uuid() . '.' . $request->file('image_path')->getClientOriginalExtension();
    
            $path = Storage::putFileAs('public/profiles', $request->file('image_path'), $imageName);
    
            $validateData['image_path'] = $path;

            $user = $this->userServices->createUser($validateData);
    
            $this->cartService->createCart([ 'user_id' => $user->id ]);
            DB::commit();
            return response()->json([
                'message' => 'User successfully registered!',
                'user' => $user
            ], 201);
        } catch (JWTException $error) {
            DB::rollback();
            return response()->json([
                'error' => 'Could not create token',
                $error
            ], 500);
        }
    }
}
