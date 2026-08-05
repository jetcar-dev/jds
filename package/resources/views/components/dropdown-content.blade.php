@props(['placement'=>'bottom-start'])
<div data-slot="dropdown-content" data-placement="{{ $placement }}" class="app-dropdown-content" role="menu" hidden {{ $attributes }}>{{ $slot }}</div>
