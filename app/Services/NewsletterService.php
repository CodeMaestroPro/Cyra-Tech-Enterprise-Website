<?php

namespace App\Services;

use App\Repositories\NewsletterSubscriberRepository;
use Illuminate\Support\Str;

class NewsletterService extends BaseService
{
    public function __construct(
        private readonly NewsletterSubscriberRepository $newsletterSubscriberRepository,
    ) {
    }

    /**
     * @return array{status: string, message: string}
     */
    public function subscribe(string $email, ?string $ipAddress = null, string $source = 'footer'): array
    {
        $normalizedEmail = Str::lower(trim($email));
        $existing = $this->newsletterSubscriberRepository->findByEmail($normalizedEmail);

        if ($existing !== null && $existing->status === 'active') {
            return [
                'status' => 'info',
                'message' => config(
                    'cyra.navigation.public.newsletter.already_subscribed_message',
                    'You are already subscribed to Cyra-Tech updates.'
                ),
            ];
        }

        if ($existing !== null) {
            $this->newsletterSubscriberRepository->reactivate($existing, $ipAddress);

            return [
                'status' => 'success',
                'message' => config(
                    'cyra.navigation.public.newsletter.success_message',
                    'Thank you for subscribing to Cyra-Tech updates.'
                ),
            ];
        }

        $this->newsletterSubscriberRepository->subscribe([
            'email' => $normalizedEmail,
            'status' => 'active',
            'source' => $source,
            'ip_address' => $ipAddress,
            'subscribed_at' => now(),
        ]);

        return [
            'status' => 'success',
            'message' => config(
                'cyra.navigation.public.newsletter.success_message',
                'Thank you for subscribing to Cyra-Tech updates.'
            ),
        ];
    }
}
