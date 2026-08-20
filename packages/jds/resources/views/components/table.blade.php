@props(['selectionMode' => 'none', 'color' => 'primary', 'removeWrapper' => false, 'striped' => false, 'compact' => false])
@if(!$removeWrapper)<div data-slot="base" class="app-table-wrapper">@endif
<table data-slot="table" data-selection-mode="{{ $selectionMode }}" data-color="{{ $color }}" data-striped="{{ $striped?'true':'false' }}" data-compact="{{ $compact?'true':'false' }}" {{ $attributes->class("app-table app-color-$color") }}>{{ $slot }}</table>
@if(!$removeWrapper)</div>@endif
