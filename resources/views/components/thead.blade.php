@props(['columns' => []])

<thead class="dark:bg-gray-900 bg-gray-100">
  <tr>
      @foreach ($columns as $column)
        <th class="px-4 py-2 text-left text-sm font-medium dark:text-gray-100 text-gray-700">{{$column}}</th>
      @endforeach
  </tr>
</thead>