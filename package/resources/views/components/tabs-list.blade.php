{{-- 탭 버튼을 묶는 목록. appearance: box | line | round --}}
@props(['appearance' => 'box'])

@php
    $appearance = in_array($appearance, ['box', 'line', 'round'], true) ? $appearance : 'box';
@endphp

<div
    data-slot="tabs-list"
    data-appearance="{{ $appearance }}"
    role="tablist"
    {{ $attributes->class('app-tabs-list') }}
>
    {{ $slot }}
</div>
