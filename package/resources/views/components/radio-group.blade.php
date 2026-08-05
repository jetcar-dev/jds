@props(['name'=>null,'value'=>'','label'=>null,'description'=>null,'orientation'=>'vertical','color'=>'primary','disabled'=>false,'required'=>false,'invalid'=>false,'errorMessage'=>null])
<fieldset data-slot="radio-group" role="radiogroup" data-orientation="{{ $orientation }}" data-disabled="{{ $disabled?'true':'false' }}" data-invalid="{{ $invalid?'true':'false' }}" @if($required) aria-required="true" @endif {{ $attributes->class("app-choice-group app-color-$color") }}>
    @if($label)<legend class="app-field-label" data-required="{{ $required?'true':'false' }}">{{ $label }}</legend>@endif
    @if($description)<div class="app-field-description">{{ $description }}</div>@endif
    <input type="hidden" data-radio-input @if($name) name="{{ $name }}" @endif value="{{ $value }}">
    {{ $slot }}
    @if($invalid && $errorMessage)<div class="app-field-error">{{ $errorMessage }}</div>@endif
</fieldset>
