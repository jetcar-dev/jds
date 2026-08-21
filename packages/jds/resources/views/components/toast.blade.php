@props([
    'title' => null,
    'description' => null,
    'color' => 'default',
    'severity' => 'default',
    'variant' => 'flat',
    'size' => 'md',
    'radius' => 'md',
    'shadow' => 'sm',
    'timeout' => 6000,
    'placement' => 'bottom-right',
    'hideIcon' => false,
    'hideCloseButton' => false,
    'shouldShowTimeoutProgress' => false,
    'disableAnimation' => false,
    'closable' => null,
])
@php
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger'];
    $color = in_array($color, $colors, true) ? $color : 'default';
    $severity = in_array($severity, $colors, true) ? $severity : $color;
    $variant = in_array($variant, ['flat', 'solid', 'bordered'], true) ? $variant : 'flat';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $shadow = in_array($shadow, ['none', 'sm', 'md', 'lg'], true) ? $shadow : 'sm';
    $placement = in_array($placement, ['top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right'], true) ? $placement : 'bottom-right';
    if ($closable !== null) $hideCloseButton = !$closable;
    $iconNames = [
        'default' => 'info-circle-bold',
        'primary' => 'info-circle-bold',
        'secondary' => 'info-circle-bold',
        'success' => 'check-circle-bold',
        'warning' => 'danger-triangle-bold',
        'danger' => 'close-circle-bold',
    ];
    $hasTitle = filled($title);
    $body = $description ?? trim((string) $slot);
    $hasDescription = filled($body);
@endphp

<div
    data-slot="toast"
    data-ui-component="toast"
    role="alert"
    data-color="{{ $color }}"
    data-severity="{{ $severity }}"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-shadow="{{ $shadow }}"
    data-timeout="{{ max(0, (int) $timeout) }}"
    data-placement="{{ $placement }}"
    data-has-title="{{ $hasTitle ? 'true' : 'false' }}"
    data-has-description="{{ $hasDescription ? 'true' : 'false' }}"
    data-hide-icon="{{ $hideIcon ? 'true' : 'false' }}"
    data-hide-close-button="{{ $hideCloseButton ? 'true' : 'false' }}"
    data-show-timeout-progress="{{ $shouldShowTimeoutProgress ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-animation="entering"
    data-drag-value="0"
    {{ $attributes->class("app-toast app-color-$color app-radius-$radius") }}
>
    <div data-slot="content" class="app-toast-content">
        @unless($hideIcon)
            <span data-slot="icon" class="app-toast-icon">
                {{ $icon ?? '' }}
                @if(!isset($icon))<x-icon :name="$iconNames[$severity]" />@endif
            </span>
        @endunless

        <div data-slot="wrapper" class="app-toast-wrapper">
            @if($hasTitle)<div data-slot="title" class="app-toast-title">{{ $title }}</div>@endif
            @if($hasDescription)<div data-slot="description" class="app-toast-description">{{ $body }}</div>@endif
        </div>

        @isset($endContent)<div data-slot="end-content" class="app-toast-end-content">{{ $endContent }}</div>@endisset
        @isset($loadingComponent)<div data-slot="loading-component" class="app-toast-loading" hidden>{{ $loadingComponent }}</div>@endisset
    </div>

    @unless($hideCloseButton)
        <button type="button" data-slot="close-button" class="app-toast-close" data-toast-close aria-label="닫기">
            <span data-slot="close-icon" class="app-toast-close-icon">
                {{ $closeIcon ?? '' }}
                @if(!isset($closeIcon))<x-icon name="close-circle-bold" />@endif
            </span>
        </button>
    @endunless

    @if($shouldShowTimeoutProgress)
        <span data-slot="progress-track" class="app-toast-progress-track" aria-hidden="true">
            <span data-slot="progress-indicator" class="app-toast-progress-indicator"></span>
        </span>
    @endif
</div>
