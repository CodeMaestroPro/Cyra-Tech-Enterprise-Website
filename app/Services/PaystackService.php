<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService extends BaseService
{
    public function isConfigured(): bool
    {
        return filled($this->secretKey()) && filled($this->publicKey());
    }

    public function publicKey(): string
    {
        return (string) config('services.paystack.public_key', '');
    }

    public function secretKey(): string
    {
        return (string) config('services.paystack.secret_key', '');
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array{success: bool, authorization_url?: string, reference?: string, access_code?: string, message?: string}
     */
    public function initializeTransaction(
        string $email,
        int $amountInKobo,
        string $reference,
        string $callbackUrl,
        array $metadata = [],
    ): array {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Paystack is not configured. Please contact support.',
            ];
        }

        try {
            $response = Http::withToken($this->secretKey())
                ->acceptJson()
                ->post($this->apiUrl().'/transaction/initialize', [
                    'email' => $email,
                    'amount' => $amountInKobo,
                    'reference' => $reference,
                    'callback_url' => $callbackUrl,
                    'currency' => 'NGN',
                    'metadata' => $metadata,
                ]);

            if (! $response->successful() || ! ($response->json('status') === true)) {
                Log::warning('Paystack initialize failed.', [
                    'reference' => $reference,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return [
                    'success' => false,
                    'message' => $response->json('message') ?? 'Unable to start payment. Please try again.',
                ];
            }

            $data = $response->json('data', []);

            return [
                'success' => true,
                'authorization_url' => $data['authorization_url'] ?? null,
                'reference' => $data['reference'] ?? $reference,
                'access_code' => $data['access_code'] ?? null,
            ];
        } catch (\Throwable $exception) {
            Log::error('Paystack initialize exception.', [
                'reference' => $reference,
                'error' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment service is temporarily unavailable. Please try again.',
            ];
        }
    }

    /**
     * @return array{success: bool, status?: string, amount?: int, paid_at?: string|null, message?: string}
     */
    public function verifyTransaction(string $reference): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Paystack is not configured.',
            ];
        }

        try {
            $response = Http::withToken($this->secretKey())
                ->acceptJson()
                ->get($this->apiUrl().'/transaction/verify/'.rawurlencode($reference));

            if (! $response->successful() || ! ($response->json('status') === true)) {
                return [
                    'success' => false,
                    'message' => $response->json('message') ?? 'Payment verification failed.',
                ];
            }

            $data = $response->json('data', []);

            return [
                'success' => ($data['status'] ?? '') === 'success',
                'status' => $data['status'] ?? null,
                'amount' => isset($data['amount']) ? (int) $data['amount'] : null,
                'paid_at' => $data['paid_at'] ?? null,
                'message' => $response->json('message'),
            ];
        } catch (\Throwable $exception) {
            Log::error('Paystack verify exception.', [
                'reference' => $reference,
                'error' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment verification is temporarily unavailable.',
            ];
        }
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        if (! filled($signature) || ! $this->isConfigured()) {
            return false;
        }

        $computed = hash_hmac('sha512', $payload, $this->secretKey());

        return hash_equals($computed, $signature);
    }

    private function apiUrl(): string
    {
        return rtrim((string) config('services.paystack.payment_url', 'https://api.paystack.co'), '/');
    }
}
