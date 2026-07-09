<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContactInquiryRequest;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService,
    ) {
    }

    public function index(): View
    {
        return view('admin.contact.index', [
            'contact' => $this->contactService->getAdminWorkspace(),
        ]);
    }

    public function edit(string $reference): View|RedirectResponse
    {
        $inquiry = $this->contactService->getAdminInquiry($reference);

        if ($inquiry === null) {
            return redirect()
                ->route('admin.contact.index')
                ->with('error', 'Inquiry not found.');
        }

        return view('admin.contact.edit', [
            'inquiry' => $inquiry,
            'statusOptions' => $this->contactService->getStatusOptions(),
        ]);
    }

    public function update(UpdateContactInquiryRequest $request, string $reference): RedirectResponse
    {
        $inquiry = $this->contactService->updateInquiry($reference, $request->validated());

        if ($inquiry === null) {
            return redirect()
                ->route('admin.contact.index')
                ->with('error', 'Inquiry not found.');
        }

        return redirect()
            ->route('admin.contact.edit', $inquiry['reference'])
            ->with('success', 'Inquiry updated successfully.');
    }

    public function destroy(string $reference): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('crm.update') ?? false, 403);

        if (! $this->contactService->deleteInquiry($reference)) {
            return redirect()
                ->route('admin.contact.index')
                ->with('error', 'Inquiry not found.');
        }

        return redirect()
            ->route('admin.contact.index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}
