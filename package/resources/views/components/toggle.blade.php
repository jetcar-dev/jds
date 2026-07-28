@props([
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'pressed' => false,
    'disabled' => false,
    'value' => null,
])

@php
    $variant = in_array($variant, ['flat', 'outline', 'faded', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp

<button
    type="button"
    data-slot="toggle"
    data-state="{{ $pressed ? 'on' : 'off' }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if($value !== null) data-value="{{ $value }}" @endif
    aria-pressed="{{ $pressed ? 'true' : 'false' }}"
    @disabled($disabled)
    {{ $attributes->class(['app-toggle', 'app-toggle-'.$variant, 'app-toggle-'.$size, 'app-color-'.$color]) }}
>{{ $slot }}</button>
