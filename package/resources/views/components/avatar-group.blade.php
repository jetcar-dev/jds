@props(['max'=>null,'total'=>null])
<div data-slot="avatar-group" @if($max) data-max="{{ $max }}" @endif {{ $attributes->class('app-avatar-group') }}>{{ $slot }}@if($total)<x-avatar :name="'+'.$total" />@endif</div>
