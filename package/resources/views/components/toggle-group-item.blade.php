@props(['value' => null, 'disabled' => false])

<button
    type="button"
    data-slot="toggle-group-item"
    data-value="{{ $value }}"
    data-state="off"
    aria-pressed="false"
    tabindex="-1"
    @disabled($disabled)
    {{ $attributes->class('app-toggle app-toggle-group-item') }}
>
    {{ $slot }}
</button>
