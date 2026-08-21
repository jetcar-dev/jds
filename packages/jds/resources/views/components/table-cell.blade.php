@props([
    'key' => null,
    'align' => 'start',
    'rowHeader' => false,
])
@php($align = in_array($align, ['start', 'center', 'end'], true) ? $align : 'start')
<td
    data-slot="td"
    data-key="{{ $key }}"
    data-align="{{ $align }}"
    data-row-header="{{ $rowHeader ? 'true' : 'false' }}"
    role="{{ $rowHeader ? 'rowheader' : 'gridcell' }}"
    tabindex="-1"
    {{ $attributes->class('app-table-cell') }}
><span class="app-table-cell-content">{{ $slot }}</span></td>
