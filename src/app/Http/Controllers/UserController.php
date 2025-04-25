<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPassword;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Jobs\SendPasswordResetEmail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function __construct(protected UserService $userService) {}

    public function show(int $id)
    {
        $user = $this->userService->getUser($id, JWTAuth::user()->id);

        return response()->json($user, 200);
    }

    public function update(UpdateUserRequest $request, $id) 
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
        $request->validated();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status !== Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Email not sent'
            ], 400);
        }

        return response()->json([
            'message' => 'Successfully send email!'
        ], 200);
    }

    public function resetPassword(ResetPassword $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );

        return response()->json([
            'message' => 'Successfully password changed',
            'status' => $status
        ], 200);
    }
}
