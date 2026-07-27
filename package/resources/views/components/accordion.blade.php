@props(['type' => 'single', 'collapsible' => false, 'value' => null])

@php
    $type = $type === 'multiple' ? 'multiple' : 'single';
    $values = $type === 'multiple' ? (array)($value ?? []) : [$value];
@endphp

<div
    data-slot="accordion"
    data-type="{{ $type }}"
    data-collapsible="{{ $collapsible ? 'true' : 'false' }}"
    data-value="{{ implode('|', array_filter($values, fn ($item) => $item !== null && $item !== '')) }}"
    {{ $attributes->class('app-accordion') }}
>
    {{ $slot }}
</div>
