@props([
    'variant' => 'default',
    'size' => 'default',
    'pressed' => false,
    'disabled' => false,
])

@php
    $variant = in_array($variant, ['default', 'outline'], true) ? $variant : 'default';
    $size = in_array($size, ['sm', 'default', 'lg'], true) ? $size : 'default';
@endphp

<button
    type="button"
    data-slot="toggle"
    data-state="{{ $pressed ? 'on' : 'off' }}"
    aria-pressed="{{ $pressed ? 'true' : 'false' }}"
    @disabled($disabled)
    {{ $attributes->class(['app-toggle', 'app-toggle-'.$variant, 'app-toggle-'.$size]) }}
>
    {{ $slot }}
</button>
