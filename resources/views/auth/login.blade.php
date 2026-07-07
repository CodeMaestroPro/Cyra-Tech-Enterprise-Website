@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
    <main class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-cyra-accent">
                    {{ config('cyra.name') }} Command Center
                </p>
                <h1 class="text-3xl font-bold text-cyra-text">Sign in to your account</h1>
                <p class="mt-2 text-sm text-cyra-muted">Secure access for authorized Cyra-Tech team members.</p>
            </div>

            <section class="cyra-card p-8">
                @if (session('success'))
                    <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
                @endif

                @if ($errors->any())
                    <x-ui.alert variant="error" class="mb-6">
                        {{ $errors->first() }}
                    </x-ui.alert>
                @endif

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="space-y-5"
                    id="login-form"
                    novalidate
                >
                    @csrf

                    <x-ui.input
                        name="email"
                        type="email"
                        label="Email address"
                        placeholder="admin@cyratech.com"
                        autocomplete="email"
                        required
                    />

                    <x-ui.input
                        name="password"
                        type="password"
                        label="Password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    />

                    <div class="flex items-center justify-between gap-4">
                        <x-ui.checkbox name="remember" label="Remember me" />
                        <a href="{{ route('home') }}" class="text-sm text-cyra-accent hover:text-cyra-primary">Back to site</a>
                    </div>

                    <x-ui.button type="submit" class="w-full" id="login-submit">
                        Sign In
                    </x-ui.button>
                </form>
            </section>

            <p class="mt-6 text-center text-xs text-cyra-muted">
                Default admin: {{ config('cyra.admin.email') }} (seeded in development)
            </p>
        </div>
    </main>
@endsection
