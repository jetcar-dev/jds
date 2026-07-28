@props([
    'variant' => 'default',
])

@php
    $variant = in_array($variant, ['transparent', 'default', 'secondary', 'tertiary', 'outline'], true)
        ? $variant
        : 'default';
@endphp

<div
    data-slot="card"
    data-variant="{{ $variant }}"
    {{ $attributes->class(['app-card', 'app-card-'.$variant]) }}
>
    {{ $slot }}
</div>
