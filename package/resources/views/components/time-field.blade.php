@props(['name' => null, 'value' => null, 'inputType' => 'input', 'hourCycle' => 'auto', 'seconds' => false, 'minuteStep' => 1, 'secondStep' => 1, 'min' => null, 'max' => null, 'disabled' => false, 'id' => null, 'part' => null, 'fullWidth' => false, 'variant' => 'flat', 'color' => 'default', 'size' => 'md'])
@php
    $inputType = in_array($inputType, ['input', 'select'], true) ? $inputType : 'input';
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $config = compact('inputType', 'hourCycle', 'seconds', 'minuteStep', 'secondStep', 'min', 'max', 'disabled', 'part');
@endphp
<div data-slot="time-field" data-time-config='@json($config)' data-variant="{{ $variant }}" data-color="{{ $color }}" data-size="{{ $size }}" @if($id) id="{{ $id }}" @endif {{ $attributes->class(['app-time-field', 'app-time-field-'.$variant, 'app-time-field-'.$size, 'app-color-'.$color, 'app-time-field-full' => $fullWidth]) }}>
    <input type="hidden" data-time-value @if($name) name="{{ $name }}" @endif value="{{ $value }}">
    <div data-time-ui>
        <input type="time" value="{{ $value }}" min="{{ $min }}" max="{{ $max }}" step="{{ $seconds ? max(1, (int) $secondStep) : max(1, (int) $minuteStep) * 60 }}" @disabled($disabled)>
    </div>
</div>
