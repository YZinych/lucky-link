<?php

namespace App\Services;

use App\Models\LuckyAttempt;
use App\Models\LuckyLink;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LuckyService
{
    public const int HISTORY_LIMIT = 3;
    public const int TOKEN_CACHE_MIN = 10;


    public function deactivate(LuckyLink $link): void
    {
        $link->update(['active' => false]);
    }

    public function regenerate(LuckyLink $link): LuckyLink
    {
        $link->update([
            'token' => Str::uuid(),
            'expires_at' => now()->addMinutes(LuckyLink::EXPIRATION_MINUTES),
            'active' => true,
        ]);

        return $link;
    }

    public function play(LuckyLink $link): array
    {
        $number = $this->getNumber();
        $win = $this->isWinningNumber($number);

        $amount = '0.00';

        if ($win) {
            $amount = $this->calculateAmount($number);
        }

        $this->storeAttempt($link, $number, $win, $amount);

        return [
            'number' => $number,
            'win' => $win,
            'amount' => $amount,
        ];
    }

    private function getNumber(int $limit = 1000): int
    {
        return mt_rand(1, $limit);
    }

    private function isWinningNumber(int $number): bool
    {
        return $number % 2 === 0;
    }

    private function calculateAmount(int $number): string
    {
        $winRules = $this->prepareWinRules();

        foreach ($winRules as $rule) {
            if ($number >= $rule['min']) {
                $multiplier = bcdiv((string) $rule['win_percent'], '100', 2);
                return bcmul((string) $number, $multiplier, 2);
            }
        }

        return '0.00';
    }

    public function getByToken(string $token): ?LuckyLink
    {
        return Cache::remember("lucky_link_{$token}", now()->addMinutes(self::TOKEN_CACHE_MIN), function () use ($token) {
            return LuckyLink::where('token', $token)->first();
        });
    }

    public function forgetToken(string $token): void
    {
        Cache::forget("lucky_link_{$token}");
    }

    public function getRecentAttempts(LuckyLink $link): Collection
    {
        return $link->attempts()
            ->latest()
            ->take(self::HISTORY_LIMIT)
            ->get();
    }

    public function disableExpiredLinks(): int
    {
        return LuckyLink::where('active', true)
            ->where('expires_at', '<=', Carbon::now())
            ->update(['active' => false]);
    }

    private function storeAttempt(LuckyLink $link, int $number, bool $win, string $amount): void
    {
        LuckyAttempt::create([
            'lucky_link_id' => $link->id,
            'number' => $number,
            'win' => $win,
            'amount' => $amount,
        ]);
    }

    /**
     * Get sorted win rules from config.
     *
     * Each rule must contain 'min' and 'win_percent'.
     * Rules are sorted descending by 'min'.
     *
     * @return array<int, array{min: int, win_percent: int}>
     */
    private function prepareWinRules(): array
    {
        return collect(config('lucky.win_rules', []))
            ->filter(fn ($rule) => isset($rule['min'], $rule['win_percent']))
            ->sortByDesc('min')
            ->values()
            ->toArray();
    }
}
