<?php

namespace App\Services;

use App\Models\User;
use App\Models\LuckyLink;
use Illuminate\Support\Str;

class LuckyRegistrationService
{
    public function register(array $validatedData): LuckyLink
    {
        $user = User::create([
            'username' => $validatedData['username'],
            'phone_number' => $validatedData['phone_number'],
        ]);

        return LuckyLink::create([
            'user_id' => $user->id,
            'token' => Str::uuid(),
            'expires_at' => now()->addMinutes(LuckyLink::EXPIRATION_MINUTES),
        ]);
    }
}
