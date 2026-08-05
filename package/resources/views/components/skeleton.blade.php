@props(['loaded'=>false,'radius'=>'md'])
<div data-slot="skeleton" data-loaded="{{ $loaded?'true':'false' }}" {{ $attributes->class("app-skeleton app-radius-$radius") }}>@if($loaded){{ $slot }}@endif</div>
