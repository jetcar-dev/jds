@props([
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'rows' => null,
    'maxRows' => null,
    'fullWidth' => false,
])

@php
    $variant = $variant === 'outline' ? 'bordered' : ($variant === 'ghost' ? 'underlined' : $variant);
    $variant = in_array($variant, ['flat', 'faded', 'bordered', 'underlined'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp

<textarea
    data-slot="textarea"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if($maxRows !== null) data-max-rows="{{ max(1, (int) $maxRows) }}" @endif
    @if($rows !== null) rows="{{ max(1, (int) $rows) }}" @endif
    {{ $attributes->class([
        'app-textarea', 'app-textarea-'.$variant, 'app-textarea-'.$size,
        'app-color-'.$color,
        'app-textarea-capped' => $maxRows !== null,
        'app-textarea-full' => $fullWidth,
    ]) }}
>{{ $slot }}</textarea>
