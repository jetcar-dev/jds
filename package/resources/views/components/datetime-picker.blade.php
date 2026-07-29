@props(['mode' => 'single', 'name' => null, 'value' => null, 'placeholder' => null, 'hourCycle' => 'auto', 'timeInputType' => 'input', 'seconds' => false, 'minuteStep' => 1, 'captionLayout' => 'dropdown', 'min' => null, 'max' => null, 'minNights' => null, 'maxNights' => null, 'outOfRange' => 'disable', 'weekStart' => 0, 'numberOfMonths' => null, 'defaultMonth' => null, 'showOutsideDays' => true, 'width' => null, 'fullWidth' => false, 'disabled' => false, 'variant' => null, 'color' => 'default', 'size' => 'md'])
@php
    $hasExplicitVariant = $variant !== null;
    $variant ??= 'flat';
    $mode = $mode === 'range' ? 'range' : 'single';
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $range = is_array($value) ? $value : [];
    $single = is_string($value) ? str_replace(' ', 'T', $value) : '';
    $split = fn ($dateTime) => $dateTime ? array_pad(explode('T', $dateTime, 2), 2, '') : ['', ''];
    [$date, $time] = $split($single);
    [$fromDate, $fromTime] = $split($range['from'] ?? '');
    [$toDate, $toTime] = $split($range['to'] ?? '');
    $dateValue = $mode === 'range' ? ['from' => $fromDate, 'to' => $toDate] : $date;
    $placeholder ??= $mode === 'range' ? '시작일 · 종료일 선택' : 'YYYY-MM-DD HH:mm';
@endphp
<div data-slot="datetime-picker" data-datetime-mode="{{ $mode }}" data-datetime-hour-cycle="{{ $hourCycle }}" data-datetime-seconds="{{ $seconds ? 'true' : 'false' }}" data-variant="{{ $variant }}" @if($hasExplicitVariant) data-group-variant="explicit" @endif data-color="{{ $color }}" data-size="{{ $size }}" @if($width && ! $fullWidth) style="width: {{ $width }}" @endif {{ $attributes->class(['app-datetime-picker', 'app-datetime-picker-'.$variant, 'app-datetime-picker-'.$size, 'app-color-'.$color, 'app-datetime-picker-full' => $fullWidth]) }}>
    @if($mode === 'range')
        @if($name)<input type="hidden" data-datetime-from name="{{ $name }}[from]" value="{{ $range['from'] ?? '' }}"><input type="hidden" data-datetime-to name="{{ $name }}[to]" value="{{ $range['to'] ?? '' }}">@endif
    @elseif($name)<input type="hidden" data-datetime-input name="{{ $name }}" value="{{ $single }}">@endif

    <button type="button" data-datetime-trigger @disabled($disabled) aria-haspopup="dialog" aria-expanded="false">
        <x-icon name="calendar-search-linear" class="app-date-picker-icon" />
        <span data-datetime-label>{{ $mode === 'range' ? (($fromDate ? $fromDate . ($fromTime ? ', ' . $fromTime : '') : 'YYYY-MM-DD HH:mm') . ' ~ ' . ($toDate ? $toDate . ($toTime ? ', ' . $toTime : '') : 'YYYY-MM-DD HH:mm')) : ($single ? $date . ($time ? ', ' . $time : '') : $placeholder) }}</span>
    </button>

    <div data-datetime-popover hidden role="dialog">
        <x-calendar :mode="$mode" :value="$dateValue" :min-date="$min" :max-date="$max" :min-days="$minNights" :max-days="$maxNights" :default-month="$defaultMonth" :week-start="$weekStart" :caption-layout="$captionLayout" :show-outside-days="$showOutsideDays" :out-of-range="$outOfRange" :number-of-months="$numberOfMonths ?? ($mode === 'range' ? 2 : 1)" />
        <div class="app-datetime-time-panel">
            @if($mode === 'range')
                <div><span>시작 시간</span><x-time-field :value="$fromTime" :input-type="$timeInputType" :hour-cycle="$hourCycle" :seconds="$seconds" :minute-step="$minuteStep" :disabled="$disabled" :variant="$variant" :color="$color" :size="$size" part="from" /></div>
                <div><span>종료 시간</span><x-time-field :value="$toTime" :input-type="$timeInputType" :hour-cycle="$hourCycle" :seconds="$seconds" :minute-step="$minuteStep" :disabled="$disabled" :variant="$variant" :color="$color" :size="$size" part="to" /></div>
            @else
                <div><span>시간</span><x-time-field :value="$time" :input-type="$timeInputType" :hour-cycle="$hourCycle" :seconds="$seconds" :minute-step="$minuteStep" :disabled="$disabled" :variant="$variant" :color="$color" :size="$size" /></div>
            @endif
        </div>
        <div class="app-datetime-footer"><button type="button" data-datetime-done>완료</button></div>
    </div>
</div>
