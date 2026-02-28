<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\NotFoundException;
use App\Exceptions\TokenException;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

use function App\Helpers\uploadImage;

class AuthService
{
    public function __construct(protected UserService $userServices){}

    public function login(array $data): array
    {
        $token = JWTAuth::attempt($data);
        if(!$token) {
            throw new InvalidCredentialsException();
        }
        $user = JWTAuth::user();
        $refreshToken = $this->createRefreshToken($user);

        return [
            'access_token' => $token,
            'refresh_token' => (string) $refreshToken->jti,
            'expires_at' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    public function register($data): array
    {
        $data['image_path'] = uploadImage($data['image_path' ?? null], 'users');
        $data['role'] = 'client';

        DB::beginTransaction();
        try {
            $user = $this->userServices->createUser($data);
            $token = JWTAuth::fromUser($user);
            $refreshToken = $this->createRefreshToken($user);

            DB::commit();

            return [
                'user' => $user,
                'access_token' => $token,
                'refresh_token' => (string) $refreshToken['jti'],
            ];
        } catch (\Exception $error) {
            DB::rollback();

            if (isset($data['image_path'])) {
                Storage::delete($data['image_path']);
            }

            throw $error;
        }
    }

    protected function createRefreshToken(User $user)
    {
        $jti = Str::uuid();
        $expiresAt = now()->addMinutes(config('jwt.refresh_ttl'));

        return RefreshToken::create([
            'user_id' => $user->id,
            'jti' => $jti,
            'expires_at' => $expiresAt,
            'revoked' => false,
        ]);
    }

    public function updateAccessToken($jti)
    {
        $refreshToken = RefreshToken::where('jti', $jti)
            ->where('revoked', false)
            ->with('user')
            ->first();

        if (!$refreshToken) {
            throw new NotFoundException('Refresh Token');
        }
        if (!$refreshToken->isValid()) {
            throw new TokenException('Refresh Token');
        }

        $user = $refreshToken->user;
        if(!$user) {
            throw new NotFoundException('User');
        }

        $refreshToken->revoke();

        $newAccessToken = JWTAuth::fromUser($user);
        $newRefreshToken = $refreshToken->generate($user);

        return [
            'access_token' => $newAccessToken,
            'refresh_token' => (string) $newRefreshToken->jti,
            'token_type' => 'bearer',
            'expires_at' => JWTAuth::factory()->getTTL() * 60,
        ];
    }
}
