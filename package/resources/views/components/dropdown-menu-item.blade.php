@props([
    'href' => null,
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'inset' => false,
    'disabled' => false,
    'closeOnSelect' => true,
    'type' => 'button',
])
@php
    $variant = in_array($variant, ['flat', 'outline', 'faded', 'ghost'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp

@php $tag = $href ? 'a' : 'button'; @endphp

<{{ $tag }}
    @if($tag === 'a') href="{{ $href }}" @else type="{{ $type }}" @endif
    role="menuitem"
    tabindex="-1"
    data-slot="dropdown-menu-item"
    data-close-on-select="{{ $closeOnSelect ? 'true' : 'false' }}"
    @if($inset) data-inset @endif
    @disabled($disabled)
    @if($disabled) aria-disabled="true" @endif
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    {{ $attributes->class(['app-dropdown-item', 'app-dropdown-item-'.$variant, 'app-dropdown-item-'.$size, 'app-color-'.$color]) }}
>
    {{ $slot }}
</{{ $tag }}>
