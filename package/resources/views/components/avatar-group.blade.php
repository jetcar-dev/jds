@props([
    'max' => 5,
    'total' => null,
    'size' => 'md',
    'radius' => 'full',
    'color' => 'default',
    'bordered' => true,
    'disabled' => false,
    'grid' => false,
    'disableAnimation' => false,
])
@php
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'full';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $max = max(1, (int) $max);
@endphp

<div
    data-slot="base"
    data-ui-component="avatar-group"
    data-max="{{ $max }}"
    @if($total !== null) data-total="{{ max(0, (int) $total) }}" @endif
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-color="{{ $color }}"
    data-bordered="{{ $bordered ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-grid="{{ $grid ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    {{ $attributes->class("app-avatar-group app-color-$color app-radius-$radius") }}
>
    {{ $slot }}
    <span data-slot="count" class="app-avatar app-avatar-count" hidden aria-label="추가 사용자"></span>
</div>
