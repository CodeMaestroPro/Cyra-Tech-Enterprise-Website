<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function __construct(
        private readonly NewsletterService $newsletterService,
    ) {
    }

    public function store(NewsletterSubscribeRequest $request): RedirectResponse
    {
        $result = $this->newsletterService->subscribe(
            $request->validated('email'),
            $request->ip(),
        );

        return redirect()
            ->back()
            ->withFragment('newsletter-signup')
            ->with('newsletter_status', $result['status'])
            ->with('newsletter_message', $result['message']);
    }
}
