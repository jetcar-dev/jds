@props([
    'name' => null,
    'maxlength' => 6,
    'value' => '',
    'disabled' => false,
    'alphanumeric' => false,
    'ariaLabel' => '일회용 인증번호',
])

@php
    $inputmode = $alphanumeric ? 'text' : 'numeric';
    $pattern = $alphanumeric ? '[a-zA-Z0-9]*' : '[0-9]*';
@endphp

<div
    data-slot="input-otp"
    data-maxlength="{{ $maxlength }}"
    data-alphanumeric="{{ $alphanumeric ? 'true' : 'false' }}"
    {{ $attributes->class('app-input-otp') }}
>
    <input
        value="{{ $value }}"
        maxlength="{{ $maxlength }}"
        inputmode="{{ $inputmode }}"
        autocomplete="one-time-code"
        pattern="{{ $pattern }}"
        aria-label="{{ $ariaLabel }}"
        @if($name) name="{{ $name }}" @endif
        @disabled($disabled)
        class="app-input-otp-control"
    >
    {{ $slot }}
</div>
