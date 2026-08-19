@props([
    'size' => 'sm',
    'color' => 'default',
    'radius' => 'sm',
])

@php
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'sm';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'sm';
@endphp

<code
    data-slot="base"
    data-ui-component="code"
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    {{ $attributes->class("app-code app-color-$color app-size-$size app-radius-$radius") }}
>{{ $slot }}</code>
