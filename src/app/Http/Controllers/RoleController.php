<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function update(Request $request, int $id)
    {
        $validateData = $request->validate([
            'role' => 'required|in:moderator,admin'
        ]);

        $user = $this->userService->updateUserAdmin($validateData, $id);

        return response()->json([
            'message' => 'User successfully updated!',
            'user' => $user
        ], 200);
    }
}
