@props([
    'orientation' => 'horizontal',
    'align' => 'center',
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'fullWidth' => false,
    'collapse' => false,
    'selection' => null,
    'value' => null,
    'name' => null,
])

@php
    $orientation = $orientation === 'vertical' ? 'vertical' : 'horizontal';
    $align = in_array($align, ['start', 'center', 'end', 'stretch'], true) ? $align : 'center';
    $variant = in_array($variant, ['flat', 'outline', 'faded', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $selection = in_array($selection, ['single', 'multiple'], true) ? $selection : null;
    $values = $selection === 'multiple' ? (array) ($value ?? []) : [$value];
@endphp

<div
    data-slot="group"
    data-orientation="{{ $orientation }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if($selection) data-selection="{{ $selection }}" data-value="{{ implode('|', array_filter($values, fn ($item) => $item !== null && $item !== '')) }}" role="group" @endif
    @if($collapse) data-collapse="true" @endif
    {{ $attributes->class([
        'app-group',
        'app-group-'.$orientation,
        'app-group-align-'.$align,
        'app-group-'.$variant,
        'app-group-'.$size,
        'app-color-'.$color,
        'app-group-full' => $fullWidth,
        'app-group-selection' => $selection,
    ]) }}
>
    {{ $slot }}
    @if($selection && $name)
        <input type="hidden" data-group-value name="{{ $name }}" value="{{ $selection === 'multiple' ? json_encode(array_values(array_filter($values, fn ($item) => $item !== null && $item !== '')), JSON_UNESCAPED_UNICODE) : ($values[0] ?? '') }}">
    @endif
</div>
