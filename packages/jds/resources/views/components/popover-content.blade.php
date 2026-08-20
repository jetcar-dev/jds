@props([
    'placement' => null,
    'offset' => null,
    'crossOffset' => null,
    'showArrow' => null,
])

<div
    data-popover-content
    data-slot="base"
    role="{{ $attributes->get('role', 'dialog') }}"
    tabindex="{{ $attributes->get('tabindex', '-1') }}"
    @if($attributes->has('aria-label')) aria-label="{{ $attributes->get('aria-label') }}" @endif
    @if($attributes->has('aria-labelledby')) aria-labelledby="{{ $attributes->get('aria-labelledby') }}" @endif
    @if($attributes->has('aria-describedby')) aria-describedby="{{ $attributes->get('aria-describedby') }}" @endif
    @if($placement) data-placement="{{ $placement }}" @endif
    @if(!is_null($offset)) data-offset="{{ (float) $offset }}" @endif
    @if(!is_null($crossOffset)) data-cross-offset="{{ (float) $crossOffset }}" @endif
    @if(!is_null($showArrow)) data-show-arrow="{{ $showArrow ? 'true' : 'false' }}" @endif
    data-open="false"
    data-state="closed"
    class="app-popover-base"
    hidden
>
    <span data-slot="arrow" class="app-popover-arrow" aria-hidden="true"></span>
    <div
        data-slot="content"
        {{ $attributes->except(['role', 'tabindex', 'aria-label', 'aria-labelledby', 'aria-describedby'])->class('app-popover-content') }}
    >{{ $slot }}</div>
</div>
