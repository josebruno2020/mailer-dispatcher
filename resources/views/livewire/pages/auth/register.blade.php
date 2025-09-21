<?php

use App\Models\User;
use App\Services\{AccountService, UserService};
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component {
    public string $document = '';
    public string $account_name = '';

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(AccountService $accountService, UserService $userService): void
    {
        $validated = $this->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'document' => ['nullable', 'string', 'unique:' . App\Models\Account::class],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $account = $accountService->create([
            'name' => $validated['account_name'],
            'document' => $validated['document'] ?? null,
        ]);
        unset($validated['account_name'], $validated['document']);
        $validated['password'] = Hash::make($validated['password']);
        $validated['account_id'] = $account->id;
        $user = $userService->create($validated);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; 
?>

<div>
    <form wire:submit="register">
        <div class="dark:text-white">
            <h2>Account Information</h2>
        </div>
        <!-- Account Name -->
        <div class="mt-4">
            <x-input-label for="account_name" :value="__('Account name')" />
            <x-text-input wire:model="account_name" id="account_name" class="block mt-1 w-full" type="text"
                name="account_name" required autofocus autocomplete="account_name" />
            <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
        </div>

        <!-- Account Document -->
        <div class="mt-4">
            <x-input-label for="document" :value="__('Document')" />
            <x-text-input wire:model="document" id="document" class="block mt-1 w-full" type="text" name="document"
                autocomplete="document" />
            <x-input-error :messages="$errors->get('document')" class="mt-2" />
        </div>

        {{-- divider --}}
        <div class="border-t border-gray-500 my-4"></div>

        <div class="dark:text-white">
            <h2>User Information</h2>
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name"
                required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
