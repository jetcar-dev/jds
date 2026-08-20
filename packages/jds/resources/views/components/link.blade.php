@props([
    'href' => '#',
    'color' => 'primary',
    'size' => 'md',
    'underline' => 'none',
    'external' => false,
    'showAnchorIcon' => false,
    'block' => false,
    'disabled' => false,
    'disableAnimation' => false,
])
@php
    $color = in_array($color, ['foreground', 'primary', 'secondary', 'success', 'warning', 'danger'], true)
        ? $color
        : 'primary';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $underline = in_array($underline, ['none', 'hover', 'always', 'active', 'focus'], true)
        ? $underline
        : 'none';
@endphp

<a
    data-ui-component="link"
    data-ui-interactive
    data-slot="base"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-underline="{{ $underline }}"
    data-external="{{ $external ? 'true' : 'false' }}"
    data-block="{{ $block ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @unless($disabled) href="{{ $href }}" @endunless
    @if($disabled) aria-disabled="true" tabindex="-1" @endif
    @if($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class(['app-link', 'app-color-'.$color => $color !== 'foreground']) }}
>
    {{ $slot }}

    @if($showAnchorIcon || isset($anchorIcon))
        <span data-slot="anchor-icon" class="app-link-anchor-icon" aria-hidden="true">
            @isset($anchorIcon)
                {{ $anchorIcon }}
            @else
                <x-icon name="solar:square-top-down-linear" />
            @endisset
        </span>
    @endif
</a>
