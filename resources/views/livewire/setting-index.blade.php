<div class="py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <x-content>
        <x-primary-link href="{{ route('settings.create') }}">
            New Settings
        </x-primary-link>

        <x-flash-message class="mt-5" />

        {{-- filter component --}}
        <div class="mt-5">
            <form action="" wire:submit.prevent="search">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <x-input wire:model="name" placeholder="Search by name..." />
                    <x-input wire:model="host" placeholder="Search by host..." />
                    <x-input wire:model="username" placeholder="Search by username..." />
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
                <x-thead :columns="['#', 'Name', 'Host', 'Port', 'Username', 'Actions']"></x-thead>
                <tbody>
                    @foreach ($settings as $setting)
                        <tr class="border dark:border-gray-800 border-gray-200">
                            <x-td>{{ $setting->id }}</x-td>
                            <td class="px-4 py-2 text-sm dark:text-gray-100 text-gray-600">{{ $setting->name }}</td>
                            <x-td>{{ $setting->host }}</x-td>
                            <x-td>{{ $setting->port }}</x-td>
                            <x-td>{{ $setting->username }}</x-td>
                            <td class="px-4 py-2 text-sm dark:text-gray-100 text-gray-600">
                                <x-secondary-link href="{{ route('settings.create', ['id' => $setting->id]) }}">
                                    Edit
                                </x-secondary-link>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-5">
                {{ $settings->links() }}
            </div>
        </div>
    </x-content>
</div>
