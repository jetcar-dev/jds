@props(['value' => '', 'label' => 'Copy', 'disabled' => false])
<button
    type="button"
    data-slot="copy-button"
    data-copy-value="{{ (string) $value }}"
    data-copy-label="{{ $label }}"
    aria-label="{{ $label }}"
    @disabled($disabled)
    {{ $attributes->class('app-copy-button') }}
>
    <span class="app-copy-button-icons" aria-hidden="true">
        <x-icon name="copy-linear" data-copy-icon />
        <x-icon name="check-circle-linear" data-copied-icon />
    </span>
    @if($slot->isNotEmpty())<span>{{ $slot }}</span>@endif
    <span class="app-sr-only" data-copy-status aria-live="polite"></span>
</button>
