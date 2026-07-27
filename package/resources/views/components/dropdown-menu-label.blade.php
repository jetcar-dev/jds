@props(['inset' => false])

<div data-slot="dropdown-menu-label" @if($inset) data-inset @endif {{ $attributes->class('app-dropdown-label') }}>
    {{ $slot }}
</div>
