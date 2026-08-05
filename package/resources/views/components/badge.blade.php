@props(['content'=>null,'color'=>'danger','placement'=>'top-right','invisible'=>false,'shape'=>'rectangle'])
<span data-slot="badge" {{ $attributes->class('app-badge') }}>{{ $slot }}@unless($invisible)<span data-slot="badge-content" data-placement="{{ $placement }}" data-shape="{{ $shape }}" class="app-badge-content app-color-{{ $color }}">{{ $content ?? ($badge ?? '') }}</span>@endunless</span>
