@props(['href' => null, 'current' => false, 'separator' => null])
<li data-slot="breadcrumb" data-current="{{ $current ? 'true' : 'false' }}" {{ $attributes->class('app-breadcrumb-item') }}>
    @if($href)<a data-slot="breadcrumb-link" href="{{ $href }}" class="app-breadcrumb-link">{{ $slot }}</a>@else<span data-slot="breadcrumb-current" @if($current) aria-current="page" @endif>{{ $slot }}</span>@endif
    @unless($current)<span data-slot="separator" class="app-breadcrumb-separator" aria-hidden="true">{{ $separator ?? '/' }}</span>@endunless
</li>
