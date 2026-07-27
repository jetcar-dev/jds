@props([
    'size' => 'default',
    'ariaLabel' => null,
    'disabled' => false,
])

@php
    $size = in_array($size, ['sm', 'default', 'lg'], true) ? $size : 'default';
@endphp

<button
    type="button"
    data-slot="select-trigger"
    data-size="{{ $size }}"
    role="combobox"
    aria-haspopup="listbox"
    aria-expanded="false"
    data-state="closed"
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    @disabled($disabled)
    {{ $attributes->class('app-select-trigger app-select-trigger-' . $size) }}
>
    {{ $slot }}

    <x-icon name="alt-arrow-down-linear" class="app-select-chevron" />
</button>
