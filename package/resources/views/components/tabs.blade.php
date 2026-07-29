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
])

@php
    $orientation = in_array($orientation, ['horizontal', 'vertical'], true) ? $orientation : 'horizontal';
    $variant = match ($variant) { 'box' => 'solid', 'line' => 'underlined', 'round' => 'light', default => $variant };
    $variant = in_array($variant, ['solid', 'underlined', 'bordered', 'light'], true) ? $variant : 'solid';
@endphp

<div
    data-slot="tabs"
    data-default-value="{{ $value }}"
    data-orientation="{{ $orientation }}"
    data-variant="{{ $variant }}"
    {{ $attributes->class(['app-tabs', 'app-tabs-'.$orientation, 'app-tabs-'.$variant, 'app-tabs-full' => $fullWidth]) }}
>
    {{ $slot }}
</div>
