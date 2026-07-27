@props([
    'id' => null,
    'name' => null,
    'value' => 'on',
    'checked' => false,
    'disabled' => false,
    'size' => 'default',
])

@php
    $switchSize = in_array($size, ['sm', 'default', 'lg'], true) ? $size : 'default';
@endphp

<button
    type="button"
    role="switch"
    data-slot="switch"
    data-state="{{ $checked ? 'checked' : 'unchecked' }}"
    data-checked="{{ $checked ? 'true' : 'false' }}"
    aria-checked="{{ $checked ? 'true' : 'false' }}"
    @if($id) id="{{ $id }}" @endif
    @disabled($disabled)
    {{ $attributes->class(['app-switch', 'app-switch-'.$switchSize]) }}
>
    <span
        data-slot="switch-thumb"
        data-state="{{ $checked ? 'checked' : 'unchecked' }}"
        class="app-switch-thumb"
    ></span>

    @if($name)
        {{-- 켜진 상태에서만 name을 부여해 일반 체크박스와 같은 방식으로 전송 --}}
        <input
            type="hidden"
            data-switch-input
            data-name="{{ $name }}"
            @if($checked) name="{{ $name }}" @endif
            value="{{ $value }}"
        >
    @endif
</button>
