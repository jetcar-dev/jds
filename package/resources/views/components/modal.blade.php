@props([
    'open' => false,
    'id' => null,
    'backdropVariant' => 'opaque',
    'scroll' => 'inside',
    'isDismissable' => true,
    'isKeyboardDismissDisabled' => false,
])
@php
    $backdropVariant = in_array($backdropVariant, ['opaque', 'blur', 'transparent'], true)
        ? $backdropVariant
        : 'opaque';
    $scroll = in_array($scroll, ['inside', 'outside'], true) ? $scroll : 'inside';
@endphp
<div
    data-slot="modal"
    data-modal-id="{{ $id }}"
    data-modal-initial-open="{{ $open ? 'true' : 'false' }}"
    data-backdrop-variant="{{ $backdropVariant }}"
    data-scroll="{{ $scroll }}"
    data-is-dismissable="{{ $isDismissable ? 'true' : 'false' }}"
    data-is-keyboard-dismiss-disabled="{{ $isKeyboardDismissDisabled ? 'true' : 'false' }}"
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->class('app-modal-root') }}
>
    {{ $slot }}
</div>
