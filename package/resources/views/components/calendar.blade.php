@props(['mode' => 'single', 'value' => null, 'name' => null, 'locale' => null, 'numberOfMonths' => 1, 'defaultMonth' => null, 'weekStart' => 0, 'captionLayout' => 'label', 'showWeekNumber' => false, 'disabled' => null, 'min' => null, 'max' => null, 'minDays' => null, 'maxDays' => null, 'required' => false, 'startMonth' => null, 'endMonth' => null, 'disableNavigation' => false, 'modifiers' => [], 'modifiersClass' => [], 'buttonVariant' => 'ghost', 'showOutsideDays' => true, 'minDate' => null, 'maxDate' => null, 'outOfRange' => 'disable'])
@php
    $mode = in_array($mode, ['single', 'range', 'multiple'], true) ? $mode : 'single';
    $days = ['sunday'=>0,'monday'=>1,'tuesday'=>2,'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6];
    $weekStart = is_numeric($weekStart) ? (int) $weekStart : ($days[strtolower($weekStart)] ?? 0);
    $config = compact('mode', 'value', 'locale', 'defaultMonth', 'captionLayout', 'showWeekNumber', 'disabled', 'min', 'max', 'minDays', 'maxDays', 'required', 'startMonth', 'endMonth', 'disableNavigation', 'modifiers', 'modifiersClass', 'buttonVariant', 'showOutsideDays', 'minDate', 'maxDate', 'outOfRange');
    $config['weekStart'] = (($weekStart % 7) + 7) % 7;
    $config['numberOfMonths'] = max(1, (int) $numberOfMonths);
@endphp
<div data-slot="calendar" data-calendar-config='@json($config)' {{ $attributes->class('app-calendar') }}>
    @if($name)<input type="hidden" data-calendar-input name="{{ $name }}" value='@json($value)'>@endif
    <div data-calendar-ui></div>
</div>
