@props([
    'type' => 'single',
    'value' => null,
    'variant' => 'default',
    'size' => 'default',
    'orientation' => 'horizontal',
])

@php
    $type = $type === 'multiple' ? 'multiple' : 'single';
    $variant = in_array($variant, ['default', 'outline'], true) ? $variant : 'default';
    $size = in_array($size, ['sm', 'default', 'lg'], true) ? $size : 'default';
    $orientation = $orientation === 'vertical' ? 'vertical' : 'horizontal';
    $values = $type === 'multiple' ? (array)($value ?? []) : [$value];
@endphp

<div
    data-slot="toggle-group"
    data-type="{{ $type }}"
    data-value="{{ implode('|', array_filter($values, fn ($item) => $item !== null && $item !== '')) }}"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-orientation="{{ $orientation }}"
    role="group"
    {{ $attributes->class(['app-toggle-group', 'app-toggle-group-'.$orientation, 'app-toggle-group-'.$variant]) }}
>
    {{ $slot }}
</div>
