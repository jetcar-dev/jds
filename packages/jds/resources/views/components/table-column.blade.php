@props([
    'key' => null,
    'align' => 'start',
    'allowsSorting' => false,
    'isRowHeader' => false,
    'hideHeader' => false,
    'width' => null,
    'minWidth' => null,
    'maxWidth' => null,
])
@php
    $align = in_array($align, ['start', 'center', 'end'], true) ? $align : 'start';
    $dimension = static fn ($value) => is_numeric($value) ? $value.'px' : $value;
    $styles = array_filter([
        $width !== null ? 'width: '.$dimension($width) : null,
        $minWidth !== null ? 'min-width: '.$dimension($minWidth) : null,
        $maxWidth !== null ? 'max-width: '.$dimension($maxWidth) : null,
        $attributes->get('style'),
    ]);
@endphp
<th
    data-slot="th"
    data-key="{{ $key }}"
    data-align="{{ $align }}"
    data-sortable="{{ $allowsSorting ? 'true' : 'false' }}"
    data-row-header="{{ $isRowHeader ? 'true' : 'false' }}"
    data-hide-header="{{ $hideHeader ? 'true' : 'false' }}"
    scope="col"
    role="columnheader"
    @if($allowsSorting) tabindex="0" aria-sort="none" @endif
    @if(count($styles)) style="{{ implode('; ', $styles) }}" @endif
    {{ $attributes->except('style')->class('app-table-column') }}
>
    <span data-slot="column-content" class="app-table-column-content">{{ $slot }}</span>
    @if($allowsSorting)
        <span data-slot="sort-icon" class="app-table-sort-icon" aria-hidden="true">
            @isset($sortIcon)
                {{ $sortIcon }}
            @else
                <x-icon name="solar:alt-arrow-up-linear" />
            @endisset
        </span>
    @endif
</th>
