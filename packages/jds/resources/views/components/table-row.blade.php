@props([
    'key' => null,
    'selected' => false,
    'disabled' => false,
    'selectable' => null,
    'textValue' => null,
    'action' => false,
])
<tr
    data-slot="tr"
    data-key="{{ $key }}"
    data-selected="{{ $selected ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-selectable="{{ $selectable === null ? 'auto' : ($selectable ? 'true' : 'false') }}"
    data-text-value="{{ $textValue }}"
    data-action="{{ $action ? 'true' : 'false' }}"
    role="row"
    aria-selected="{{ $selected ? 'true' : 'false' }}"
    @if($disabled) aria-disabled="true" @endif
    tabindex="-1"
    {{ $attributes->class('app-table-row') }}
>{{ $slot }}</tr>
