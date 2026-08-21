@props([
    'emptyContent' => null,
    'loading' => false,
    'loadingContent' => null,
])
@php($isEmpty = trim((string) $slot) === '')
<tbody
    data-slot="tbody"
    data-empty="{{ $isEmpty ? 'true' : 'false' }}"
    data-loading="{{ $loading ? 'true' : 'false' }}"
    role="rowgroup"
    {{ $attributes->class('app-table-body') }}
>
    {{ $slot }}
    @if($isEmpty && $emptyContent)
        <tr data-slot="empty-row" class="app-table-empty-row" role="row">
            <td data-slot="emptyWrapper" class="app-table-empty" role="gridcell" colspan="100">{{ $emptyContent }}</td>
        </tr>
    @endif
    @if($loading)
        <tr data-slot="loading-row" class="app-table-loading-row" role="row">
            <td data-slot="loadingWrapper" class="app-table-loading" role="gridcell" colspan="100">
                @if($loadingContent)
                    {{ $loadingContent }}
                @else
                    <span class="app-table-loading-spinner" aria-hidden="true"></span>
                    <span>불러오는 중</span>
                @endif
            </td>
        </tr>
    @endif
</tbody>
