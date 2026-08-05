@props(['name'=>null,'value'=>'on','checked'=>false,'indeterminate'=>false,'disabled'=>false,'color'=>'primary','size'=>'md','label'=>null,'description'=>null])
<label data-slot="checkbox" data-ui-component="checkbox" data-value="{{ $value }}" data-selected="{{ $checked?'true':'false' }}" data-indeterminate="{{ $indeterminate?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" {{ $attributes->class("app-checkbox-label app-color-$color") }}>
    <input class="app-sr-only" type="checkbox" @if($name) name="{{ $name }}" @endif value="{{ $value }}" @checked($checked) @disabled($disabled)>
    <span data-slot="checkbox-control" class="app-checkbox" aria-hidden="true"><svg class="app-checkbox-indicator" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 12 4 4L19 6"/></svg></span>
    @if($label || !$slot->isEmpty())<span class="app-choice-copy"><span>{{ $label ?? $slot }}</span>@if($description)<span class="app-choice-description">{{ $description }}</span>@endif</span>@endif
</label>
