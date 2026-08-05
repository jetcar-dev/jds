@props(['id'=>null,'name'=>null,'type'=>'text','value'=>'','label'=>null,'placeholder'=>null,'description'=>null,'errorMessage'=>null,'variant'=>'flat','color'=>'default','size'=>'md','radius'=>'md','labelPlacement'=>'outside','disabled'=>false,'readOnly'=>false,'required'=>false,'invalid'=>false,'clearable'=>false,'passwordToggle'=>false,'fullWidth'=>true])
@php $id=$id?:'input-'.uniqid();$fieldAttrs=$attributes->except('class'); @endphp
<div data-slot="input-base" class="app-field app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }}" data-invalid="{{ $invalid?'true':'false' }}" data-required="{{ $required?'true':'false' }}" style="{{ $fullWidth?'width:100%;':'width:fit-content;' }}">
    @if($label && $labelPlacement!=='inside')<label data-slot="label" for="{{ $id }}" data-required="{{ $required?'true':'false' }}" class="app-field-label">{{ $label }}</label>@endif
    <div data-slot="input-wrapper" data-variant="{{ $variant }}" data-invalid="{{ $invalid?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" {{ $attributes->only('class')->class('app-input-wrapper') }}>
        @isset($startContent)<span data-slot="start-content">{{ $startContent }}</span>@endisset
        <span class="app-input-inner">@if($label && $labelPlacement==='inside')<label data-slot="label" for="{{ $id }}" data-required="{{ $required?'true':'false' }}" class="app-input-label-inside">{{ $label }}</label>@endif<input data-slot="input" id="{{ $id }}" class="app-input" @if($name) name="{{ $name }}" @endif type="{{ $type }}" value="{{ $value }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif @disabled($disabled) @readonly($readOnly) @required($required) @if($invalid) aria-invalid="true" @endif @if($description) aria-describedby="{{ $id }}-description" @endif @if($errorMessage) aria-errormessage="{{ $id }}-error" @endif {{ $fieldAttrs }}></span>
        @if($clearable)<button type="button" data-input-clear class="app-input-clear" aria-label="Clear" hidden>×</button>@endif
        @if($passwordToggle)<button type="button" data-password-toggle class="app-input-clear" aria-label="Toggle password visibility"><x-icon name="eye-linear" /></button>@endif
        @isset($endContent)<span data-slot="end-content">{{ $endContent }}</span>@endisset
    </div>
    @if($description && !$invalid)<div id="{{ $id }}-description" data-slot="description" class="app-field-description">{{ $description }}</div>@endif
    @if($errorMessage && $invalid)<div id="{{ $id }}-error" data-slot="error-message" class="app-field-error">{{ $errorMessage }}</div>@endif
</div>
