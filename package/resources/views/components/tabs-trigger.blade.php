{{-- value가 같은 tabs-content를 활성화하는 탭 버튼 --}}
@props([
    'value' => null,
    'disabled' => false,
])

<button
    type="button"
    data-slot="tabs-trigger"
    data-tab-value="{{ $value }}"
    role="tab"
    aria-selected="false"
    tabindex="-1"
    @disabled($disabled)
    {{ $attributes->class('app-tab') }}
>
    {{ $slot }}
</button>
