@props(['options' => [], 'selected' => null])

<select {{ $attributes }} class="block mt-1 w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ $value == $selected ? 'selected' : '' }}>
            {{ $label  }}
        </option>
    @endforeach
</select>