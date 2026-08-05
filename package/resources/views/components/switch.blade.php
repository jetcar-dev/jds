@props(['name'=>null,'value'=>'1','checked'=>false,'disabled'=>false,'color'=>'primary','size'=>'md','label'=>null,'description'=>null])
<label data-slot="switch" data-ui-component="switch" data-selected="{{ $checked?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" {{ $attributes->class("app-switch-label app-color-$color") }}>
    <input class="app-sr-only" type="checkbox" role="switch" @if($name) name="{{ $name }}" @endif value="{{ $value }}" @checked($checked) @disabled($disabled)>
    <span data-slot="switch-control" class="app-switch" aria-hidden="true"><span class="app-switch-thumb"></span></span>
    @if($label || !$slot->isEmpty())<span class="app-choice-copy"><span>{{ $label ?? $slot }}</span>@if($description)<span class="app-choice-description">{{ $description }}</span>@endif</span>@endif
</label>
