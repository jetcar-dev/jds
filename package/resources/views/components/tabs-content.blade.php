@props(['value','selected'=>false])
<div data-slot="tab-panel" data-value="{{ $value }}" role="tabpanel" tabindex="0" @unless($selected) hidden @endunless {{ $attributes->class('app-tab-panel') }}>{{ $slot }}</div>
