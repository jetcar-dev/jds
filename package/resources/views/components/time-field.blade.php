@props(['name' => null, 'value' => null, 'variant' => 'input', 'hourCycle' => 'auto', 'seconds' => false, 'minuteStep' => 1, 'secondStep' => 1, 'min' => null, 'max' => null, 'disabled' => false, 'id' => null, 'part' => null])
@php
    $variant = in_array($variant, ['input', 'select'], true) ? $variant : 'input';
    $config = compact('variant', 'hourCycle', 'seconds', 'minuteStep', 'secondStep', 'min', 'max', 'disabled', 'part');
@endphp
<div data-slot="time-field" data-time-config='@json($config)' @if($id) id="{{ $id }}" @endif {{ $attributes->class('app-time-field') }}>
    <input type="hidden" data-time-value @if($name) name="{{ $name }}" @endif value="{{ $value }}">
    <div data-time-ui>
        <input type="time" value="{{ $value }}" min="{{ $min }}" max="{{ $max }}" step="{{ $seconds ? max(1, (int) $secondStep) : max(1, (int) $minuteStep) * 60 }}" @disabled($disabled)>
    </div>
</div>
