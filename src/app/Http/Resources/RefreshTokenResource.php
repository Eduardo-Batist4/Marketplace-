<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->when(isset($this['user']), fn() => UserResource::make($this['user'])),
            'access_token' => $this['access_token'],
            'refresh_token' => $this['refresh_token'],
            'token_type' => $this['token_type'] ?? 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'refresh_expires_in' => $this['refresh_expires_in'] ?? config('jwt.refresh_ttl') * 60,
        ];
    }
}
