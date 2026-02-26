<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RefreshToken extends Model
{
    use HasFactory;

    protected $table = 'refresh_tokens';

    protected $fillable = [
        'user_id',
        'jti',
        'expires_at',
        'revoked',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->revoked && !$this->isExpired();
    }

    public static function generate(User $user): self
    {
        return self::create([
            'user_id' => $user->id,
            'jti' => Str::uuid(),
            'expires_at' => now()->addMinutes(config('jwt.refresh_ttl')),
            'revoked' => false,
        ]);
    }

    public function revoke(): bool
    {
        $this->revoked = true;
        return $this->save();
    }
}
