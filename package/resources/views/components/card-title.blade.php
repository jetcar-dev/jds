@props(['as' => 'h3'])

@php
    $tag = in_array($as, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p'], true) ? $as : 'h3';
@endphp

<{{ $tag }} data-slot="card-title" {{ $attributes->class('app-card-title') }}>
    {{ $slot }}
</{{ $tag }}>
