@props(['method'=>'POST','action'=>null,'validationBehavior'=>'native'])
<form data-slot="form" data-validation-behavior="{{ $validationBehavior }}" method="{{ $method }}" @if($action) action="{{ $action }}" @endif {{ $attributes->class('app-form') }}>{{ $slot }}</form>
