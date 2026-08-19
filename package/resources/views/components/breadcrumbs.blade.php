@props([
    'variant' => 'light',
    'color' => 'foreground',
    'size' => 'md',
    'radius' => null,
    'underline' => 'none',
    'separator' => null,
    'maxItems' => null,
    'itemsBeforeCollapse' => 1,
    'itemsAfterCollapse' => 1,
    'hideSeparator' => false,
    'disabled' => false,
    'disableAnimation' => false,
    'ariaLabel' => 'Breadcrumbs',
])
@php
    $variant = in_array($variant, ['solid', 'bordered', 'light'], true) ? $variant : 'light';
    $color = in_array($color, ['foreground', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'foreground';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = $radius === null ? 'sm' : (in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'sm');
    $underline = in_array($underline, ['none', 'active', 'hover', 'focus', 'always'], true) ? $underline : 'none';
    $maxItems = is_numeric($maxItems) ? max(1, (int) $maxItems) : null;
@endphp

<nav
    aria-label="{{ $ariaLabel }}"
    data-slot="base"
    data-ui-component="breadcrumbs"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-underline="{{ $underline }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-hide-separator="{{ $hideSeparator ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @if($maxItems) data-max-items="{{ $maxItems }}" @endif
    data-items-before-collapse="{{ max(0, (int) $itemsBeforeCollapse) }}"
    data-items-after-collapse="{{ max(0, (int) $itemsAfterCollapse) }}"
    {{ $attributes->class("app-breadcrumbs-base app-radius-$radius") }}
>
    <ol data-slot="list" class="app-breadcrumbs">{{ $slot }}</ol>

    @if($separator !== null || isset($separatorContent))
        <template data-breadcrumb-separator-template>
            @if(isset($separatorContent)){{ $separatorContent }}@else{{ $separator }}@endif
        </template>
    @endif

    <template data-breadcrumb-ellipsis-template>
        @isset($ellipsis)
            {{ $ellipsis }}
        @else
            <svg
                data-breadcrumb-default-ellipsis
                aria-hidden="true"
                focusable="false"
                viewBox="0 0 24 24"
                fill="currentColor"
            >
                <circle cx="5" cy="12" r="1.75" />
                <circle cx="12" cy="12" r="1.75" />
                <circle cx="19" cy="12" r="1.75" />
            </svg>
        @endisset
    </template>
</nav>
