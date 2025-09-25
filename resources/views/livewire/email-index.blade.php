<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Emails') }}
        </h2>
    </x-slot>

    <x-content>
        <x-flash-message class="mt-5" />

        {{-- filter component --}}
        <div class="mt-5">
            <form action="" wire:submit.prevent="search">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <div>
                        <x-label for="email_id" value="Email ID" class="block mb-1" />
                        <x-input wire:model="email_id" />
                    </div>
                    <div>
                        <x-label for="status" value="Status" class="block mb-1" />
                        <x-select wire:model="status" :options="$statuses" />
                    </div>
                    <div>
                        <x-label for="setting_id" value="Setting" class="block mb-1" />
                        <x-select wire:model="setting_id" :options="$settings" />
                    </div>
                    <div>
                        <x-label for="template_id" value="Template" class="block mb-1" />
                        <x-select wire:model="template_id" :options="$templates" />
                    </div>
                </div>
                <div class="mt-3">
                    <x-secondary-button type="submit">
                        Search
                    </x-secondary-button>
                    <x-secondary-button type="button" wire:click="resetFilters" class="ml-2">
                        Reset
                    </x-secondary-button>
                </div>
            </form>
        </div>

        <div class="mt-3 overflow-x-auto w-100">
            <table
                class="min-w-full border dark:border-gray-600 border-gray-200 dark:bg-gray-600 bg-white rounded-lg table-auto">
                <x-thead :columns="['#', 'Setting', 'Template', 'To', 'Status', 'Sent At', 'Actions']"></x-thead>
                <tbody>
                    @foreach ($emails as $email)
                        <tr class="border dark:border-gray-800 border-gray-200">
                            <x-td>{{ $email->id }}</x-td>
                            <x-td>{{ $email->setting->name }}</x-td>
                            <x-td>{{ $email->template->name }}</x-td>
                            <x-td>{{ $email->to['address'] }}</x-td>
                            <x-td>
                                <x-email-status :status="$email->status" />
                            </x-td>
                            <x-td>
                                <x-format-date :date="$email->sent_at" />
                            </x-td>
                            <td class="px-4 py-2 text-sm dark:text-gray-100 text-gray-600">
                                <x-secondary-link href="{{ route('emails.create', ['id' => $email->id]) }}">
                                    View
                                </x-secondary-link>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-5">
                {{ $emails->links() }}
            </div>
        </div>
    </x-content>
</div>
