<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRegistrationRequest;
use App\Services\PaystackService;
use App\Services\StudentRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function __construct(
        private readonly StudentRegistrationService $studentRegistrationService,
        private readonly PaystackService $paystackService,
    ) {
    }

    public function show(): View
    {
        $page = $this->studentRegistrationService->getPage();
        $registration = null;

        if (session()->has('registration_reference')) {
            $registration = $this->studentRegistrationService->findRegistrationByReference(
                (string) session('registration_reference')
            );
        }

        return view('students.index', [
            'page' => $page,
            'registration' => $registration,
            'programs' => $this->studentRegistrationService->getPrograms(),
            'educationLevelOptions' => $this->studentRegistrationService->getEducationLevelOptions(),
            'genderOptions' => $this->studentRegistrationService->getGenderOptions(),
            'stateOptions' => $this->studentRegistrationService->getStateOptions(),
            'skillLevelOptions' => $this->studentRegistrationService->getSkillLevelOptions(),
            'paystackPublicKey' => $this->paystackService->publicKey(),
            'paystackConfigured' => $this->paystackService->isConfigured(),
        ]);
    }

    public function store(StudentRegistrationRequest $request): RedirectResponse
    {
        if (! $this->paystackService->isConfigured()) {
            return back()
                ->withInput()
                ->withErrors(['payment' => 'Online payment is not available right now. Please contact support.']);
        }

        $result = $this->studentRegistrationService->initiateRegistration(
            $request->validated(),
            $request->ip(),
        );

        if (! $result['success'] || empty($result['authorization_url'])) {
            return back()
                ->withInput()
                ->withErrors(['payment' => $result['message'] ?? 'Unable to start payment. Please try again.']);
        }

        return redirect()->away($result['authorization_url']);
    }

    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('lookup', [
            'lookup_reference' => ['required', 'string', 'max:40'],
            'lookup_email' => ['required', 'email'],
        ], [
            'lookup_reference.required' => 'Please enter your registration reference.',
            'lookup_email.required' => 'Please enter the email used during registration.',
            'lookup_email.email' => 'Please enter a valid email address.',
        ]);

        $registration = $this->studentRegistrationService->findRegistrationForLookup(
            $validated['lookup_reference'],
            $validated['lookup_email'],
        );

        if ($registration === null) {
            return back()
                ->withInput()
                ->with('error', 'No registration matched that reference and email. Check both and try again.');
        }

        if (($registration['payment_status'] ?? '') === 'pending') {
            return back()
                ->withInput()
                ->with('error', 'Your initial payment is still pending. Complete the first Paystack checkout or contact support.');
        }

        $message = ($registration['can_pay_balance'] ?? false)
            ? 'Registration found. You can complete your remaining balance below.'
            : 'Registration found. This enrollment is already paid in full.';

        return redirect()
            ->route('students')
            ->with('success', $message)
            ->with('registration_reference', $registration['reference'])
            ->with('registration_lookup', true);
    }

    public function paymentCallback(Request $request): RedirectResponse
    {
        $paystackReference = (string) $request->query('reference', '');

        if ($paystackReference === '') {
            return redirect()
                ->route('students')
                ->with('error', 'Payment reference was not provided. Please try again or contact support.');
        }

        $registration = $this->studentRegistrationService->verifyAndCompletePayment($paystackReference);

        if ($registration === null) {
            return redirect()
                ->route('students')
                ->with('error', 'We could not verify your payment. If you were charged, please contact support with your payment reference.');
        }

        $message = ($registration['payment_status'] ?? '') === 'partial'
            ? config(
                'cyra.student_registrations.form.partial_success_message',
                'Initial payment received! You can complete the remaining balance anytime from your receipt.'
            )
            : config(
                'cyra.student_registrations.form.success_message',
                'Payment successful! Your registration is complete.'
            );

        return redirect()
            ->route('students')
            ->with('success', $message)
            ->with('registration_reference', $registration['reference']);
    }

    public function verify(string $reference, string $token): View
    {
        $registration = $this->studentRegistrationService->findVerifiedRegistration($reference, $token);

        return view('students.verify', [
            'registration' => $registration,
            'valid' => $registration !== null,
        ]);
    }

    public function payBalance(string $reference): RedirectResponse
    {
        if (! $this->paystackService->isConfigured()) {
            return redirect()
                ->route('students')
                ->with('error', 'Online payment is not available right now. Please contact support.')
                ->with('registration_reference', $reference);
        }

        $result = $this->studentRegistrationService->initiateBalancePayment($reference);

        if (! $result['success'] || empty($result['authorization_url'])) {
            return redirect()
                ->route('students')
                ->with('error', $result['message'] ?? 'Unable to start balance payment. Please try again.')
                ->with('registration_reference', $reference);
        }

        return redirect()->away($result['authorization_url']);
    }

    public function paystackWebhook(Request $request): Response
    {
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        if (! $this->paystackService->verifyWebhookSignature($payload, $signature)) {
            return response('Invalid signature.', 400);
        }

        $data = json_decode($payload, true);

        if (! is_array($data)) {
            return response('Invalid payload.', 400);
        }

        $this->studentRegistrationService->handleWebhookEvent($data);

        return response('Webhook handled.', 200);
    }
}
