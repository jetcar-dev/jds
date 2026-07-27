@props([
    'mask' => '',
    'value' => '',
    'id' => null,
    'name' => null,
    'placeholder' => null,
    'inputmode' => null,
])

<input
    type="text"
    data-slot="input"
    data-size="default"
    data-mask="{{ $mask }}"
    value="{{ $value }}"
    @if($id) id="{{ $id }}" @endif
    @if($name) name="{{ $name }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($inputmode) inputmode="{{ $inputmode }}" @endif
    {{ $attributes->class('app-input app-input-default') }}
/>
