@props(['mode' => 'single', 'name' => null, 'value' => null, 'placeholder' => null, 'hourCycle' => 'auto', 'timeVariant' => 'input', 'seconds' => false, 'minuteStep' => 1, 'captionLayout' => 'dropdown', 'min' => null, 'max' => null, 'minNights' => null, 'maxNights' => null, 'outOfRange' => 'disable', 'weekStart' => 0, 'numberOfMonths' => null, 'defaultMonth' => null, 'showOutsideDays' => true, 'width' => null, 'disabled' => false])
@php
    $mode = $mode === 'range' ? 'range' : 'single';
    $range = is_array($value) ? $value : [];
    $single = is_string($value) ? str_replace(' ', 'T', $value) : '';
    $split = fn ($dateTime) => $dateTime ? array_pad(explode('T', $dateTime, 2), 2, '') : ['', ''];
    [$date, $time] = $split($single);
    [$fromDate, $fromTime] = $split($range['from'] ?? '');
    [$toDate, $toTime] = $split($range['to'] ?? '');
    $dateValue = $mode === 'range' ? ['from' => $fromDate, 'to' => $toDate] : $date;
    $placeholder ??= $mode === 'range' ? '시작일 · 종료일 선택' : 'YYYY-MM-DD HH:mm';
@endphp
<div data-slot="datetime-picker" data-datetime-mode="{{ $mode }}" data-datetime-hour-cycle="{{ $hourCycle }}" data-datetime-seconds="{{ $seconds ? 'true' : 'false' }}" @if($width) style="width: {{ $width }}" @endif {{ $attributes->class('app-datetime-picker') }}>
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
                <div><span>시작 시간</span><x-time-field :value="$fromTime" :variant="$timeVariant" :hour-cycle="$hourCycle" :seconds="$seconds" :minute-step="$minuteStep" :disabled="$disabled" part="from" /></div>
                <div><span>종료 시간</span><x-time-field :value="$toTime" :variant="$timeVariant" :hour-cycle="$hourCycle" :seconds="$seconds" :minute-step="$minuteStep" :disabled="$disabled" part="to" /></div>
            @else
                <div><span>시간</span><x-time-field :value="$time" :variant="$timeVariant" :hour-cycle="$hourCycle" :seconds="$seconds" :minute-step="$minuteStep" :disabled="$disabled" /></div>
            @endif
        </div>
        <div class="app-datetime-footer"><button type="button" data-datetime-done>완료</button></div>
    </div>
</div>
