@props([
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'ariaLabel' => null,
    'disabled' => false,
])

@php
    $variant = in_array($variant, ['flat', 'outline', 'faded', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp

<button
    type="button"
    data-slot="select-trigger"
    data-size="{{ $size }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    role="combobox"
    aria-haspopup="listbox"
    aria-expanded="false"
    data-state="closed"
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    @disabled($disabled)
    {{ $attributes->class('app-select-trigger app-select-trigger-'.$size.' app-select-'.$variant.' app-color-'.$color) }}
>
    {{ $slot }}

    <x-icon name="alt-arrow-down-linear" class="app-select-chevron" />
</button>
