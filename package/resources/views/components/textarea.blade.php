@props([
    'size' => 'default',
    'color' => null,
    'rows' => null,
    'maxRows' => null,
])

@php
    $size = in_array($size, ['sm', 'default', 'lg'], true) ? $size : 'default';
    $style = $color ? "--ring: {$color}; --primary: {$color}; --primary-foreground: #ffffff;" : '';
    $userStyle = (string) $attributes->get('style', '');
    $style = trim($style . ($style && $userStyle ? ' ' : '') . $userStyle);
    $attributes = $attributes->except('style');
@endphp

<textarea
    data-slot="textarea"
    data-size="{{ $size }}"
    @if($maxRows !== null) data-max-rows="{{ max(1, (int) $maxRows) }}" @endif
    @if($rows !== null) rows="{{ max(1, (int) $rows) }}" @endif
    @if($style) style="{{ $style }}" @endif
    {{ $attributes->class(['app-textarea', 'app-textarea-'.$size, 'app-textarea-capped' => $maxRows !== null]) }}
>{{ $slot }}</textarea>
