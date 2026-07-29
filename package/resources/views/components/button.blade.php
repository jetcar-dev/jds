@props([
    'variant' => 'solid',
    'color' => 'default',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'as' => null,
    'disabled' => false,
    'iconOnly' => false,
    'fullWidth' => false,
])

@php
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $tag = $as ?: ($href ? 'a' : 'button');
    $isButtonDisabled = (bool) $disabled;
@endphp

<{{ $tag }}
    data-slot="button"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if($tag === 'a' && $href) href="{{ $href }}" @endif
    @if($tag === 'button') type="{{ $type }}" @endif
    @if($tag === 'button') @disabled($isButtonDisabled) @elseif($isButtonDisabled) aria-disabled="true" tabindex="-1" @endif
    {{ $attributes->class([
        'app-button',
        'app-button-'.$variant,
        'app-button-'.$size,
        'app-color-'.$color,
        'app-button-icon-only' => $iconOnly,
        'app-button-full' => $fullWidth,
    ]) }}
>
    @isset($before){{ $before }}@endisset
    {{ $slot }}
    @isset($after){{ $after }}@endisset
</{{ $tag }}>
