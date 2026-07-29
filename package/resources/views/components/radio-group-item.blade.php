@props([
    'value' => null,
    'id' => null,
    'disabled' => false,
    'description' => null,
])

@php
    $itemAttributes = $attributes->has('aria-label')
        ? $attributes
        : $attributes->merge(['aria-label' => trim(strip_tags((string) $slot))]);
@endphp

<label class="app-radio-option">
    <button
        type="button"
        role="radio"
        data-slot="radio-group-item"
        data-value="{{ $value }}"
        data-state="unchecked"
        aria-checked="false"
        tabindex="-1"
        @if($id) id="{{ $id }}" @endif
        @disabled($disabled)
        {{ $itemAttributes->class('app-radio-group-item') }}
    >
        <span
            data-slot="radio-group-indicator"
            class="app-radio-group-indicator"
            hidden
        >
            <svg viewBox="0 0 8 8" aria-hidden="true">
                <circle cx="4" cy="4" r="4"/>
            </svg>
        </span>
    </button>
    <span class="app-radio-option-copy">
        <span>{{ $slot }}</span>
        @if($description)<small>{{ $description }}</small>@endif
    </span>
</label>
