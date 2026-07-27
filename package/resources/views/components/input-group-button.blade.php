@props([
    'size' => 'xs',
    'variant' => 'ghost',
    'type' => 'button',
])

@php
    $variants = [
        'ghost' => 'app-input-group-button-ghost',
        'outline' => 'app-input-group-button-outline',
        'default' => 'app-input-group-button-default',
    ];

    $sizes = [
        'xs' => 'app-input-group-button-xs',
        'sm' => 'app-input-group-button-sm',
        'icon-xs' => 'app-input-group-button-icon-xs',
        'icon-sm' => 'app-input-group-button-icon-sm',
    ];

    $classes = 'app-input-group-button '
        . ($variants[$variant] ?? $variants['ghost']) . ' '
        . ($sizes[$size] ?? $sizes['xs']);
@endphp

<button
    type="{{ $type }}"
    data-slot="input-group-button"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    {{ $attributes->class($classes) }}
>
    {{ $slot }}
</button>
