<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function update(Request $request, int $id): UserResource
    {
        $validateData = $request->validate([
            'role' => 'required|in:moderator,admin'
        ]);

        $user = $this->userService->updateUser($validateData, $id);

        return UserResource::make($user);
    }
}
