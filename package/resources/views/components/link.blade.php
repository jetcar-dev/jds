@props([
    'href' => '#',
    'variant' => 'default',
    'external' => false,
])

@php $variant = in_array($variant, ['default', 'muted', 'subtle'], true) ? $variant : 'default'; @endphp

<a
    href="{{ $href }}"
    data-slot="link"
    @if($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class(['app-link', 'app-link-'.$variant]) }}
>{{ $slot }}@if($external)<span class="app-sr-only"> (새 탭에서 열림)</span>@endif</a>
