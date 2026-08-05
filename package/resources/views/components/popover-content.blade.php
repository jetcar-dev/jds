@props(['placement'=>'bottom','offset'=>4])
<div data-slot="popover-content" data-placement="{{ $placement }}" data-offset="{{ $offset }}" class="app-popover-content" hidden {{ $attributes }}>{{ $slot }}</div>
