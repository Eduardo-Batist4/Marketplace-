<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPassword;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\PasswordService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService,
        protected PasswordService $passwordService
    ) {}

    public function me(): JsonResponse
    {
        $id = JWTAuth::user()->id;
        $user = $this->userService->getUser($id);

        return response()->json($user, 200);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getAllUsers($id);

        return response()->json($user, 200);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $validateData = $request->validated();
        $user = $this->userService->updateUser($validateData, JWTAuth::user()->id);

        return response()->json($user, 200);
    }

    public function updateImage(UpdateUserImageRequest $request): Response
    {
        $image = $request->file('image_path');
        $this->userService->updateUserImage($image, JWTAuth::user()->id);

        return response()->noContent();
    }

    public function destroy(): Response
    {
        $id = JWTAuth::user()->id;
        $this->userService->deleteUser($id);

        return response()->noContent();
    }

    public function forgotPassword(ForgotPassword $request): Response
    {
        $validateData = $request->validated();

        $this->passwordService->forgotPassword($validateData);

        return response()->noContent();
    }

    public function resetPassword(ResetPassword $request): Response
    {
        $validateData = $request->validated();
        $this->passwordService->resetPassword($validateData);

        return response()->noContent();
    }
}
