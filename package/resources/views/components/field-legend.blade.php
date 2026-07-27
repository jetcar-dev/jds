@props(['variant' => 'legend'])
<legend data-slot="field-legend" data-variant="{{ $variant }}" {{ $attributes->class('app-field-legend') }}>{{ $slot }}</legend>
