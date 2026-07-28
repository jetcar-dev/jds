@props(['appearance' => 'legend'])
@php $appearance = in_array($appearance, ['legend', 'label'], true) ? $appearance : 'legend'; @endphp
<legend data-slot="field-legend" data-appearance="{{ $appearance }}" {{ $attributes->class('app-field-legend') }}>{{ $slot }}</legend>
