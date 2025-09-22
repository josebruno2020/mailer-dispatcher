@props(['status'])
@php
  use App\Enums\EmailStatusEnum;
@endphp
@if ($status === EmailStatusEnum::SENT->value)
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
        Sent
    </span>
@elseif ($status === EmailStatusEnum::FAILED->value)
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
        Failed
    </span>
@elseif ($status === EmailStatusEnum::PENDING->value)
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
        Pending
    </span>
@elseif ($status === EmailStatusEnum::RETRYING->value)
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
        Retrying
    </span>
@endif
