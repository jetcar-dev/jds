@props(['sideOffset' => 4])

<div
    data-slot="dropdown-menu-sub-content"
    data-side-offset="{{ max(0, (int) $sideOffset) }}"
    role="menu"
    aria-orientation="vertical"
    tabindex="-1"
    hidden
    {{ $attributes->class('app-dropdown-menu app-dropdown-submenu-menu') }}
>
    {{ $slot }}
</div>
