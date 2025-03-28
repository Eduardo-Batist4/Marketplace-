<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct(protected UserService $userServices) {}

    public function login(Request $request) 
    {
        $validateData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string' 
        ]);

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

    public function register(Request $request) 
    {
        /*
            Regex exige uma letra (Maiuscula, MInuscula) e um numero
        */
        $validateData = $request->validate([
            'name' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/' 
        ]);

        $user = $this->userServices->createUser($validateData);

        return response()->json([
            'message' => 'User successfully registered!',
            'user' => $user
        ], 201);
    }
}
