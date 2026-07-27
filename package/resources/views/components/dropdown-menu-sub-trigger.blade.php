@props(['inset' => false, 'disabled' => false])

<button
    type="button"
    role="menuitem"
    tabindex="-1"
    data-slot="dropdown-menu-sub-trigger"
    aria-haspopup="menu"
    aria-expanded="false"
    @if($inset) data-inset @endif
    @disabled($disabled)
    @if($disabled) aria-disabled="true" @endif
    {{ $attributes->class('app-dropdown-item app-dropdown-submenu-trigger') }}
>
    {{ $slot }}
    <span class="app-dropdown-submenu-arrow" aria-hidden="true"></span>
</button>
