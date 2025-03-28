<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct(protected UserService $userService) {}

    public function show(int $id)
    {
        $user = $this->userService->getUser($id, Auth::id());

        return response()->json($user, 200);
    }

    public function update(Request $request, $id) 
    {
        $validateData = $request->validate([
            'name' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        $user = $this->userService->updateUser($validateData, $id, Auth::id());

        return response()->json([
            'message' => 'User successfully updated!',
            'user' => $user
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->userService->deleteUser($id, Auth::id());

        return response()->json([
            'message' => 'User successfully deleted!',
        ], 200);
    }
}
