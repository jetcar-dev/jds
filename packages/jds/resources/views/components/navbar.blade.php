@props(['position'=>'static','maxWidth'=>'full'])
<nav data-slot="navbar" data-position="{{ $position }}" data-max-width="{{ $maxWidth }}" {{ $attributes->class('app-navbar') }}>{{ $slot }}</nav>
