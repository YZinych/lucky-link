<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\LuckyRegistrationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly LuckyRegistrationService $service) {}

    public function show(): View
    {
        return view('register');
    }

    public function store(RegisterRequest $validatedRequest): RedirectResponse
    {
        $link = $this->service->register(
            $validatedRequest->validated()
        );

        return redirect()->route('link.show', $link->token)
            ->with('status', 'Your link was created!');
    }
}
