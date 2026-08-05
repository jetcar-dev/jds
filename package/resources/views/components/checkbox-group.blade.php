@props(['label'=>null,'description'=>null,'orientation'=>'vertical','color'=>'primary','invalid'=>false,'errorMessage'=>null])
<fieldset data-slot="checkbox-group" data-orientation="{{ $orientation }}" data-invalid="{{ $invalid?'true':'false' }}" {{ $attributes->class("app-choice-group app-color-$color") }}>
    @if($label)<legend class="app-field-label">{{ $label }}</legend>@endif
    @if($description)<div class="app-field-description">{{ $description }}</div>@endif
    {{ $slot }}
    @if($invalid && $errorMessage)<div class="app-field-error">{{ $errorMessage }}</div>@endif
</fieldset>
