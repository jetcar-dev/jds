{{-- value가 같은 tabs-trigger가 선택됐을 때 표시되는 패널 --}}
@props(['value' => null])

<div
    data-slot="tabs-content"
    data-tab-panel-value="{{ $value }}"
    role="tabpanel"
    tabindex="0"
    hidden
    {{ $attributes->class('app-tab-panel') }}
>
    {{ $slot }}
</div>
