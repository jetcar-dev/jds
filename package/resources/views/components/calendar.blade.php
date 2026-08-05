@props(['value' => null, 'selectionMode' => 'single', 'name' => null, 'locale' => 'ko-KR', 'visibleMonth' => null, 'visibleMonths' => 1, 'firstDayOfWeek' => 0, 'minValue' => null, 'maxValue' => null, 'disabledValues' => [], 'color' => 'primary'])
@php
    $selectionMode = in_array($selectionMode, ['single', 'multiple', 'range'], true) ? $selectionMode : 'single';
    $config = compact('value', 'selectionMode', 'locale', 'visibleMonth', 'visibleMonths', 'firstDayOfWeek', 'minValue', 'maxValue', 'disabledValues');
@endphp
<div data-slot="calendar" data-color="{{ $color }}" data-calendar-config='@json($config)' {{ $attributes->class("app-calendar app-color-$color") }}>
    @if($name)<input type="hidden" data-calendar-input name="{{ $name }}" value='@json($value)'>@endif
    <div data-calendar-ui></div>
</div>
