<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordService
{
    public function forgotPassword($data)
    {
        $status = Password::sendResetLink(
            ['email' => $data['email']]
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Email not sent'
            ], 400);
        }

        return $status;
    }

    public function resetPassword($data)
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status;
    }
}
