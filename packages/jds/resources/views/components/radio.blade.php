@props([
    'id' => null,
    'name' => null,
    'value',
    'label' => null,
    'description' => null,
    'checked' => false,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'invalid' => false,
    'color' => 'primary',
    'size' => 'md',
    'disableAnimation' => false,
])

@php
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger'];
    $sizes = ['sm', 'md', 'lg'];
    $color = in_array($color, $colors, true) ? $color : 'primary';
    $size = in_array($size, $sizes, true) ? $size : 'md';
@endphp

<label
    data-slot="base"
    data-ui-component="radio"
    data-value="{{ $value }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-selected="{{ $checked ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-readonly="{{ $readonly ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @if($disabled) aria-disabled="true" @endif
    @if($readonly) aria-readonly="true" @endif
    {{ $attributes->class("app-radio-base app-color-$color") }}
>
    <input
        data-slot="hidden-input"
        class="app-radio-input"
        type="radio"
        @if($id) id="{{ $id }}" @endif
        @if($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        @checked($checked)
        @required($required)
        @disabled($disabled)
        @if($invalid) aria-invalid="true" @endif
        @if($readonly) aria-readonly="true" @endif
    >

    <span data-slot="wrapper" class="app-radio-wrapper" aria-hidden="true">
        <span data-slot="control" class="app-radio-control"></span>
    </span>

    @if($label !== null || !$slot->isEmpty() || $description !== null)
        <span data-slot="label-wrapper" class="app-radio-label-wrapper">
            @if($label !== null || !$slot->isEmpty())
                <span data-slot="label" class="app-radio-label">{{ $label ?? $slot }}</span>
            @endif
            @if($description !== null)
                <span data-slot="description" class="app-radio-description">{{ $description }}</span>
            @endif
        </span>
    @endif
</label>
