@props(['x'=>1,'y'=>1])
<span data-slot="spacer" aria-hidden="true" style="--spacer-x:{{ is_numeric($x)?$x.'rem':$x }};--spacer-y:{{ is_numeric($y)?$y.'rem':$y }}" {{ $attributes->class('app-spacer') }}></span>
