<x-modal name="generate-email" :show="$isGenerateModal" focusable>
    <form wire:submit.prevent="generate">
        <div class="mt-4">
            <x-label for="to_address" value="To Address" />
            <x-input type="email" wire:model.defer="to_address" id="to_address" class="mt-1 block w-full" />
            @error('to_address')
                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-4">
            <x-label for="parameters" value="Parameters (JSON format)" />
            <textarea wire:model.defer="parameters" id="parameters" rows="4"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            @error('parameters')
                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
            @enderror
        </div>

        {{-- <div class="mt-4">
            <x-label for="setting_id" value="Setting" />
            <x-select wire:model="setting_id" :options="$settings" id="setting_id" />
            @error('setting_id')
                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
            @enderror
        </div> --}}

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')" class="mr-2">
                Cancel
            </x-secondary-button>
            <x-primary-button type="submit">
                Generate
            </x-primary-button>
        </div>
    </form>
</x-modal>
