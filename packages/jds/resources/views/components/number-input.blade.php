@props([
    'id' => null,
    'name' => null,
    'value' => null,
    'defaultValue' => null,
    'min' => null,
    'max' => null,
    'minValue' => null,
    'maxValue' => null,
    'step' => 1,
    'label' => null,
    'placeholder' => null,
    'description' => null,
    'errorMessage' => null,
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'md',
    'labelPlacement' => 'inside',
    'formatOptions' => [],
    'locale' => null,
    'hideStepper' => false,
    'wheelDisabled' => false,
    'clearable' => false,
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    'invalid' => false,
    'fullWidth' => true,
    'autoFocus' => false,
    'disableAnimation' => false,
    'incrementAriaLabel' => '증가',
    'decrementAriaLabel' => '감소',
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'faded', 'underlined'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $labelPlacement = in_array($labelPlacement, ['inside', 'outside', 'outside-left', 'outside-top'], true) ? $labelPlacement : 'inside';
    if (!$label && $labelPlacement === 'inside') $labelPlacement = 'outside';
    $id = $id ?: 'number-input-'.uniqid();
    $value = $value ?? $defaultValue;
    $min = $minValue ?? $min;
    $max = $maxValue ?? $max;
    $formatOptions = is_array($formatOptions) ? $formatOptions : [];
    $locale = $locale ?: str_replace('_', '-', app()->getLocale());
    $labelId = $id.'-label';
    $descriptionId = $id.'-description';
    $errorId = $id.'-error';
    $hasStartContent = isset($startContent);
    $hasEndContent = isset($endContent);
    $hasHelper = (bool) $description || (bool) $errorMessage;
    $hasLabel = (bool) $label;
    $hasValue = $value !== null && $value !== '';
    $filled = $hasValue || (bool) $placeholder || $hasStartContent;
    $fieldAttributes = $attributes->except(['class', 'inputmode']);
@endphp

<div
    data-slot="base"
    data-ui-component="number-input"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-label-placement="{{ $labelPlacement }}"
    data-locale="{{ $locale }}"
    data-format-options="{{ json_encode($formatOptions, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    data-filled="{{ $filled ? 'true' : 'false' }}"
    data-filled-within="{{ $filled ? 'true' : 'false' }}"
    data-has-value="{{ $hasValue ? 'true' : 'false' }}"
    data-has-label="{{ $hasLabel ? 'true' : 'false' }}"
    data-has-helper="{{ $hasHelper ? 'true' : 'false' }}"
    data-has-elements="{{ ($hasLabel || $hasHelper) ? 'true' : 'false' }}"
    data-has-start-content="{{ $hasStartContent ? 'true' : 'false' }}"
    data-has-end-content="{{ $hasEndContent ? 'true' : 'false' }}"
    data-hide-stepper="{{ $hideStepper ? 'true' : 'false' }}"
    data-wheel-disabled="{{ $wheelDisabled ? 'true' : 'false' }}"
    data-clearable="{{ $clearable ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    class="app-number-input app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }} {{ $attributes->get('class') }}"
>
    @if($label && in_array($labelPlacement, ['outside', 'outside-left', 'outside-top'], true))
        <label id="{{ $labelId }}" data-slot="label" for="{{ $id }}" class="app-number-input-label">{{ $label }}</label>
    @endif

    <div data-slot="main-wrapper" class="app-number-input-main">
        <div data-slot="input-wrapper" class="app-number-input-wrapper">
            @if($label && $labelPlacement === 'inside')
                <label id="{{ $labelId }}" data-slot="label" for="{{ $id }}" class="app-number-input-label">{{ $label }}</label>
            @endif

            <div data-slot="inner-wrapper" class="app-number-input-inner">
                @isset($startContent)<span data-slot="start-content" class="app-number-input-content">{{ $startContent }}</span>@endisset

                <input
                    data-slot="input"
                    data-has-start-content="{{ $hasStartContent ? 'true' : 'false' }}"
                    data-has-end-content="{{ $hasEndContent ? 'true' : 'false' }}"
                    id="{{ $id }}"
                    class="app-number-input-control"
                    type="text"
                    inputmode="{{ $attributes->get('inputmode', 'decimal') }}"
                    value="{{ $value }}"
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    role="spinbutton"
                    @if($min !== null) aria-valuemin="{{ $min }}" @endif
                    @if($max !== null) aria-valuemax="{{ $max }}" @endif
                    @if($hasValue) aria-valuenow="{{ $value }}" @endif
                    @if($label) aria-labelledby="{{ $labelId }}" @endif
                    @if($description && !$invalid) aria-describedby="{{ $descriptionId }}" @endif
                    @if($errorMessage && $invalid) aria-errormessage="{{ $errorId }}" @endif
                    @if($invalid) aria-invalid="true" @endif
                    aria-required="{{ $required ? 'true' : 'false' }}"
                    aria-readonly="{{ $readOnly ? 'true' : 'false' }}"
                    @disabled($disabled)
                    @readonly($readOnly)
                    @required($required)
                    @if($autoFocus) autofocus @endif
                    {{ $fieldAttributes }}
                >

                <input
                    data-slot="hidden-input"
                    data-number-hidden
                    type="hidden"
                    @if($name) name="{{ $name }}" @endif
                    value="{{ $value }}"
                    data-min="{{ $min }}"
                    data-max="{{ $max }}"
                    data-step="{{ $step }}"
                    @disabled($disabled)
                >

                @if($clearable)
                    <button type="button" tabindex="-1" data-slot="clear-button" data-number-clear class="app-number-input-clear" aria-label="입력값 지우기">
                        <x-icon name="solar:close-circle-bold" />
                    </button>
                @endif

                @isset($endContent)<span data-slot="end-content" class="app-number-input-content">{{ $endContent }}</span>@endisset

                @if(!$hideStepper)
                    <span data-slot="stepper-wrapper" class="app-number-input-steppers">
                        <button type="button" tabindex="-1" data-slot="increase-button" data-number-increase class="app-number-input-stepper" aria-label="{{ $incrementAriaLabel }}">
                            <x-icon name="solar:alt-arrow-up-linear" />
                        </button>
                        <button type="button" tabindex="-1" data-slot="decrease-button" data-number-decrease class="app-number-input-stepper" aria-label="{{ $decrementAriaLabel }}">
                            <x-icon name="solar:alt-arrow-down-linear" />
                        </button>
                    </span>
                @endif
            </div>
        </div>

        @if($hasHelper)
            <div data-slot="helper-wrapper" class="app-number-input-helper">
                @if($description)<div id="{{ $descriptionId }}" data-slot="description" class="app-number-input-description" @if($invalid) hidden @endif>{{ $description }}</div>@endif
                @if($errorMessage)<div id="{{ $errorId }}" data-slot="error-message" class="app-number-input-error" @if(!$invalid) hidden @endif>{{ $errorMessage }}</div>@endif
            </div>
        @endif
    </div>
</div>
