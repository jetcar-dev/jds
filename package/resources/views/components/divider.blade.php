@props([
    'orientation' => 'horizontal',
])

@php
    $orientation = $orientation === 'vertical' ? 'vertical' : 'horizontal';
    $dividerAttributes = $attributes->class('app-divider')->merge([
        'data-slot' => 'base',
        'data-ui-component' => 'divider',
        'data-orientation' => $orientation,
        'role' => 'separator',
        'aria-orientation' => $orientation,
    ]);
@endphp

@if($orientation === 'vertical')
    <div {{ $dividerAttributes }}></div>
@else
    <hr {{ $dividerAttributes }} />
@endif
