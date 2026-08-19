@props([
    'variant' => 'solid',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'full',
    'disabled' => false,
    'closable' => false,
    'dismissible' => false,
    'disableAnimation' => false,
])
@php
    $variant = in_array($variant, ['solid', 'bordered', 'light', 'flat', 'faded', 'shadow', 'dot'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'full';
    $isClosable = (bool) $closable || (bool) $dismissible;
    $hasAvatar = isset($avatar);
    $hasStartContent = isset($startContent);
    $hasEndContent = isset($endContent);
@endphp

<span
    data-slot="base"
    data-ui-component="chip"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-closable="{{ $isClosable ? 'true' : 'false' }}"
    data-has-avatar="{{ $hasAvatar ? 'true' : 'false' }}"
    data-has-start-content="{{ $hasStartContent ? 'true' : 'false' }}"
    data-has-end-content="{{ $hasEndContent ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-visible="true"
    @if($disabled) aria-disabled="true" @endif
    {{ $attributes->class("app-chip app-color-$color app-radius-$radius") }}
>
    @if($variant === 'dot')
        <span data-slot="dot" class="app-chip-dot" aria-hidden="true"></span>
    @endif

    @if($hasAvatar)
        <span data-slot="avatar" class="app-chip-avatar">{{ $avatar }}</span>
    @endif

    @if($hasStartContent)
        <span data-slot="start-content" class="app-chip-start-content">{{ $startContent }}</span>
    @endif

    <span data-slot="content" class="app-chip-content">{{ $slot }}</span>

    @if($isClosable)
        <button
            data-slot="close-button"
            data-chip-close
            type="button"
            class="app-chip-close"
            aria-label="칩 닫기"
            @disabled($disabled)
        >
            @if($hasEndContent)
                {{ $endContent }}
            @elseif(isset($closeIcon))
                {{ $closeIcon }}
            @else
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm3.54 12.12a1 1 0 0 1-1.42 1.42L12 13.42l-2.12 2.12a1 1 0 0 1-1.42-1.42L10.58 12 8.46 9.88a1 1 0 1 1 1.42-1.42L12 10.58l2.12-2.12a1 1 0 0 1 1.42 1.42L13.42 12l2.12 2.12Z" />
                </svg>
            @endif
        </button>
    @elseif($hasEndContent)
        <span data-slot="end-content" class="app-chip-end-content">{{ $endContent }}</span>
    @endif
</span>
