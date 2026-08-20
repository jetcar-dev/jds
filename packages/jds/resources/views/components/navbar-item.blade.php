@props(['active'=>false])
<div data-slot="navbar-item" data-active="{{ $active?'true':'false' }}" {{ $attributes }}>{{ $slot }}</div>
