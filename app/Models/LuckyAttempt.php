<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LuckyAttempt
 *
 * @property int $id
 * @property int $lucky_link_id
 * @property int $number
 * @property bool $win
 * @property string $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read LuckyLink $luckyLink
 */
class LuckyAttempt extends Model
{
    protected $fillable = [
        'lucky_link_id',
        'number',
        'win',
        'amount',
    ];

    protected $casts = [
        'win' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function luckyLink(): BelongsTo
    {
        return $this->belongsTo(LuckyLink::class);
    }
}
