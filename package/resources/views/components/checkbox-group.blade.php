@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'value' => null,
    'defaultValue' => null,
    'orientation' => 'vertical',
    'color' => 'primary',
    'size' => 'md',
    'radius' => 'md',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'invalid' => false,
    'validationState' => null,
    'description' => null,
    'errorMessage' => null,
    'lineThrough' => false,
    'disableAnimation' => false,
])

@php
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger'];
    $sizes = ['sm', 'md', 'lg'];
    $radii = ['none', 'sm', 'md', 'lg', 'full'];
    $orientation = in_array($orientation, ['vertical', 'horizontal'], true) ? $orientation : 'vertical';
    $color = in_array($color, $colors, true) ? $color : 'primary';
    $size = in_array($size, $sizes, true) ? $size : 'md';
    $radius = in_array($radius, $radii, true) ? $radius : 'md';
    $isInvalid = (bool) $invalid || $validationState === 'invalid';
    $initialValue = $value ?? $defaultValue;
    $hasInitialValue = $value !== null || $defaultValue !== null;
    $initialValue = is_array($initialValue) ? array_values(array_map('strval', $initialValue)) : [];
@endphp

<fieldset
    @if($id) id="{{ $id }}" @endif
    data-slot="base"
    data-ui-component="checkbox-group"
    data-orientation="{{ $orientation }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-readonly="{{ $readonly ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-invalid="{{ $isInvalid ? 'true' : 'false' }}"
    data-line-through="{{ $lineThrough ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @if($name) data-name="{{ $name }}" @endif
    @if($hasInitialValue) data-initial-value="{{ json_encode($initialValue) }}" @endif
    @if($isInvalid) aria-invalid="true" @endif
    @if($disabled) aria-disabled="true" @endif
    @if($readonly) aria-readonly="true" @endif
    {{ $attributes->class("app-checkbox-group app-color-$color") }}
>
    @if($label !== null)
        <legend data-slot="label" class="app-checkbox-group-label">{{ $label }}</legend>
    @endif

    @if($description !== null)
        <div data-slot="description" class="app-checkbox-group-description">{{ $description }}</div>
    @endif

    <div data-slot="wrapper" class="app-checkbox-group-wrapper">
        {{ $slot }}
    </div>

    @if($errorMessage !== null)
        <div data-slot="error-message" class="app-checkbox-group-error" @if(!$isInvalid) hidden @endif>{{ $errorMessage }}</div>
    @endif
</fieldset>
