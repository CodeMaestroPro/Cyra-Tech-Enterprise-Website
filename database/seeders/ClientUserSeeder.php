<?php

namespace Database\Seeders;

use App\Models\ClientAccount;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientUserSeeder extends Seeder
{
    public function run(): void
    {
        $clientConfig = config('cyra.client_user');
        $account = ClientAccount::query()->where('slug', $clientConfig['account'] ?? 'novabank')->first();

        if ($account === null) {
            return;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $clientConfig['email']],
            [
                'name' => $clientConfig['name'],
                'password' => Hash::make($clientConfig['password']),
                'is_active' => true,
                'email_verified_at' => now(),
                'client_account_id' => $account->id,
            ],
        );

        $user->syncRoles([$clientConfig['role']]);
    }
}
