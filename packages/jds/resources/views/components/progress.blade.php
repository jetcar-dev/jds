@props([
    'value' => 0,
    'valueLabel' => null,
    'minValue' => 0,
    'maxValue' => 100,
    'formatOptions' => ['style' => 'percent'],
    'locale' => null,
    'label' => null,
    'showValueLabel' => false,
    'size' => 'md',
    'color' => 'primary',
    'radius' => 'full',
    'indeterminate' => false,
    'striped' => false,
    'disabled' => false,
    'disableAnimation' => false,
])

@php
    $minimum = (float) $minValue;
    $maximum = (float) $maxValue;
    $current = (float) $value;
    $range = $maximum - $minimum;
    $percentage = $range > 0 ? max(0, min(100, (($current - $minimum) / $range) * 100)) : 0;
    $isIndeterminate = filter_var($indeterminate, FILTER_VALIDATE_BOOL);
    $isStriped = filter_var($striped, FILTER_VALIDATE_BOOL);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
    $animationDisabled = filter_var($disableAnimation, FILTER_VALIDATE_BOOL);
    $showsValue = filter_var($showValueLabel, FILTER_VALIDATE_BOOL);
    $options = is_array($formatOptions) ? $formatOptions : ['style' => 'percent'];
    $formatStyle = $options['style'] ?? 'percent';
    $fallbackValueText = $valueLabel ?? match ($formatStyle) {
        'currency' => ($options['currency'] ?? '') . ' ' . number_format($current, $options['maximumFractionDigits'] ?? 2),
        'unit' => trim(number_format($current, $options['maximumFractionDigits'] ?? 0) . ' ' . ($options['unit'] ?? '')),
        default => round($percentage) . '%',
    };
    $visibleLabel = $label ?? (trim((string) $slot) !== '' ? trim((string) $slot) : null);
    $rootAttributes = $attributes->except('style');
    $rootStyle = '--progress-translate:-100%'
        . ($attributes->get('style') ? ';' . $attributes->get('style') : '');
@endphp

<div
    data-ui-component="progress"
    data-slot="base"
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    data-radius="{{ $radius }}"
    data-indeterminate="{{ $isIndeterminate ? 'true' : 'false' }}"
    data-striped="{{ $isStriped ? 'true' : 'false' }}"
    data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
    data-disable-animation="{{ $animationDisabled ? 'true' : 'false' }}"
    data-value="{{ $current }}"
    data-min-value="{{ $minimum }}"
    data-max-value="{{ $maximum }}"
    @if($valueLabel !== null) data-value-label="{{ $valueLabel }}" @endif
    data-format-options="{{ json_encode($options, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    @if($locale) data-locale="{{ $locale }}" @endif
    role="progressbar"
    @if($visibleLabel && !$attributes->has('aria-label')) aria-label="{{ $visibleLabel }}" @endif
    @unless($isIndeterminate)
        aria-valuenow="{{ $current }}"
        aria-valuemin="{{ $minimum }}"
        aria-valuemax="{{ $maximum }}"
        aria-valuetext="{{ $fallbackValueText }}"
    @endunless
    @if($isDisabled) aria-disabled="true" @endif
    style="{{ $rootStyle }}"
    {{ $rootAttributes->class(["app-progress", "app-color-$color", "app-radius-$radius"]) }}
>
    @if($visibleLabel || $showsValue)
        <div data-slot="labelWrapper" class="app-progress-label-wrapper">
            @if($visibleLabel)
                <span data-slot="label" class="app-progress-label">{{ $visibleLabel }}</span>
            @endif
            @if($showsValue)
                <span data-slot="value" class="app-progress-value">{{ $fallbackValueText }}</span>
            @endif
        </div>
    @endif
    <div data-slot="track" class="app-progress-track">
        <div data-slot="indicator" class="app-progress-indicator"></div>
    </div>
</div>
