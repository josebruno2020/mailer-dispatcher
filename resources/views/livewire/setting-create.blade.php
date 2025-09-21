<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <x-content>
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" autofocus wire:model.defer="form.name" />
                    <x-input-error for="name" class="mt-2" :messages="$errors->get('form.name')" />
                </div>
                <div>
                    <x-label for="host" value="{{ __('Host') }}" />
                    <x-input id="host" type="text" class="mt-1 block w-full" wire:model.defer="form.host" />
                    <x-input-error for="host" class="mt-2" :messages="$errors->get('form.host')" />
                </div>
                <div>
                    <x-label for="port" value="{{ __('Port') }}" />
                    <x-input id="port" type="text" class="mt-1 block w-full" wire:model.defer="form.port" />
                    <x-input-error for="port" class="mt-2" :messages="$errors->get('form.port')" />
                </div>
                <div>
                    <x-label for="username" value="{{ __('Username') }}" />
                    <x-input id="username" type="text" class="mt-1 block w-full" wire:model.defer="form.username" />
                    <x-input-error for="username" class="mt-2" :messages="$errors->get('form.username')" />
                </div>
                <div>
                    <x-label for="password" value="{{ __('Password') }}" />
                    <div class="relative">
                        <x-input id="password" type="password" class="mt-1 block w-full pr-10" wire:model.defer="form.password" />
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                            <svg id="eye-open" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-closed" class="h-5 w-5 text-gray-400 hover:text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    <x-input-error for="password" class="mt-2" :messages="$errors->get('form.password')" />
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-secondary-button href="{{ route('settings') }}" wire:navigate>
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-content>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</div>
