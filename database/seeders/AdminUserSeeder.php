<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminConfig = config('cyra.admin');

        $user = User::query()->updateOrCreate(
            ['email' => $adminConfig['email']],
            [
                'name' => $adminConfig['name'],
                'password' => Hash::make($adminConfig['password']),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $user->syncRoles([$adminConfig['role']]);
    }
}
