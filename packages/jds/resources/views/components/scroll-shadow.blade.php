@props(['orientation'=>'vertical','size'=>40,'hideScrollBar'=>false])
<div data-slot="scroll-shadow" data-orientation="{{ $orientation }}" style="--shadow-size:{{ (int)$size }}px;{{ $hideScrollBar?'scrollbar-width:none;':'' }}" {{ $attributes->class('app-scroll-shadow') }}>{{ $slot }}</div>
