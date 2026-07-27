@props([
    'index' => 0,
])

<div
    data-slot="input-otp-slot"
    data-index="{{ $index }}"
    data-active="false"
    aria-hidden="true"
    {{ $attributes->class('app-input-otp-slot') }}
>
    <span data-slot="input-otp-value"></span>
    <span data-slot="input-otp-caret" class="app-input-otp-caret" hidden></span>
</div>
