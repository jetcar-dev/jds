@props([
    'value' => null,
    'id' => null,
    'disabled' => false,
])

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
    {{ $attributes->class('app-radio-group-item') }}
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
