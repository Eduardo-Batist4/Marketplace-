<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\CartService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct(protected UserService $userServices, protected CartService $cartService) {}

    public function login(LoginRequest $request) 
    {
        $validateData = $request->validated();

        $user = $this->userServices->findUserWithEmail($validateData['email']);

        if(!$user || ! Hash::check($validateData['password'], $user->password)) {
            return response()->json([
                'error' => 'Incorrect credentials!',
            ], 400);
        }

        $token = $user->createToken('token')->plainTextToken;
        
        return response()->json([
            'message' => 'Successfully login',
            'token' => $token
        ], 200);
    }

    public function register(RegisterRequest $request) 
    {
        $validateData = $request->validated();

        $user = $this->userServices->createUser($validateData);

        $this->cartService->createCart([ 'user_id' => $user->id ]);
        
        return response()->json([
            'message' => 'User successfully registered!',
            'user' => $user
        ], 201);
    }
}
