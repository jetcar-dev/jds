@props(['size' => 'md', 'color' => 'foreground'])
<nav aria-label="Breadcrumbs" data-slot="breadcrumbs" data-size="{{ $size }}" data-color="{{ $color }}" {{ $attributes }}><ol class="app-breadcrumbs">{{ $slot }}</ol></nav>
