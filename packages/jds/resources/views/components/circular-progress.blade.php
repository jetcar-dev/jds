@props([
    'value' => null,
    'valueLabel' => null,
    'minValue' => 0,
    'maxValue' => 100,
    'formatOptions' => ['style' => 'percent'],
    'locale' => null,
    'indeterminate' => null,
    'showValueLabel' => false,
    'strokeWidth' => null,
    'size' => 'md',
    'color' => 'primary',
    'label' => null,
    'disabled' => false,
    'disableAnimation' => false,
])

@php
    $minimum = (float) $minValue;
    $maximum = (float) $maxValue;
    $current = $value === null ? null : (float) $value;
    $isIndeterminate = $indeterminate === null ? $current === null : filter_var($indeterminate, FILTER_VALIDATE_BOOL);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
    $animationDisabled = filter_var($disableAnimation, FILTER_VALIDATE_BOOL);
    $showsValue = filter_var($showValueLabel, FILTER_VALIDATE_BOOL);
    $lineWidth = $strokeWidth === null ? ($size === 'sm' ? 2 : 3) : max(1, min(8, (float) $strokeWidth));
    $radius = 16 - $lineWidth;
    $circumference = 2 * $radius * pi();
    $range = $maximum - $minimum;
    $percentage = $isIndeterminate ? .25 : ($range > 0 ? max(0, min(1, (($current ?? $minimum) - $minimum) / $range)) : 0);
    $dashOffset = $circumference * (1 - $percentage);
    $options = is_array($formatOptions) ? $formatOptions : ['style' => 'percent'];
    $formatStyle = $options['style'] ?? 'percent';
    $fallbackValueText = $valueLabel ?? ($formatStyle === 'percent'
        ? round($percentage * 100) . '%'
        : trim(($current ?? $minimum) . (isset($options['unit']) ? ' ' . $options['unit'] : '')));
    $visibleLabel = $label ?? (trim((string) $slot) !== '' ? trim((string) $slot) : null);
    $rootAttributes = $attributes->except('style');
    $rootStyle = '--circular-progress-circumference:' . round($circumference, 4)
        . ';--circular-progress-offset:' . round($dashOffset, 4)
        . ($attributes->get('style') ? ';' . $attributes->get('style') : '');
@endphp

<div
    data-ui-component="circular-progress"
    data-slot="base"
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    data-indeterminate="{{ $isIndeterminate ? 'true' : 'false' }}"
    data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
    data-disable-animation="{{ $animationDisabled ? 'true' : 'false' }}"
    data-min-value="{{ $minimum }}"
    data-max-value="{{ $maximum }}"
    @if($current !== null) data-value="{{ $current }}" @endif
    @if($valueLabel !== null) data-value-label="{{ $valueLabel }}" @endif
    data-format-options="{{ json_encode($options, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    @if($locale) data-locale="{{ $locale }}" @endif
    role="progressbar"
    @if($visibleLabel && !$attributes->has('aria-label')) aria-label="{{ $visibleLabel }}" @endif
    @unless($isIndeterminate)
        aria-valuenow="{{ $current ?? $minimum }}"
        aria-valuemin="{{ $minimum }}"
        aria-valuemax="{{ $maximum }}"
        aria-valuetext="{{ $fallbackValueText }}"
    @endunless
    @if($isDisabled) aria-disabled="true" @endif
    style="{{ $rootStyle }}"
    {{ $rootAttributes->class(["app-circular-progress", "app-color-$color"]) }}
>
    <div data-slot="svgWrapper" class="app-circular-progress-svg-wrapper">
        <svg data-slot="svg" class="app-circular-progress-svg" viewBox="0 0 32 32" fill="none" aria-hidden="true">
            <circle data-slot="track" class="app-circular-progress-track" cx="16" cy="16" r="{{ $radius }}" stroke-width="{{ $lineWidth }}" stroke-dasharray="{{ round($circumference, 4) }}" stroke-dashoffset="0" />
            <circle data-slot="indicator" class="app-circular-progress-indicator" cx="16" cy="16" r="{{ $radius }}" stroke-width="{{ $lineWidth }}" stroke-dasharray="{{ round($circumference, 4) }}" stroke-dashoffset="{{ round($dashOffset, 4) }}" transform="rotate(-90 16 16)" />
        </svg>
        @if($showsValue)
            <span data-slot="value" class="app-circular-progress-value">{{ $fallbackValueText }}</span>
        @endif
    </div>
    @if($visibleLabel)
        <span data-slot="label" class="app-circular-progress-label">{{ $visibleLabel }}</span>
    @endif
</div>
