@props(['mode' => 'single', 'name' => null, 'value' => null, 'placeholder' => null, 'numberOfMonths' => null, 'captionLayout' => 'label', 'weekStart' => 0, 'defaultMonth' => null, 'min' => null, 'max' => null, 'minNights' => null, 'maxNights' => null, 'outOfRange' => 'disable', 'showOutsideDays' => true, 'width' => null, 'fullWidth' => false, 'presets' => null, 'disabled' => false, 'variant' => null, 'color' => 'default', 'size' => 'md'])
@php
    $hasExplicitVariant = $variant !== null;
    $variant ??= 'flat';
    $mode = $mode === 'range' ? 'range' : 'single';
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $range = is_array($value) ? $value : [];
    $from = $mode === 'range' ? ($range['from'] ?? '') : '';
    $to = $mode === 'range' ? ($range['to'] ?? '') : '';
    $calendarValue = $mode === 'range' ? ['from' => $from, 'to' => $to] : $value;
    $placeholder ??= $mode === 'range' ? '날짜 범위 선택' : '날짜 선택';
    $presetValues = $presets === true ? ['today', 'yesterday', 'last7Days', 'last30Days', 'thisMonth', 'yearToDate'] : (array) ($presets ?? []);
    $config = compact('mode', 'min', 'max', 'minNights', 'maxNights', 'outOfRange', 'presets', 'disabled');
@endphp
<div data-slot="date-picker" data-date-config='@json($config)' data-variant="{{ $variant }}" @if($hasExplicitVariant) data-group-variant="explicit" @endif data-color="{{ $color }}" data-size="{{ $size }}" @if($width && ! $fullWidth) style="width: {{ $width }}" @endif {{ $attributes->class(['app-date-picker', 'app-date-picker-'.$variant, 'app-date-picker-'.$size, 'app-color-'.$color, 'app-date-picker-full' => $fullWidth]) }}>
    @if($name && $mode === 'range')
        <input type="hidden" data-date-from name="{{ $name }}[from]" value="{{ $from }}"><input type="hidden" data-date-to name="{{ $name }}[to]" value="{{ $to }}">
    @elseif($name)<input type="hidden" data-date-input name="{{ $name }}" value="{{ $value }}">@endif
    <button type="button" data-date-trigger data-date-mode="{{ $mode }}" @disabled($disabled) aria-haspopup="dialog" aria-expanded="false">
        <x-icon name="calendar-search-linear" class="app-date-picker-icon" />
        @if($mode === 'range')
            <span class="app-date-range-values">
                <span class="app-date-range-value"><span>시작일</span><strong data-date-from-label @class(['is-placeholder' => !$from])>{{ $from ?: 'YYYY-MM-DD' }}</strong></span>
                <span class="app-date-range-separator" aria-hidden="true">~</span>
                <span class="app-date-range-value"><span>종료일</span><strong data-date-to-label @class(['is-placeholder' => !$to])>{{ $to ?: 'YYYY-MM-DD' }}</strong></span>
            </span>
        @else
            <span data-date-value-label @class(['is-placeholder' => !$value])>{{ $value ?: ($placeholder ?: 'YYYY-MM-DD') }}</span>
        @endif
    </button>
    <div data-date-popover hidden role="dialog">
        @if(count($presetValues))<div class="app-date-presets">@foreach($presetValues as $key => $preset)<button type="button" data-date-preset="{{ is_string($key) ? $key : $preset }}" data-date-preset-value='@json(is_array($preset) ? $preset : null)'>{{ is_string($key) ? $key : $preset }}</button>@endforeach</div>@endif
        <x-calendar :mode="$mode" :value="$calendarValue" :min-date="$min" :max-date="$max" :default-month="$defaultMonth" :week-start="$weekStart" :caption-layout="$captionLayout" :show-outside-days="$showOutsideDays" :out-of-range="$outOfRange" :min-days="$minNights" :max-days="$maxNights" :number-of-months="$numberOfMonths ?? ($mode === 'range' ? 2 : 1)" />
        <p data-date-error hidden></p>
    </div>
</div>
