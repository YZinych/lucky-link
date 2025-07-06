<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class LuckyLink
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property bool $active
 * @property Carbon $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read User $user
 * @property-read Collection|LuckyAttempt[] $attempts
 */
class LuckyLink extends Model
{
    use HasFactory;
    public const int EXPIRATION_MINUTES = 7 * 24 * 60;

    protected $fillable = [
        'user_id',
        'token',
        'active',
        'expires_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(LuckyAttempt::class);
    }

    public function isActive(): bool
    {
        return $this->active && $this->expires_at->isFuture();
    }
}
