@props([
    'src' => null,
    'name' => null,
    'alt' => null,
    'size' => 'md',
    'radius' => 'full',
    'color' => 'default',
    'bordered' => false,
    'disabled' => false,
    'focusable' => false,
    'showFallback' => false,
    'fallback' => null,
    'imageAttributes' => [],
    'disableAnimation' => false,
])
@php
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'full';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $fallbackSlot = $fallback instanceof \Illuminate\View\ComponentSlot ? $fallback : null;
    $fallbackText = !$fallbackSlot && is_scalar($fallback) ? (string) $fallback : '';
    $initial = mb_substr(trim((string) $name), 0, 1);
    $accessibleName = $alt ?: ($name ?: 'avatar');
    $imageAttributes = new \Illuminate\View\ComponentAttributeBag($imageAttributes);
@endphp

<span
    data-slot="base"
    data-ui-component="avatar"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-color="{{ $color }}"
    data-bordered="{{ $bordered ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-focusable="{{ $focusable ? 'true' : 'false' }}"
    data-show-fallback="{{ $showFallback ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-loaded="false"
    data-fallback-visible="{{ $src ? 'false' : 'true' }}"
    @if($focusable && !$disabled) tabindex="0" aria-label="{{ $accessibleName }}" @endif
    @if($disabled) aria-disabled="true" @endif
    {{ $attributes->class("app-avatar app-color-$color app-radius-$radius") }}
>
    <img
        data-slot="img"
        class="app-avatar-img"
        alt="{{ $accessibleName }}"
        @if($src) src="{{ $src }}" @else hidden @endif
        loading="lazy"
        decoding="async"
        {{ $imageAttributes }}
    >

    <span data-slot="fallback" class="app-avatar-fallback" role="img" aria-label="{{ $accessibleName }}">
        @if($fallbackSlot)
            {{ $fallbackSlot }}
        @elseif($fallbackText !== '')
            <span data-slot="name" class="app-avatar-name">{{ $fallbackText }}</span>
        @elseif(isset($icon))
            <span data-slot="icon" class="app-avatar-icon">{{ $icon }}</span>
        @elseif($initial !== '')
            <span data-slot="name" class="app-avatar-name">{{ $initial }}</span>
        @else
            <span data-slot="icon" class="app-avatar-icon" data-default-avatar-icon="true"><x-icon name="user-linear" /></span>
        @endif
    </span>
</span>
