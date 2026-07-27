@props(['checked' => false, 'disabled' => false, 'closeOnSelect' => false])

<button
    type="button"
    role="menuitemcheckbox"
    tabindex="-1"
    data-slot="dropdown-menu-checkbox-item"
    data-close-on-select="{{ $closeOnSelect ? 'true' : 'false' }}"
    aria-checked="{{ $checked ? 'true' : 'false' }}"
    data-state="{{ $checked ? 'checked' : 'unchecked' }}"
    @disabled($disabled)
    @if($disabled) aria-disabled="true" @endif
    {{ $attributes->class('app-dropdown-item app-dropdown-checkable') }}
>
    <span class="app-dropdown-item-indicator" aria-hidden="true">✓</span>
    {{ $slot }}
</button>
