@php
    $element = $tag();
    $separatorAttributes = $attributes
        ->except($isDecorative ? ['role', 'aria-hidden', 'aria-orientation'] : ['role', 'aria-orientation'])
        ->class($classes())
        ->merge(['data-slot' => 'separator', 'data-orientation' => $orientation]);
@endphp

<{{ $element }}
    @if ($isDecorative) role="presentation" aria-hidden="true"
    @else role="separator" @if ($orientation === 'vertical') aria-orientation="vertical" @endif
    @endif
    {{ $separatorAttributes }}
>@if ($element !== 'hr')</{{ $element }}>@endif
