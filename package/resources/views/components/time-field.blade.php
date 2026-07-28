@props(['name' => null, 'value' => null, 'inputType' => 'input', 'hourCycle' => 'auto', 'seconds' => false, 'minuteStep' => 1, 'secondStep' => 1, 'min' => null, 'max' => null, 'disabled' => false, 'id' => null, 'part' => null])
@php
    $inputType = in_array($inputType, ['input', 'select'], true) ? $inputType : 'input';
    $config = compact('inputType', 'hourCycle', 'seconds', 'minuteStep', 'secondStep', 'min', 'max', 'disabled', 'part');
@endphp
<div data-slot="time-field" data-time-config='@json($config)' @if($id) id="{{ $id }}" @endif {{ $attributes->class('app-time-field') }}>
    <input type="hidden" data-time-value @if($name) name="{{ $name }}" @endif value="{{ $value }}">
    <div data-time-ui>
        <input type="time" value="{{ $value }}" min="{{ $min }}" max="{{ $max }}" step="{{ $seconds ? max(1, (int) $secondStep) : max(1, (int) $minuteStep) * 60 }}" @disabled($disabled)>
    </div>
</div>
