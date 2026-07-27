@props(['href' => '#'])
<a href="{{ $href }}" data-slot="breadcrumb-link" {{ $attributes->class('app-breadcrumb-link') }}>
    {{ $slot }}
</a>
