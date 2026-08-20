@props([
    'variant' => 'solid',
    'color' => 'default',
    'size' => 'md',
    'radius' => null,
    'href' => null,
    'external' => false,
    'showAnchorIcon' => false,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'iconOnly' => false,
    'fullWidth' => false,
    'spinnerPlacement' => 'start',
    'disableRipple' => false,
    'disableAnimation' => false,
])
@php
    $variant = in_array($variant, ['solid', 'bordered', 'light', 'flat', 'faded', 'shadow', 'ghost'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = $radius === null ? $size : (in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : $size);
    $spinnerPlacement = in_array($spinnerPlacement, ['start', 'end'], true) ? $spinnerPlacement : 'start';
    $tag = $href ? 'a' : 'button';
    $isDisabled = $disabled || $loading;
@endphp

<{{ $tag }}
    data-slot="base"
    data-ui-component="button"
    data-ui-interactive
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
    data-loading="{{ $loading ? 'true' : 'false' }}"
    data-icon-only="{{ $iconOnly ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-link="{{ $href ? 'true' : 'false' }}"
    data-external="{{ $external ? 'true' : 'false' }}"
    data-disable-ripple="{{ $disableRipple ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @if($href)
        href="{{ $href }}"
        @if($isDisabled) aria-disabled="true" tabindex="-1" @endif
        @if($external) target="_blank" rel="noopener noreferrer" @endif
    @else
        type="{{ $type }}"
        @disabled($isDisabled)
    @endif
    @if($loading) aria-busy="true" @endif
    {{ $attributes->class("app-button app-color-$color app-size-$size app-radius-$radius") }}
>
    @isset($startContent)<span data-slot="start-content">{{ $startContent }}</span>@endisset

    @if($loading && $spinnerPlacement === 'start')
        @isset($spinner)
            <span data-slot="spinner">{{ $spinner }}</span>
        @else
            <span data-slot="spinner" class="app-button-spinner" aria-hidden="true"></span>
        @endisset
    @endif

    @unless($loading && $iconOnly)
        <span data-slot="content">{{ $slot }}</span>
    @endunless

    @if($loading && $spinnerPlacement === 'end')
        @isset($spinner)
            <span data-slot="spinner">{{ $spinner }}</span>
        @else
            <span data-slot="spinner" class="app-button-spinner" aria-hidden="true"></span>
        @endisset
    @endif

    @isset($endContent)<span data-slot="end-content">{{ $endContent }}</span>@endisset

    @if($href && ($showAnchorIcon || isset($anchorIcon)))
        <span data-slot="anchor-icon" class="app-button-anchor-icon" aria-hidden="true">
            @isset($anchorIcon)
                {{ $anchorIcon }}
            @else
                <x-icon name="solar:square-top-down-linear" />
            @endisset
        </span>
    @endif
</{{ $tag }}>
