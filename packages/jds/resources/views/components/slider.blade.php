@props([
    'id' => null, 'name' => null, 'value' => 0, 'label' => null,
    'minValue' => 0, 'maxValue' => 100, 'step' => 1,
    'size' => 'md', 'color' => 'primary', 'radius' => 'full',
    'orientation' => 'horizontal', 'fillOffset' => null,
    'showSteps' => false, 'marks' => [], 'showTooltip' => false,
    'formatOptions' => [], 'tooltipValueFormatOptions' => null, 'locale' => null,
    'showOutline' => false, 'hideValue' => false, 'hideThumb' => false,
    'disableThumbScale' => false, 'disabled' => false, 'disableAnimation' => false,
])

@php
    $id = $id ?: 'slider-' . substr(md5(uniqid('', true)), 0, 10);
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $color = in_array($color, ['foreground', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'primary';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'full';
    $orientation = $orientation === 'vertical' ? 'vertical' : 'horizontal';
    $minimum = (float) $minValue;
    $maximum = max($minimum, (float) $maxValue);
    $interval = max(0.0000001, (float) $step);
    $range = max(0.0000001, $maximum - $minimum);
    $values = is_array($value) ? array_values($value) : [$value];
    $values = array_map(fn ($item) => max($minimum, min($maximum, (float) $item)), $values);
    if (count($values) > 1) sort($values, SORT_NUMERIC);
    $isRange = count($values) > 1;
    $offsetValue = $fillOffset === null ? $minimum : max($minimum, min($maximum, (float) $fillOffset));
    $percentages = array_map(fn ($item) => (($item - $minimum) / $range) * 100, $values);
    $fillStart = $isRange ? min($percentages) : min($percentages[0], (($offsetValue - $minimum) / $range) * 100);
    $fillEnd = $isRange ? max($percentages) : max($percentages[0], (($offsetValue - $minimum) / $range) * 100);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
    $stepsVisible = filter_var($showSteps, FILTER_VALIDATE_BOOL);
    $tooltipVisible = filter_var($showTooltip, FILTER_VALIDATE_BOOL);
    $outlineVisible = filter_var($showOutline, FILTER_VALIDATE_BOOL);
    $valueHidden = filter_var($hideValue, FILTER_VALIDATE_BOOL);
    $thumbHidden = filter_var($hideThumb, FILTER_VALIDATE_BOOL);
    $thumbScaleDisabled = filter_var($disableThumbScale, FILTER_VALIDATE_BOOL);
    $animationDisabled = filter_var($disableAnimation, FILTER_VALIDATE_BOOL);
    $normalizedMarks = [];
    foreach ((array) $marks as $key => $mark) {
        $markValue = is_array($mark) ? ($mark['value'] ?? $key) : (is_numeric($key) ? $key : $mark);
        $markLabel = is_array($mark) ? ($mark['label'] ?? $markValue) : $mark;
        if (is_numeric($markValue) && $markValue >= $minimum && $markValue <= $maximum) {
            $normalizedMarks[] = ['value' => (float) $markValue, 'label' => $markLabel];
        }
    }
    $stepCount = (int) floor(($maximum - $minimum) / $interval);
    $renderSteps = $stepsVisible && $stepCount <= 200;
    $format = is_array($formatOptions) ? $formatOptions : [];
    $tooltipFormat = is_array($tooltipValueFormatOptions) ? $tooltipValueFormatOptions : $format;
    $rootStyle = '--slider-fill-start:' . $fillStart . '%;--slider-fill-end:' . $fillEnd . '%';
    if ($attributes->get('style')) $rootStyle .= ';' . $attributes->get('style');
@endphp

<div
    id="{{ $id }}" data-ui-component="slider" data-slot="base"
    data-size="{{ $size }}" data-color="{{ $color }}" data-radius="{{ $radius }}"
    data-orientation="{{ $orientation }}" data-range="{{ $isRange ? 'true' : 'false' }}"
    data-disabled="{{ $isDisabled ? 'true' : 'false' }}"
    data-show-outline="{{ $outlineVisible ? 'true' : 'false' }}"
    data-hide-thumb="{{ $thumbHidden ? 'true' : 'false' }}"
    data-disable-thumb-scale="{{ $thumbScaleDisabled ? 'true' : 'false' }}"
    data-disable-animation="{{ $animationDisabled ? 'true' : 'false' }}"
    data-min-value="{{ $minimum }}" data-max-value="{{ $maximum }}" data-step="{{ $interval }}"
    data-fill-offset="{{ $offsetValue }}"
    data-format-options="{{ json_encode($format, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    data-tooltip-format-options="{{ json_encode($tooltipFormat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    @if($locale) data-locale="{{ $locale }}" @endif
    role="group" @if($label) aria-labelledby="{{ $id }}-label" @endif
    @if($isDisabled) aria-disabled="true" @endif
    style="{{ $rootStyle }}"
    {{ $attributes->except('style')->class(["app-slider", "app-color-$color", "app-radius-$radius"]) }}
>
    @if($label || isset($labelContent))
        <div data-slot="labelWrapper" class="app-slider-label-wrapper">
            @if($label || isset($labelContent))
                <span id="{{ $id }}-label" data-slot="label" class="app-slider-label">@isset($labelContent){{ $labelContent }}@else{{ $label }}@endisset</span>
            @endif
            @unless($valueHidden)
                <output data-slot="value" class="app-slider-value" aria-live="off" @unless(isset($valueContent)) data-slider-output @endunless>
                    @isset($valueContent){{ $valueContent }}@endisset
                </output>
            @endunless
        </div>
    @endif

    <div data-slot="trackWrapper" class="app-slider-track-wrapper">
        @isset($startContent)<span data-slot="startContent" class="app-slider-content">{{ $startContent }}</span>@endisset
        <div data-slot="track" data-slider-track data-fill-start="{{ $fillStart <= 0 ? 'true' : 'false' }}" data-fill-end="{{ $fillEnd >= 100 ? 'true' : 'false' }}" class="app-slider-track">
            <span data-slot="filler" data-slider-fill class="app-slider-fill"></span>
            @if($renderSteps)
                @for($index = 0; $index <= $stepCount; $index++)
                    @php $stepValue = min($maximum, $minimum + ($index * $interval)); @endphp
                    <span data-slot="step" data-slider-step data-value="{{ $stepValue }}" class="app-slider-step" style="--slider-position:{{ (($stepValue - $minimum) / $range) * 100 }}%"></span>
                @endfor
            @endif
            @foreach($normalizedMarks as $mark)
                <span data-slot="mark" data-slider-mark data-value="{{ $mark['value'] }}" class="app-slider-mark" style="--slider-position:{{ (($mark['value'] - $minimum) / $range) * 100 }}%">{{ $mark['label'] }}</span>
            @endforeach
            @foreach($values as $index => $item)
                <span data-slot="thumb" data-slider-thumb data-index="{{ $index }}" data-dragging="false" data-focus-visible="false" class="app-slider-thumb" style="--slider-position:{{ $percentages[$index] }}%">
                    @if($tooltipVisible)
                        <span data-slot="tooltip" data-slider-tooltip class="app-slider-tooltip" role="tooltip">
                            @isset($tooltipContent){{ $tooltipContent }}@else<span data-slider-tooltip-value></span>@endisset
                        </span>
                    @endif
                    @isset($thumb)<span data-slot="thumbContent" class="app-slider-thumb-content">{{ $thumb }}</span>@endisset
                    <span class="app-sr-only"><input data-slider-input type="range"
                        @if($name) name="{{ $name }}{{ $isRange ? '[]' : '' }}" @endif
                        min="{{ $minimum }}" max="{{ $maximum }}" step="{{ $interval }}" value="{{ $item }}"
                        @if($label) aria-labelledby="{{ $id }}-label" @else aria-label="{{ $isRange ? '슬라이더 손잡이 ' . ($index + 1) : '슬라이더' }}" @endif
                        @if($orientation === 'vertical') aria-orientation="vertical" @endif @disabled($isDisabled)></span>
                </span>
            @endforeach
        </div>
        @isset($endContent)<span data-slot="endContent" class="app-slider-content">{{ $endContent }}</span>@endisset
    </div>
</div>
