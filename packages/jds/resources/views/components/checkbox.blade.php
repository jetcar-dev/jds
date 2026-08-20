@props([
    'id' => null,
    'name' => null,
    'value' => 'on',
    'checked' => false,
    'indeterminate' => false,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'invalid' => false,
    'loading' => false,
    'color' => 'primary',
    'size' => 'md',
    'radius' => null,
    'lineThrough' => false,
    'disableAnimation' => false,
    'label' => null,
])

@php
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger'];
    $sizes = ['sm', 'md', 'lg'];
    $radii = ['none', 'sm', 'md', 'lg', 'full'];
    $color = in_array($color, $colors, true) ? $color : 'primary';
    $size = in_array($size, $sizes, true) ? $size : 'md';
    $radius = in_array($radius, $radii, true) ? $radius : null;
    $selected = (bool) $checked || (bool) $indeterminate;
    $inputId = $id;
    $isDisabled = (bool) $disabled || (bool) $loading;
@endphp

<label
    data-slot="base"
    data-ui-component="checkbox"
    data-value="{{ $value }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    @if($radius) data-radius="{{ $radius }}" @endif
    data-selected="{{ $selected ? 'true' : 'false' }}"
    data-indeterminate="{{ $indeterminate ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-readonly="{{ $readonly ? 'true' : 'false' }}"
    data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
    data-loading="{{ $loading ? 'true' : 'false' }}"
    data-line-through="{{ $lineThrough ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @if($isDisabled) aria-disabled="true" @endif
    @if($readonly) aria-readonly="true" @endif
    {{ $attributes->class("app-checkbox-base app-color-$color") }}
>
    <input
        data-slot="hidden-input"
        class="app-checkbox-input"
        type="checkbox"
        @if($inputId) id="{{ $inputId }}" @endif
        @if($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        @checked($checked)
        @required($required)
        @disabled($isDisabled)
        @if($invalid) aria-invalid="true" @endif
        @if($readonly) aria-readonly="true" @endif
    >

    <span data-slot="wrapper" class="app-checkbox-wrapper" aria-hidden="true">
        <span data-slot="icon" class="app-checkbox-icon">
            @isset($icon)
                {{ $icon }}
            @else
                <svg viewBox="0 0 17 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline class="app-checkbox-check" points="1 9 7 14 15 4" />
                    <line class="app-checkbox-indeterminate" x1="3" y1="9" x2="14" y2="9" />
                </svg>
            @endisset
        </span>
    </span>

    @if($label !== null || !$slot->isEmpty())
        <span data-slot="label" class="app-checkbox-label">{{ $label ?? $slot }}</span>
    @endif
</label>
