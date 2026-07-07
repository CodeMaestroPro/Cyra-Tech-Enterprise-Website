<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactInquiryRequest;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService,
    ) {
    }

    public function show(): View
    {
        return view('contact.index', [
            'contact' => $this->contactService->getContact(),
            'inquiryOptions' => $this->contactService->getInquiryTypeOptions(),
        ]);
    }

    public function store(ContactInquiryRequest $request): RedirectResponse
    {
        $result = $this->contactService->submitInquiry(
            $request->validated(),
            $request->ip(),
        );

        return redirect()
            ->route('contact')
            ->with('success', $result['message'])
            ->with('reference', $result['reference']);
    }
}
