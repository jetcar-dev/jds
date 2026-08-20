@props(['value', 'selected' => false, 'disabled' => false, 'href' => null])
@php $tag = $href ? 'a' : 'button'; @endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @else type="button" @endif
    data-slot="tab"
    data-value="{{ $value }}"
    data-selected="{{ $selected ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-item-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-ui-interactive
    role="tab"
    aria-selected="{{ $selected ? 'true' : 'false' }}"
    @if($disabled) aria-disabled="true" @endif
    tabindex="{{ $selected ? '0' : '-1' }}"
    @if(!$href) @disabled($disabled) @endif
    {{ $attributes->class('app-tab') }}
>
    <span data-slot="tabContent" class="app-tab-content">
        @isset($startContent)<span class="app-tab-start-content">{{ $startContent }}</span>@endisset
        {{ $slot }}
        @isset($endContent)<span class="app-tab-end-content">{{ $endContent }}</span>@endisset
    </span>
</{{ $tag }}>
