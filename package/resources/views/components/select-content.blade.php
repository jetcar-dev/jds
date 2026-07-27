@props([
    'align' => 'start',
    'side' => 'bottom',
    'sideOffset' => 4,
    'indicator' => 'check',
])

@php
    $align = in_array($align, ['start', 'center', 'end'], true) ? $align : 'start';
    $side = in_array($side, ['top', 'bottom'], true) ? $side : 'bottom';
    $indicator = in_array($indicator, ['check', 'checkbox', 'radio'], true) ? $indicator : 'check';
@endphp

<div
    data-slot="select-content"
    data-align="{{ $align }}"
    data-side="{{ $side }}"
    data-side-offset="{{ max(0, (int)$sideOffset) }}"
    data-indicator="{{ $indicator }}"
    data-state="closed"
    role="listbox"
    tabindex="-1"
    hidden
    {{ $attributes->class('app-select-content') }}
>
    <div data-slot="select-viewport" class="app-select-viewport">
        {{ $slot }}
    </div>
</div>
