@props(['title'=>null,'description'=>null,'color'=>'default','variant'=>'flat','radius'=>'md','dismissible'=>false])
@php $color=in_array($color,['default','primary','secondary','success','warning','danger'],true)?$color:'default'; @endphp
<div data-slot="alert" data-ui-component="alert" data-dismissible="{{ $dismissible?'true':'false' }}" data-variant="{{ $variant }}" role="{{ $color==='danger'?'alert':'status' }}" {{ $attributes->class("app-alert app-color-$color app-radius-$radius") }}>
    <span data-slot="icon">@isset($icon){{ $icon }}@else<x-icon name="info-circle-linear" />@endisset</span>
    <div><div data-slot="title" class="app-alert-title">{{ $title ?? $slot }}</div>@if($description || isset($content))<div data-slot="description" class="app-alert-description">{{ $description ?? $content }}</div>@endif</div>
    @if($dismissible)<button type="button" data-dismiss aria-label="Close">×</button>@endif
</div>
