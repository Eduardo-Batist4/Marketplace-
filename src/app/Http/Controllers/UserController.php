<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPassword;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\PasswordService;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService,
        protected PasswordService $passwordService
    ) {}

    public function show(int $id)
    {
        $user = $this->userService->getUser($id, JWTAuth::user()->id);

        return response()->json($user, 200);
    }

    public function update(UpdateUserRequest $request, int $id) 
    {
        $validateData = $request->validated();
        
        $user = $this->userService->updateUser($validateData, $id, JWTAuth::user()->id);
        
        return response()->json([
            'message' => 'Successfully updated!',
            'user' => $user
        ], 200);
    }

    public function updateImage(UpdateUserImageRequest $request, int $id)
    {
        $request->validated();

        $this->userService->updateUserImage($request, $id, JWTAuth::user()->id);

        return response()->json([
            'message' => 'Successfully updated!',
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->userService->deleteUser($id, JWTAuth::user()->id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);
    }

    public function forgotPassword(ForgotPassword $request)
    {
        $validateData = $request->validated();

        $status = $this->passwordService->forgotPassword($validateData);

        return response()->json([
            'message' => 'Successfully send email!',
            'status' => $status
        ], 200);
    }

    public function resetPassword(ResetPassword $request)
    {
        $validateData = $request->validated();

        $status = $this->passwordService->resetPassword($validateData);

        return response()->json([
            'message' => 'Successfully password changed',
            'status' => $status
        ], 200);
    }
}
