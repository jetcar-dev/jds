@props(['variant'=>'solid','color'=>'default','size'=>'md','radius'=>'md','href'=>null,'type'=>'button','disabled'=>false,'loading'=>false,'iconOnly'=>false,'fullWidth'=>false,'spinnerPlacement'=>'start'])
@php
    $variant=in_array($variant,['solid','faded','bordered','light','flat','ghost','shadow'],true)?$variant:'solid';
    $color=in_array($color,['default','primary','secondary','success','warning','danger'],true)?$color:'default';
    $size=in_array($size,['sm','md','lg'],true)?$size:'md';
    $radius=in_array($radius,['none','sm','md','lg','full'],true)?$radius:'md';
    $tag=$href?'a':'button';
@endphp
<{{ $tag }} data-slot="button" data-ui-component="button" data-ui-interactive data-variant="{{ $variant }}" data-color="{{ $color }}" data-size="{{ $size }}" data-disabled="{{ $disabled ? 'true':'false' }}" data-loading="{{ $loading ? 'true':'false' }}" data-icon-only="{{ $iconOnly ? 'true':'false' }}" data-full-width="{{ $fullWidth ? 'true':'false' }}" @if($href) href="{{ $href }}" @else type="{{ $type }}" @disabled($disabled || $loading) @endif @if($tag==='a'&&$disabled) aria-disabled="true" tabindex="-1" @endif {{ $attributes->class("app-button app-color-$color app-size-$size app-radius-$radius") }}>
    @if($loading && $spinnerPlacement==='start')<span class="app-button-spinner" aria-hidden="true"></span>@endif
    @isset($startContent){{ $startContent }}@endisset
    @if($loading)<span class="app-sr-only">Loading</span>@endif
    {{ $slot }}
    @isset($endContent){{ $endContent }}@endisset
    @if($loading && $spinnerPlacement==='end')<span class="app-button-spinner" aria-hidden="true"></span>@endif
</{{ $tag }}>
