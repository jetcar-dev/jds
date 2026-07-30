@props([
    'variant' => null,
    'color' => null,
    'size' => 'md',
    'pressed' => false,
    'disabled' => false,
    'value' => null,
])

@php
    $hasExplicitVariant = $variant !== null;
    $hasExplicitColor = $color !== null;
    $variant ??= 'flat';
    $color ??= 'default';
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp

<button
    type="button"
    data-slot="toggle"
    data-state="{{ $pressed ? 'on' : 'off' }}"
    data-variant="{{ $variant }}"
    @if($hasExplicitVariant) data-group-variant="explicit" @endif
    data-color="{{ $color }}"
    @if($hasExplicitColor) data-group-color="explicit" @endif
    data-size="{{ $size }}"
    @if($value !== null) data-value="{{ $value }}" @endif
    aria-pressed="{{ $pressed ? 'true' : 'false' }}"
    @disabled($disabled)
    {{ $attributes->class(['app-toggle', 'app-toggle-'.$variant, 'app-toggle-'.$size, 'app-color-'.$color]) }}
>{{ $slot }}</button>
