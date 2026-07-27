@aware([
    'indicator' => 'check',
])

@props([
    'value' => '',
    'disabled' => false,
    'indicator' => 'check',
])

@php
    $indicator = in_array($indicator, ['check', 'checkbox', 'radio'], true) ? $indicator : 'check';
@endphp

<div
    role="option"
    tabindex="-1"
    data-slot="select-item"
    data-value="{{ $value }}"
    data-indicator="{{ $indicator }}"
    data-state="unchecked"
    aria-selected="false"
    @if($disabled) data-disabled="true" aria-disabled="true" @endif
    {{ $attributes->class('app-select-item') }}
>
    <span class="app-select-item-label" data-slot="select-item-label">
        {{ $slot }}
    </span>

    <span class="app-select-item-indicator app-select-item-indicator-{{ $indicator }}" aria-hidden="true">
        @if($indicator === 'radio')
            <span class="app-select-radio-dot"></span>
        @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="m20 6-11 11-5-5"/>
            </svg>
        @endif
    </span>
</div>
