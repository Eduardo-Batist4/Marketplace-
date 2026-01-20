<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordService
{
    public function forgotPassword($data): bool
    {
        return Password::sendResetLink(
            ['email' => $data['email']]
        ) === Password::RESET_LINK_SENT;
    }

    public function resetPassword($data): bool
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->update([
                    'password' => Hash::make($password)
                ]);

                event(new PasswordReset($user));
            }
        );

        return $status;
    }
}
