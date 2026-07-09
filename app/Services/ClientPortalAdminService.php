<?php

namespace App\Services;

use App\Models\ClientAccount;
use App\Repositories\ClientAccountRepository;
use Illuminate\Support\Str;

class ClientPortalAdminService extends BaseService
{
    public function __construct(
        private readonly ClientAccountRepository $clientAccountRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminCatalog(): array
    {
        $accounts = $this->clientAccountRepository
            ->getAllAccounts()
            ->map(fn (ClientAccount $account) => $this->formatAccount($account))
            ->values()
            ->all();

        return [
            'description' => 'Manage client portal accounts and access.',
            'summary' => [
                'total' => count($accounts),
                'active' => collect($accounts)->where('is_active', true)->count(),
                'inactive' => collect($accounts)->where('is_active', false)->count(),
            ],
            'accounts' => $accounts,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminAccount(string $slug): ?array
    {
        $account = $this->clientAccountRepository->findBySlug($slug);

        if ($account === null) {
            return null;
        }

        return $this->formatAccount($account, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createAccount(array $data): array
    {
        $account = $this->clientAccountRepository->createAccount([
            'slug' => $data['slug'],
            'name' => $data['name'],
            'industry' => $data['industry'] ?? null,
            'region' => $data['region'] ?? null,
            'account_manager' => $data['account_manager'] ?? null,
            'support_email' => $data['support_email'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return $this->formatAccount($account, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>|null
     */
    public function updateAccount(string $slug, array $data): ?array
    {
        $account = $this->clientAccountRepository->findBySlug($slug);

        if ($account === null) {
            return null;
        }

        $account = $this->clientAccountRepository->updateAccount($account, [
            'name' => $data['name'],
            'industry' => $data['industry'] ?? null,
            'region' => $data['region'] ?? null,
            'account_manager' => $data['account_manager'] ?? null,
            'support_email' => $data['support_email'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return $this->formatAccount($account, detailed: true);
    }

    public function deleteAccount(string $slug): bool
    {
        $account = $this->clientAccountRepository->findBySlug($slug);

        if ($account === null) {
            return false;
        }

        $this->clientAccountRepository->deleteAccount($account);

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAccount(ClientAccount $account, bool $detailed = false): array
    {
        $formatted = [
            'id' => $account->id,
            'slug' => $account->slug,
            'name' => $account->name,
            'industry' => $account->industry,
            'region' => $account->region,
            'account_manager' => $account->account_manager,
            'support_email' => $account->support_email,
            'is_active' => $account->is_active,
            'edit_url' => route('admin.client-portal.edit', $account->slug),
            'engagements_count' => $account->engagements()->count(),
        ];

        if ($detailed) {
            $formatted['public_url'] = route('client-portal');
        }

        return $formatted;
    }

    public function suggestSlug(string $name): string
    {
        return Str::slug($name);
    }
}
