<li data-slot="breadcrumb-separator" role="presentation" aria-hidden="true" {{ $attributes->class('app-breadcrumb-separator') }}>
    @if(trim((string) $slot) !== ''){{ $slot }}@else<x-icon name="alt-arrow-right-linear" />@endif
</li>
