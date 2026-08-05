@props(['name'=>null,'value'=>'','label'=>null,'placeholder'=>'선택하세요','description'=>null,'errorMessage'=>null,'selectionMode'=>'single','variant'=>'flat','color'=>'default','size'=>'md','radius'=>'md','disabled'=>false,'required'=>false,'invalid'=>false,'clearable'=>false,'placement'=>'bottom-start','fullWidth'=>true])
@php $encoded=$selectionMode==='multiple'?json_encode((array)$value,JSON_UNESCAPED_UNICODE):(string)$value; @endphp
<div data-slot="select" data-ui-component="select" data-selection-mode="{{ $selectionMode }}" data-invalid="{{ $invalid?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" class="app-select app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }}" style="{{ $fullWidth?'width:100%;':'width:fit-content;' }}" {{ $attributes }}>
    @if($label)<label class="app-field-label" data-required="{{ $required?'true':'false' }}">{{ $label }}</label>@endif
    <input type="hidden" data-select-input @if($name && $selectionMode!=='multiple') name="{{ $name }}" @endif value="{{ $encoded }}" @required($required)>
    @if($selectionMode==='multiple')<span data-select-form-values data-name="{{ $name }}">@foreach((array)$value as $selectedValue)<input type="hidden" name="{{ $name }}[]" value="{{ $selectedValue }}">@endforeach</span>@endif
    <button type="button" data-slot="select-trigger" data-variant="{{ $variant }}" class="app-select-trigger" aria-haspopup="listbox" aria-expanded="false" @disabled($disabled)>
        @isset($startContent){{ $startContent }}@endisset
        <span data-slot="select-value" data-placeholder="{{ $value===''?'true':'false' }}" data-placeholder-text="{{ $placeholder }}" class="app-select-value">{{ $displayValue ?? $placeholder }}</span>
        @isset($endContent){{ $endContent }}@endisset
        <span aria-hidden="true">⌄</span>
    </button>
    @if($description && !$invalid)<div class="app-field-description">{{ $description }}</div>@endif
    @if($errorMessage && $invalid)<div class="app-field-error">{{ $errorMessage }}</div>@endif
    <div data-slot="select-popover" data-placement="{{ $placement }}" class="app-select-popover" hidden><div data-slot="listbox" data-collection-owned="true" data-selection-mode="{{ $selectionMode }}" class="app-listbox app-color-{{ $color }}" role="listbox" @if($selectionMode==='multiple') aria-multiselectable="true" @endif>{{ $slot }}</div></div>
</div>
