{{-- 탭 버튼을 묶는 목록. variant: solid | underlined | bordered | light --}}
@props(['variant' => null, 'appearance' => null])

@php
    $variant ??= $appearance;
    $variant = match ($variant) { 'box' => 'solid', 'line' => 'underlined', 'round' => 'light', default => $variant };
    $variant = in_array($variant, ['solid', 'underlined', 'bordered', 'light'], true) ? $variant : null;
@endphp

<div
    data-slot="tabs-list"
    @if($variant) data-variant="{{ $variant }}" @endif
    role="tablist"
    {{ $attributes->class(['app-tabs-list', 'app-tabs-list-'.$variant => $variant]) }}
>
    {{ $slot }}
</div>
