@props([
    'id' => null,
    'name' => null,
    'value' => null,
    'defaultValue' => null,
    'label' => null,
    'description' => null,
    'orientation' => 'vertical',
    'color' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'required' => false,
    'readonly' => false,
    'invalid' => false,
    'validationState' => null,
    'errorMessage' => null,
    'disableAnimation' => false,
])

@php
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger'];
    $sizes = ['sm', 'md', 'lg'];
    $orientation = in_array($orientation, ['vertical', 'horizontal'], true) ? $orientation : 'vertical';
    $color = in_array($color, $colors, true) ? $color : 'primary';
    $size = in_array($size, $sizes, true) ? $size : 'md';
    $isInvalid = (bool) $invalid || $validationState === 'invalid';
    $initialValue = $value ?? $defaultValue;
    $hasInitialValue = $value !== null || $defaultValue !== null;
@endphp

<fieldset
    @if($id) id="{{ $id }}" @endif
    data-slot="base"
    data-ui-component="radio-group"
    data-orientation="{{ $orientation }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-readonly="{{ $readonly ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-invalid="{{ $isInvalid ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    @if($name) data-name="{{ $name }}" @endif
    @if($hasInitialValue) data-initial-value="{{ $initialValue }}" @endif
    @if($required) aria-required="true" @endif
    @if($isInvalid) aria-invalid="true" @endif
    @if($disabled) aria-disabled="true" @endif
    @if($readonly) aria-readonly="true" @endif
    {{ $attributes->class("app-radio-group app-color-$color") }}
>
    @if($label !== null)
        <legend data-slot="label" class="app-radio-group-label">{{ $label }}</legend>
    @endif

    @if($description !== null)
        <div data-slot="description" class="app-radio-group-description">{{ $description }}</div>
    @endif

    <div data-slot="wrapper" class="app-radio-group-wrapper">
        {{ $slot }}
    </div>

    @if($errorMessage !== null)
        <div data-slot="error-message" class="app-radio-group-error" @if(!$isInvalid) hidden @endif>{{ $errorMessage }}</div>
    @endif
</fieldset>
