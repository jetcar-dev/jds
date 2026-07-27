@props([
    'src' => null,
    'alt' => '',
    'ratio' => null,
    'placeholder' => null,
    'rounded' => 'rounded-lg',
    'fit' => 'cover',
])

@php
    $fit = $fit === 'contain' ? 'contain' : 'cover';
    $rounded = str_starts_with($rounded, 'rounded-') ? substr($rounded, 8) : $rounded;
    $rounded = in_array($rounded, ['none', 'sm', 'md', 'lg', 'xl', 'full'], true) ? $rounded : 'lg';
    $userStyle = (string) $attributes->get('style', '');
    $ratioStyle = $ratio ? "aspect-ratio: {$ratio};" : '';
    $style = trim($ratioStyle . ($ratioStyle && $userStyle ? ' ' : '') . $userStyle);
    $attributes = $attributes->except('style');
@endphp

<div
    data-slot="image"
    data-state="loading"
    @if($style) style="{{ $style }}" @endif
    {{ $attributes->class(['app-image', 'app-image-rounded-'.$rounded, 'app-image-'.$fit]) }}
>
    @if($placeholder)
        <img src="{{ $placeholder }}" alt="" aria-hidden="true" data-image-placeholder>
    @else
        <div aria-hidden="true" data-image-placeholder></div>
    @endif

    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" decoding="async" data-image-main>

    <div data-image-error hidden>
        <x-icon name="lucide:image-off" />
        @if($alt !== '')<span>{{ $alt }}</span>@endif
    </div>
</div>
