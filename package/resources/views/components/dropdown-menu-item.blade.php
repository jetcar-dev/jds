@props([
    'href' => null,
    'variant' => 'default',
    'inset' => false,
    'disabled' => false,
    'closeOnSelect' => true,
    'type' => 'button',
])

@php $tag = $href ? 'a' : 'button'; @endphp

<{{ $tag }}
    @if($tag === 'a') href="{{ $href }}" @else type="{{ $type }}" @endif
    role="menuitem"
    tabindex="-1"
    data-slot="dropdown-menu-item"
    data-close-on-select="{{ $closeOnSelect ? 'true' : 'false' }}"
    @if($inset) data-inset @endif
    @disabled($disabled)
    @if($disabled) aria-disabled="true" @endif
    {{ $attributes->class(['app-dropdown-item', 'app-dropdown-item-danger' => $variant === 'destructive']) }}
>
    {{ $slot }}
</{{ $tag }}>
