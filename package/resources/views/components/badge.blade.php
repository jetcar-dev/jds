@props([
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'href' => null,
])

@php
    $variant = in_array($variant, ['flat', 'outline', 'faded', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $tag = $href ? 'a' : 'span';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    data-slot="badge"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    {{ $attributes->class(['app-badge', 'app-badge-'.$variant, 'app-badge-'.$size, 'app-color-'.$color]) }}
>{{ $slot }}</{{ $tag }}>
