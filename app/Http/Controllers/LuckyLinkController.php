<?php

namespace App\Http\Controllers;

use App\Models\LuckyLink;
use App\Services\LuckyService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LogicException;

class LuckyLinkController extends Controller
{
    public function __construct(private readonly LuckyService $service) {}

    public function show(Request $request): View|Response
    {
        $link = $this->getLuckyLink($request);

        return view('link_active', [
            'link' => $link,
        ]);
    }

    public function deactivate(Request $request): RedirectResponse
    {
        $luckyLink = $this->getLuckyLink($request);

        $this->service->forgetToken($luckyLink->token);
        $this->service->deactivate($luckyLink);

        return redirect()->route('register.form')
            ->with('info', 'Link deactivated');
    }

    public function regenerate(Request $request): RedirectResponse
    {
        $luckyLink = $this->getLuckyLink($request);

        $this->service->forgetToken($luckyLink->token);
        $this->service->regenerate($luckyLink);

        return redirect()->route('link.show', $luckyLink->token)
            ->with('info', 'New link generated');
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function lucky(Request $request): Response|RedirectResponse
    {
        $luckyLink = $this->getLuckyLink($request);

        try {
            $result = $this->service->play($luckyLink);
        } catch (Exception $e) {
            return redirect()->route('link.show', $luckyLink->token)
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->route('link.show', $luckyLink->token)
            ->with([
                'status' => 'success_lucky_call',
                'number' => $result['number'],
                'win' => $result['win'],
                'amount' => $result['amount'],
            ]);
    }

    public function history(Request $request): View
    {
        $luckyLink = $this->getLuckyLink($request);
        $attempts = $this->service->getRecentAttempts($luckyLink);

        return view('history', compact('attempts', 'luckyLink'));
    }

    /** Get LuckyLink object set via EnsureLinkIsActive Middleware
     *
     * @param Request $request
     * @return LuckyLink
     */
    protected function getLuckyLink(Request $request): LuckyLink
    {
        $link = $request->attributes->get('luckyLink');

        if (!$link instanceof LuckyLink) {
            throw new LogicException('LuckyLink not resolved');
        }

        return $link;
    }
}
