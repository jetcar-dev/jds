{{--
    탭 컨테이너

    <x-tabs value="account">
        <x-tabs-list>...</x-tabs-list>
        <x-tabs-content value="account">...</x-tabs-content>
    </x-tabs>
--}}
@props([
    'value' => null,
    'orientation' => 'horizontal',
    'fullWidth' => false,
    'variant' => 'solid',
    'color' => 'default',
])

@php
    $orientation = in_array($orientation, ['horizontal', 'vertical'], true) ? $orientation : 'horizontal';
    $variant = match ($variant) { 'box' => 'solid', 'line' => 'underlined', 'round' => 'light', default => $variant };
    $variant = in_array($variant, ['solid', 'underlined', 'bordered', 'light'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
@endphp

<div
    data-slot="tabs"
    data-default-value="{{ $value }}"
    data-orientation="{{ $orientation }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    {{ $attributes->class(['app-tabs', 'app-tabs-'.$orientation, 'app-tabs-'.$variant, 'app-color-'.$color, 'app-tabs-full' => $fullWidth]) }}
>
    {{ $slot }}
</div>
