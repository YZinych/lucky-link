<?php

namespace App\Http\Middleware;

use App\Services\LuckyService;
use Closure;
use Illuminate\Http\Request;

class EnsureLinkIsActive
{
    public function __construct(private readonly LuckyService $service) {}

    public function handle(Request $request, Closure $next)
    {
        $token = $request->route('token');
        $link = $this->service->getByToken($token);

        if (!$link) {
            return redirect()
                ->route('register.form')
                ->with('error', 'Link not found');
        }

        if (!$link->isActive()) {
            return redirect()
                ->route('register.form')
                ->with('error', 'Link expired');
        }

        $request->attributes->set('luckyLink', $link);

        return $next($request);
    }
}
