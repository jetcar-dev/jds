@props([
    'content' => null,
    'variant' => 'solid',
    'color' => 'default',
    'size' => 'md',
    'shape' => 'rectangle',
    'placement' => 'top-right',
    'showOutline' => true,
    'disableOutline' => false,
    'disableAnimation' => false,
    'invisible' => false,
    'oneChar' => false,
    'dot' => false,
])
@php
    $variant = in_array($variant, ['solid', 'flat', 'faded', 'shadow'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $shape = in_array($shape, ['circle', 'rectangle'], true) ? $shape : 'rectangle';
    $placement = in_array($placement, ['top-right', 'top-left', 'bottom-right', 'bottom-left'], true) ? $placement : 'top-right';
    $outline = $showOutline && !$disableOutline;
    $badgeContent = $content ?? ($badge ?? '');
@endphp

<span
    data-slot="base"
    data-ui-component="badge"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-shape="{{ $shape }}"
    data-placement="{{ $placement }}"
    data-invisible="{{ $invisible ? 'true' : 'false' }}"
    data-one-char="{{ $oneChar ? 'true' : 'false' }}"
    data-dot="{{ $dot ? 'true' : 'false' }}"
    data-show-outline="{{ $outline ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    {{ $attributes->class("app-badge app-color-$color") }}
>
    {{ $slot }}
    <span data-slot="badge" class="app-badge-content" aria-hidden="true">@unless($dot){{ $badgeContent }}@endunless</span>
</span>
