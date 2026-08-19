@props([
    'name' => null,
    'value' => null,
    'defaultValue' => null,
    'placeholderValue' => null,
    'label' => null,
    'ariaLabel' => null,
    'description' => null,
    'errorMessage' => null,
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => null,
    'labelPlacement' => null,
    'locale' => 'ko-KR',
    'granularity' => 'day',
    'hourCycle' => null,
    'timeZone' => null,
    'hideTimeZone' => false,
    'minValue' => null,
    'maxValue' => null,
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    'invalid' => false,
    'clearable' => false,
    'autoFocus' => false,
    'disableAnimation' => false,
    'shouldForceLeadingZeros' => true,
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'underlined', 'faded'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : $size;
    $labelPlacement = in_array($labelPlacement, ['inside', 'outside', 'outside-left', 'outside-top'], true)
        ? $labelPlacement
        : ($label ? 'inside' : 'outside');
    $granularity = in_array($granularity, ['day', 'hour', 'minute', 'second'], true) ? $granularity : 'day';
    $hourCycle = in_array((int) $hourCycle, [12, 24], true) ? (int) $hourCycle : null;
    $selectedValue = $value ?? $defaultValue ?? '';
    $uid = 'date-input-' . uniqid();
    $labelId = $uid . '-label';
    $descriptionId = $uid . '-description';
    $errorId = $uid . '-error';
    $hasHelper = (bool) $description || (bool) $errorMessage;
    $hasStartContent = isset($startContent);
    $hasEndContent = isset($endContent);
    $config = [
        'value' => (string) $selectedValue,
        'placeholderValue' => $placeholderValue,
        'locale' => $locale,
        'granularity' => $granularity,
        'hourCycle' => $hourCycle,
        'timeZone' => $timeZone,
        'hideTimeZone' => (bool) $hideTimeZone,
        'minValue' => $minValue,
        'maxValue' => $maxValue,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readOnly,
        'required' => (bool) $required,
        'invalid' => (bool) $invalid,
        'autoFocus' => (bool) $autoFocus,
        'disableAnimation' => (bool) $disableAnimation,
        'shouldForceLeadingZeros' => (bool) $shouldForceLeadingZeros,
        'label' => $ariaLabel ?: $label ?: '날짜',
        'descriptionId' => $description ? $descriptionId : null,
        'errorId' => $errorMessage ? $errorId : null,
    ];
    $describedBy = collect([
        $description ? $descriptionId : null,
        $errorMessage ? $errorId : null,
    ])->filter()->implode(' ');
@endphp

<div
    data-slot="base"
    data-ui-component="date-input"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-label-placement="{{ $labelPlacement }}"
    data-has-label="{{ $label ? 'true' : 'false' }}"
    data-has-helper="{{ $hasHelper ? 'true' : 'false' }}"
    data-has-start-content="{{ $hasStartContent ? 'true' : 'false' }}"
    data-has-end-content="{{ $hasEndContent ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-focus="false"
    data-focus-visible="false"
    data-hover="false"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-date-input-config='@json($config)'
    {{ $attributes->class("app-date-input-base app-color-$color app-size-$size app-radius-$radius") }}
>
    @if($label && $labelPlacement !== 'inside')
        <span id="{{ $labelId }}" data-slot="label" class="app-date-input-label app-date-input-label-outside">{{ $label }}</span>
    @endif

    <div data-slot="input-wrapper" class="app-date-input-wrapper">
        @if($label && $labelPlacement === 'inside')
            <span id="{{ $labelId }}" data-slot="label" class="app-date-input-label app-date-input-label-inside">{{ $label }}</span>
        @endif

        <div data-slot="inner-wrapper" class="app-date-input-inner">
            @isset($startContent)
                <span data-slot="start-content" class="app-date-input-content app-date-input-start">{{ $startContent }}</span>
            @endisset

            <div
                id="{{ $uid }}"
                data-slot="input-field"
                data-date-field
                class="app-date-input-field"
                role="group"
                @if($label) aria-labelledby="{{ $labelId }}" @else aria-label="{{ $ariaLabel ?: '날짜' }}" @endif
                @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
                @if($disabled) aria-disabled="true" @endif
                @if($readOnly) aria-readonly="true" @endif
                @if($required) aria-required="true" @endif
                @if($invalid) aria-invalid="true" @endif
            ></div>

            @isset($endContent)
                <span data-slot="end-content" class="app-date-input-content app-date-input-end">{{ $endContent }}</span>
            @endisset

            @if($clearable)
                <button type="button" data-slot="clear-button" data-date-clear class="app-date-input-clear" aria-label="날짜 지우기" @disabled($disabled || $readOnly)>
                    <svg aria-hidden="true" viewBox="0 0 24 24"><path d="m7 7 10 10M17 7 7 17" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.7"/></svg>
                </button>
            @endif
        </div>
    </div>

    @if($hasHelper)
        <div data-slot="helper-wrapper" class="app-date-input-helper">
            @if($description)
                <span id="{{ $descriptionId }}" data-slot="description" class="app-date-input-description">{{ $description }}</span>
            @endif
            @if($errorMessage)
                <span id="{{ $errorId }}" data-slot="error-message" class="app-date-input-error" role="alert">{{ $errorMessage }}</span>
            @endif
        </div>
    @endif

    <input
        hidden
        type="text"
        data-slot="input"
        data-date-value
        @if($name) name="{{ $name }}" @endif
        value="{{ $selectedValue }}"
        @disabled($disabled)
        @readonly($readOnly)
        @required($required)
        tabindex="-1"
    >
</div>
