@props([
    'title' => null,
    'description' => null,
    'color' => 'default',
    'variant' => 'flat',
    'radius' => 'md',
    'visible' => true,
    'closable' => false,
    'hideIcon' => false,
    'hideIconWrapper' => false,
])
@php
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $variant = in_array($variant, ['solid', 'bordered', 'flat', 'faded'], true) ? $variant : 'flat';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $hasTitle = filled($title);
    $hasDescription = filled($description);
    $hasContent = $hasDescription || trim((string) $slot) !== '';
    $defaultIcon = match ($color) {
        'success' => 'check-circle-linear',
        'warning', 'danger' => 'danger-circle-linear',
        default => 'info-circle-bold',
    };
@endphp
@if($visible)
<div
    data-slot="base"
    data-ui-component="alert"
    data-visible="true"
    data-closeable="{{ $closable ? 'true' : 'false' }}"
    data-has-title="{{ $hasTitle ? 'true' : 'false' }}"
    data-has-description="{{ $hasDescription ? 'true' : 'false' }}"
    data-has-content="{{ $hasContent ? 'true' : 'false' }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    role="alert"
    {{ $attributes->class("app-alert app-color-$color app-radius-$radius") }}
>
    @isset($startContent)<div data-slot="start-content">{{ $startContent }}</div>@endisset

    @unless($hideIcon)
        <div data-slot="icon-wrapper" data-hide-wrapper="{{ $hideIconWrapper ? 'true' : 'false' }}">
            @isset($icon)
                <span data-slot="alert-icon">{{ $icon }}</span>
            @else
                <span data-slot="alert-icon"><x-icon :name="$defaultIcon" /></span>
            @endisset
        </div>
    @endunless

    <div data-slot="main-wrapper">
        @if($hasTitle)<div data-slot="title">{{ $title }}</div>@endif
        @if($hasDescription)<div data-slot="description">{{ $description }}</div>@endif
        {{ $slot }}
    </div>

    @isset($endContent)<div data-slot="end-content">{{ $endContent }}</div>@endisset

    @if($closable)
        <button type="button" data-slot="close-button" data-alert-close data-ui-interactive aria-label="닫기">
            <x-icon name="solar:close-circle-linear" />
        </button>
    @endif
</div>
@endif
