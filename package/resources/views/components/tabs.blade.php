@props([
    'name' => null,
    'value' => null,
    'selectedKey' => null,
    'defaultSelectedKey' => null,
    'variant' => 'solid',
    'color' => 'default',
    'size' => 'md',
    'radius' => null,
    'placement' => 'top',
    'isVertical' => false,
    'orientation' => null,
    'keyboardActivation' => 'automatic',
    'shouldSelectOnPressUp' => true,
    'fullWidth' => false,
    'disabled' => false,
    'disabledKeys' => [],
    'disableCursorAnimation' => false,
    'disableAnimation' => false,
    'destroyInactiveTabPanel' => true,
])
@php
    $variant = in_array($variant, ['solid', 'bordered', 'light', 'underlined'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = $radius === null || in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : null;
    $placement = in_array($placement, ['top', 'bottom', 'start', 'end'], true) ? $placement : 'top';
    $keyboardActivation = in_array($keyboardActivation, ['automatic', 'manual'], true) ? $keyboardActivation : 'automatic';
    $vertical = $isVertical || $orientation === 'vertical' || in_array($placement, ['start', 'end'], true);
    $initialValue = $selectedKey ?? $value ?? $defaultSelectedKey;
    $tabsId = $attributes->get('id') ?? uniqid('tabs-');
@endphp

<div
    id="{{ $tabsId }}"
    data-slot="tabWrapper"
    data-ui-component="tabs"
    data-value="{{ $initialValue }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius ?? 'auto' }}"
    data-placement="{{ $placement }}"
    data-orientation="{{ $vertical ? 'vertical' : 'horizontal' }}"
    data-keyboard-activation="{{ $keyboardActivation }}"
    data-should-select-on-press-up="{{ $shouldSelectOnPressUp ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-disabled-keys="{{ json_encode($disabledKeys, JSON_UNESCAPED_UNICODE) }}"
    data-disable-cursor-animation="{{ $disableCursorAnimation ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-destroy-inactive-tab-panel="{{ $destroyInactiveTabPanel ? 'true' : 'false' }}"
    {{ $attributes->except('id')->class("app-tabs app-color-$color") }}
>
    @if($name)
        <input type="hidden" data-tabs-input name="{{ $name }}" value="{{ $initialValue }}">
    @endif
    {{ $slot }}
</div>
