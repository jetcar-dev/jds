@props(['value' => '', 'closeOnSelect' => false, 'disabled' => false])

<button
    type="button"
    role="menuitemradio"
    tabindex="-1"
    data-slot="dropdown-menu-radio-item"
    data-value="{{ $value }}"
    data-close-on-select="{{ $closeOnSelect ? 'true' : 'false' }}"
    aria-checked="false"
    data-state="unchecked"
    @disabled($disabled)
    @if($disabled) aria-disabled="true" @endif
    {{ $attributes->class('app-dropdown-item app-dropdown-checkable') }}
>
    <span class="app-dropdown-item-indicator app-dropdown-radio-indicator" aria-hidden="true"></span>
    {{ $slot }}
</button>
