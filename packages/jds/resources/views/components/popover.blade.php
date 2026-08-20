@props([
    'size' => 'md',
    'color' => 'default',
    'radius' => 'lg',
    'shadow' => 'md',
    'backdrop' => 'transparent',
    'placement' => 'bottom',
    'offset' => 7,
    'containerPadding' => 12,
    'crossOffset' => 0,
    'showArrow' => false,
    'shouldFlip' => true,
    'triggerScaleOnOpen' => true,
    'shouldBlockScroll' => false,
    'shouldCloseOnScroll' => true,
    'shouldCloseOnBlur' => false,
    'keyboardDismissDisabled' => false,
    'dismissable' => true,
    'disableAnimation' => false,
    'defaultOpen' => false,
    'disabled' => false,
    'portalTarget' => null,
])
@php
    $sizes = ['sm', 'md', 'lg'];
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger', 'foreground'];
    $radii = ['none', 'sm', 'md', 'lg', 'full'];
    $shadows = ['none', 'sm', 'md', 'lg'];
    $backdrops = ['transparent', 'opaque', 'blur'];
    $placements = ['top', 'top-start', 'top-end', 'bottom', 'bottom-start', 'bottom-end', 'left', 'left-start', 'left-end', 'right', 'right-start', 'right-end'];
    $size = in_array($size, $sizes, true) ? $size : 'md';
    $color = in_array($color, $colors, true) ? $color : 'default';
    $radius = in_array($radius, $radii, true) ? $radius : 'lg';
    $shadow = in_array($shadow, $shadows, true) ? $shadow : 'md';
    $backdrop = in_array($backdrop, $backdrops, true) ? $backdrop : 'transparent';
    $placement = in_array($placement, $placements, true) ? $placement : 'bottom';
@endphp

<div
    data-slot="popover"
    data-ui-component="popover"
    data-open="false"
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    data-radius="{{ $radius }}"
    data-shadow="{{ $shadow }}"
    data-backdrop="{{ $backdrop }}"
    data-placement="{{ $placement }}"
    data-offset="{{ (float) $offset }}"
    data-container-padding="{{ max(0, (float) $containerPadding) }}"
    data-cross-offset="{{ (float) $crossOffset }}"
    data-show-arrow="{{ $showArrow ? 'true' : 'false' }}"
    data-should-flip="{{ $shouldFlip ? 'true' : 'false' }}"
    data-trigger-scale-on-open="{{ $triggerScaleOnOpen ? 'true' : 'false' }}"
    data-should-block-scroll="{{ $shouldBlockScroll ? 'true' : 'false' }}"
    data-should-close-on-scroll="{{ $shouldCloseOnScroll ? 'true' : 'false' }}"
    data-should-close-on-blur="{{ $shouldCloseOnBlur ? 'true' : 'false' }}"
    data-keyboard-dismiss-disabled="{{ $keyboardDismissDisabled ? 'true' : 'false' }}"
    data-dismissable="{{ $dismissable ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-default-open="{{ $defaultOpen ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    @if($portalTarget) data-portal-target="{{ $portalTarget }}" @endif
    {{ $attributes->class(["app-popover", "app-color-$color", "app-radius-$radius"]) }}
>
    {{ $slot }}
    <div data-slot="backdrop" data-backdrop="{{ $backdrop }}" class="app-popover-backdrop" hidden></div>
</div>
