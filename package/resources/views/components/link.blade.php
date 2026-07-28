@props([
    'href' => '#',
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'external' => false,
])
@php
    $variant = in_array($variant, ['flat', 'outline', 'faded', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp
<a
    href="{{ $href }}"
    data-slot="link"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class(['app-link', 'app-link-'.$variant, 'app-link-'.$size, 'app-color-'.$color]) }}
>{{ $slot }}@if($external)<x-icon name="arrow-right-up-linear" />@endif</a>
