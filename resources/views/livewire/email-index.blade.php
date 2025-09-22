<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Emails') }}
        </h2>
    </x-slot>

    <x-content>
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
                            <x-td>{{ $email->sent_at }}</x-td>
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
