<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Emails') }} - {{ $email->id }}
        </h2>
    </x-slot>

    <x-content>
        <div
            class="grid grid-cols-2 md:grid-cols-4 space-y-4 md:space-y-0 gap-6 border dark:border-gray-700 border-gray-300 rounded-lg p-4 bg-white dark:bg-gray-800">
            <div>
                <x-label for="setting" value="Setting" />
                <a class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                    href="{{ route('settings.create', ['id' => $email->setting->id]) }}">
                    {{ $email->setting->name }}
                </a>
            </div>
            <div>
                <x-label for="template" value="Template" />
                <a class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                    href="{{ route('templates.create', ['id' => $email->template->id]) }}">
                    {{ $email->template->name }}
                </a>
            </div>
            <div>
                <x-label for="from" value="From" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ isset($email->from['name']) ? $email->from['name'] . ' <' . $email->from['address'] . '>' : $email->from['address'] }}
                </div>
            </div>
            <div>
                <x-label for="to" value="To" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ isset($email->to['name']) ? $email->to['name'] . ' <' . $email->to['address'] . '>' : $email->to['address'] }}
                </div>
            </div>
            <div>
                <x-label for="cc" value="CC" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $email->cc ?? 'N/A' }}
                </div>
            </div>
            <div>
                <x-label for="bcc" value="BCC" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $email->bcc ?? 'N/A' }}
                </div>
            </div>
            <div>
                <x-label for="status" value="Status" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <x-email-status :status="$email->status" />
                </div>
            </div>
            <div>
                <x-label for="sent_at" value="Sent At" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <x-format-date :date="$email->sent_at" />
                </div>
            </div>
            <div>
                <x-label for="created_at" value="Created At" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <x-format-date :date="$email->created_at" />
                </div>
            </div>

            <div>
                <x-label for="updated_at" value="Updated At" />
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <x-format-date :date="$email->updated_at" />
                </div>
            </div>

            <div>
            <x-label for="webhook_status" value="Webhook Status" />
            <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $email->webhook_status ?? 'N/A' }}
            </div>
        </div>
        </div>

        <div>
            <x-label for="error_message" value="Error Message" class="mt-4" />
            <div
                class="mt-1 text-sm text-red-600 dark:text-red-400 border dark:border-gray-700 border-gray-300 rounded-lg p-4 bg-white dark:bg-gray-800">
                {{ $email->error_message ?? 'N/A' }}
            </div>
        </div>

        <div class="mt-6">
            <x-label for="body" value="Body" class="mt-4" />
            <div
                class="mt-1 text-sm text-gray-600 dark:text-gray-400 border dark:border-gray-700 border-gray-300 rounded-lg p-4 bg-white dark:bg-gray-800">
                {!! $email->body !!}
            </div>
        </div>

        <div class="mt-6">
            <x-label for="webhook_status" value="Webhook Status" class="mt-4" />
            <div
                class="mt-1 text-sm text-gray-600 dark:text-gray-400 border dark:border-gray-700 border-gray-300 rounded-lg p-4 bg-white dark:bg-gray-800">
                {{ $email->webhook_status ?? 'N/A' }}
            </div>
        </div>

        <div class="mt-6">
            <x-label for="webhook_data" value="Webhook Response" class="mt-4" />
            <div
                class="mt-1 text-sm text-gray-600 dark:text-gray-400 border dark:border-gray-700 border-gray-300 rounded-lg p-4 bg-white dark:bg-gray-800">
                <pre>
                    {{ $email->webhook_data ?? 'N/A' }}
                </pre>
            </div>
        </div>

        <div>
            <x-secondary-link href="{{ route('emails') }}" class="mt-6">
                Back to Emails
            </x-secondary-link>
            <x-secondary-button class="mt-6" wire:click="resend">
                Resend
            </x-secondary-button>
            <x-button class="mt-6" wire:click="reload">
                Reload
            </x-button>
            <x-danger-button class="mt-6" wire:confirm="Are you sure you want to delete this email?"
                wire:click="delete">
                Delete
            </x-danger-button>
        </div>
    </x-content>
</div>
