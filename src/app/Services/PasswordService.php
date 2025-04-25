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
        try {
            $status = Password::sendResetLink(
                ['email' => $data['email']]
            );
    
            if($status !== Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Email not sent'
                ], 400);
            }

            return $status;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function resetPassword($data)
    {
        try {
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
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
