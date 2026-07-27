@props(['messages' => null])

@php
    $items = is_array($messages) ? $messages : (($messages !== null && $messages !== '') ? [$messages] : []);
    $items = array_values(array_unique(array_filter(array_map(fn ($item) => is_array($item) ? ($item['message'] ?? '') : $item, $items))));
@endphp

@if(trim($slot) !== '' || count($items))
    <div role="alert" data-slot="field-error" {{ $attributes->class('app-field-error') }}>
        @if(trim($slot) !== ''){{ $slot }}@elseif(count($items) === 1){{ $items[0] }}@else<ul>@foreach($items as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
    </div>
@endif
