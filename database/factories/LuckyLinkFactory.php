<?php

namespace Database\Factories;

use App\Models\LuckyLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LuckyLink>
 */
class LuckyLinkFactory extends Factory
{
    protected $model = LuckyLink::class;

    public function definition(): array
    {
        return [
            'token' => Str::uuid(),
            'active' => true,
            'expires_at' => now()->addMinutes(30),
            'user_id' => User::factory(),
        ];
    }
}
