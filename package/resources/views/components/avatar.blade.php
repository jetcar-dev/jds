@props(['src'=>null,'name'=>null,'alt'=>null,'size'=>'md','radius'=>'full','color'=>'default','bordered'=>false,'fallback'=>null])
@php $initials=$fallback ?: collect(preg_split('/\s+/u',trim((string)$name)))->filter()->map(fn($part)=>mb_substr($part,0,1))->take(2)->implode(''); @endphp
<span data-slot="avatar" data-size="{{ $size }}" data-bordered="{{ $bordered?'true':'false' }}" {{ $attributes->class("app-avatar app-color-$color app-radius-$radius") }}>
    @if($src)<img src="{{ $src }}" alt="{{ $alt ?? $name ?? '' }}" />@else<span data-slot="fallback">{{ $initials }}</span>@endif
</span>
