<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct(protected UserService $userService) {}

    public function show(int $id)
    {
        $user = $this->userService->getUser($id, Auth::id());

        return response()->json($user, 200);
    }

    public function update(UpdateUserRequest $request, $id) 
    {
        $validateData = $request->validated();
        
        $user = $this->userService->updateUser($validateData, $id, Auth::id());
        
        return response()->json([
            'message' => 'Successfully updated!',
            'user' => $user
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->userService->deleteUser($id, Auth::id());

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);
    }
}
