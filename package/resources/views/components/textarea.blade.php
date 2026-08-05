@props(['id'=>null,'name'=>null,'value'=>'','label'=>null,'placeholder'=>null,'description'=>null,'errorMessage'=>null,'variant'=>'flat','color'=>'default','size'=>'md','radius'=>'md','labelPlacement'=>'outside','disabled'=>false,'readOnly'=>false,'required'=>false,'invalid'=>false,'minRows'=>3,'maxRows'=>8,'fullWidth'=>true])
@php $id=$id?:'textarea-'.uniqid();$fieldAttrs=$attributes->except('class'); @endphp
<div data-slot="textarea-base" class="app-field app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }}" style="{{ $fullWidth?'width:100%;':'width:fit-content;' }}">
    @if($label && $labelPlacement!=='inside')<label for="{{ $id }}" data-required="{{ $required?'true':'false' }}" class="app-field-label">{{ $label }}</label>@endif
    <div data-slot="input-wrapper" data-variant="{{ $variant }}" data-invalid="{{ $invalid?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" {{ $attributes->only('class')->class('app-input-wrapper app-textarea-wrapper') }}>
        <span class="app-input-inner">
            @if($label && $labelPlacement==='inside')<label for="{{ $id }}" class="app-input-label-inside">{{ $label }}</label>@endif
            <textarea data-slot="textarea" id="{{ $id }}" class="app-textarea-control" @if($name) name="{{ $name }}" @endif rows="{{ $minRows }}" style="max-height:{{ $maxRows*1.5 }}rem" @if($placeholder) placeholder="{{ $placeholder }}" @endif @disabled($disabled) @readonly($readOnly) @required($required) @if($invalid) aria-invalid="true" @endif {{ $fieldAttrs }}>{{ $value }}</textarea>
        </span>
    </div>
    @if($description && !$invalid)<div class="app-field-description">{{ $description }}</div>@endif
    @if($errorMessage && $invalid)<div class="app-field-error">{{ $errorMessage }}</div>@endif
</div>
