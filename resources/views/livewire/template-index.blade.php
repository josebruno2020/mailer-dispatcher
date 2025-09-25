<div>
    <div class="py-12">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Templates') }}
            </h2>
        </x-slot>

        <x-content>
            <x-primary-link href="{{ route('templates.create') }}">
                New Template
            </x-primary-link>

            <x-flash-message class="mt-5" />

            {{-- filter component --}}
        <div class="mt-5">
            <form action="" wire:submit.prevent="search">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <x-input wire:model="name" placeholder="Search by name..." />
                    <x-input wire:model="subject" placeholder="Search by subject..." />
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
                    <x-thead :columns="['#', 'Name', 'Description', 'Subject', 'Actions']"></x-thead>
                    <tbody>
                        @foreach ($templates as $template)
                            <tr class="border dark:border-gray-800 border-gray-200">
                                <x-td>{{ $template->id }}</x-td>
                                <td class="px-4 py-2 text-sm dark:text-gray-100 text-gray-600">{{ $template->name }}</td>
                                <x-td>{{ $template->description }}</x-td>
                                <x-td>{{ $template->subject }}</x-td>
                                <td class="px-4 py-2 text-sm dark:text-gray-100 text-gray-600">
                                    <x-secondary-link href="{{ route('templates.create', ['id' => $template->id]) }}">
                                        Edit
                                    </x-secondary-link>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-5">
                    {{ $templates->links() }}
                </div>
            </div>
        </x-content>

    </div>
