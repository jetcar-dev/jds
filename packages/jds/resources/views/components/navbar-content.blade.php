@props(['justify'=>'start'])
<div data-slot="navbar-content" style="justify-content:{{ $justify }}" {{ $attributes->class('app-navbar-content') }}>{{ $slot }}</div>
