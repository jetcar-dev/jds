{{-- 탭 버튼을 묶는 목록. variant: segmented | underline | pills --}}
@props(['variant' => 'segmented'])

@php
    $variant = in_array($variant, ['segmented', 'underline', 'pills'], true) ? $variant : 'segmented';
@endphp

<div
    data-slot="tabs-list"
    data-variant="{{ $variant }}"
    role="tablist"
    {{ $attributes->class('app-tabs-list') }}
>
    {{ $slot }}
</div>
