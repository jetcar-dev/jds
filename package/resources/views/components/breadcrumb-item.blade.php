@props([
    'href' => null,
    'current' => false,
    'isCurrent' => false,
    'disabled' => false,
    'hideSeparator' => false,
    'separator' => null,
    'color' => null,
    'size' => null,
    'underline' => null,
    'itemKey' => null,
])
@php
    $current = $current || $isCurrent;
    $color = in_array($color, ['foreground', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : null;
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : null;
    $underline = in_array($underline, ['none', 'active', 'hover', 'focus', 'always'], true) ? $underline : null;
    $tag = $href && !$current ? 'a' : 'span';
@endphp

<li
    data-slot="base"
    data-current="{{ $current ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-hide-separator="{{ $hideSeparator ? 'true' : 'false' }}"
    data-custom-separator="{{ $separator !== null ? 'true' : 'false' }}"
    @if($color) data-color="{{ $color }}" @endif
    @if($size) data-size="{{ $size }}" @endif
    @if($underline) data-underline="{{ $underline }}" @endif
    @if($itemKey !== null) data-key="{{ $itemKey }}" @endif
    {{ $attributes->class('app-breadcrumb-item') }}
>
    <{{ $tag }}
        data-slot="item"
        class="app-breadcrumb-link"
        @if($tag === 'a') href="{{ $href }}" @endif
        @if(!$current && !$disabled) tabindex="0" @endif
        @if($current) aria-current="page" @endif
        @if($disabled || $current) aria-disabled="true" @endif
        @if($tag === 'span') role="link" @endif
    >
        @isset($startContent)<span data-slot="start-content" class="app-breadcrumb-content">{{ $startContent }}</span>@endisset
        {{ $slot }}
        @isset($endContent)<span data-slot="end-content" class="app-breadcrumb-content">{{ $endContent }}</span>@endisset
    </{{ $tag }}>

    <span data-slot="separator" class="app-breadcrumb-separator" aria-hidden="true">
        @if($separator !== null)
            {{ $separator }}
        @else
            <svg
                data-breadcrumb-default-separator
                aria-hidden="true"
                focusable="false"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            ><path d="m9 18 6-6-6-6" /></svg>
        @endif
    </span>
</li>
