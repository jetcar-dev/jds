@props([
    'src' => null,
    'srcset' => null,
    'sizes' => null,
    'alt' => '',
    'width' => null,
    'height' => null,
    'radius' => 'lg',
    'shadow' => 'none',
    'loading' => null,
    'fallbackSrc' => null,
    'blurred' => false,
    'zoomed' => false,
    'removeWrapper' => false,
    'disableSkeleton' => false,
    'disableAnimation' => false,
])
@php
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'lg';
    $shadow = in_array($shadow, ['none', 'sm', 'md', 'lg'], true) ? $shadow : 'none';
    $loading = in_array($loading, ['eager', 'lazy'], true) ? $loading : null;
    $showSkeleton = !$removeWrapper && !$disableSkeleton && !$fallbackSrc;
    $wrapperStyle = collect([
        $width ? '--image-width:'.(is_numeric($width) ? $width.'px' : $width) : null,
        $height ? '--image-height:'.(is_numeric($height) ? $height.'px' : $height) : null,
        $fallbackSrc ? '--image-fallback:url(\''.str_replace("'", "\\'", $fallbackSrc).'\')' : null,
    ])->filter()->implode(';');
@endphp

@if($removeWrapper)
    <img
        data-slot="img"
        data-ui-component="image"
        data-loaded="false"
        data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
        class="app-image app-radius-{{ $radius }}"
        @if($src) src="{{ $src }}" @endif
        @if($srcset) srcset="{{ $srcset }}" @endif
        @if($sizes) sizes="{{ $sizes }}" @endif
        alt="{{ $alt }}"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        @if($loading) loading="{{ $loading }}" @endif
        {{ $attributes }}
    >
@else
    <div
        data-slot="wrapper"
        data-ui-component="image"
        data-loaded="false"
        data-loading="true"
        data-error="false"
        data-shadow="{{ $shadow }}"
        data-blurred="{{ $blurred ? 'true' : 'false' }}"
        data-zoomed="{{ $zoomed ? 'true' : 'false' }}"
        data-show-skeleton="{{ $showSkeleton ? 'true' : 'false' }}"
        data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
        class="app-image-wrapper app-radius-{{ $radius }}"
        @if($wrapperStyle) style="{{ $wrapperStyle }}" @endif
    >
        @if($zoomed)
            <div data-slot="zoomedWrapper" class="app-image-zoomed-wrapper app-radius-{{ $radius }}">
        @endif

        <img
            data-slot="img"
            class="app-image app-radius-{{ $radius }}"
            @if($src) src="{{ $src }}" @endif
            @if($srcset) srcset="{{ $srcset }}" @endif
            @if($sizes) sizes="{{ $sizes }}" @endif
            alt="{{ $alt }}"
            @if($width) width="{{ $width }}" @endif
            @if($height) height="{{ $height }}" @endif
            @if($loading) loading="{{ $loading }}" @endif
            {{ $attributes }}
        >

        @if($zoomed)
            </div>
        @endif

        @if($blurred)
            <img
                data-slot="blurredImg"
                class="app-image-blurred app-radius-{{ $radius }}"
                @if($src) src="{{ $src }}" @endif
                alt=""
                aria-hidden="true"
            >
        @endif
    </div>
@endif
