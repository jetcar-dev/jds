@props(['content'=>null,'placement'=>'top','delay'=>500])
<span data-slot="tooltip-root" data-delay="{{ $delay }}" {{ $attributes }}><span data-slot="tooltip-trigger">{{ $slot }}</span><span data-slot="tooltip" data-placement="{{ $placement }}" class="app-tooltip" role="tooltip" hidden>{{ $content ?? ($tooltip ?? '') }}</span></span>
