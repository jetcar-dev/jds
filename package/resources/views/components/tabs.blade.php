{{--
    탭 컨테이너

    <x-tabs value="account">
        <x-tabs-list>...</x-tabs-list>
        <x-tabs-content value="account">...</x-tabs-content>
    </x-tabs>
--}}
@props([
    'value' => null,
    'orientation' => 'horizontal',
    'fullWidth' => false,
])

@php
    $orientation = in_array($orientation, ['horizontal', 'vertical'], true) ? $orientation : 'horizontal';
@endphp

<div
    data-slot="tabs"
    data-default-value="{{ $value }}"
    data-orientation="{{ $orientation }}"
    {{ $attributes->class(['app-tabs', 'app-tabs-'.$orientation, 'app-tabs-full' => $fullWidth]) }}
>
    {{ $slot }}
</div>
