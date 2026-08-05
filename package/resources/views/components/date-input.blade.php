@props(['name'=>null,'value'=>'','label'=>null,'description'=>null,'errorMessage'=>null,'variant'=>'flat','color'=>'default','size'=>'md','radius'=>'md','disabled'=>false,'required'=>false,'invalid'=>false])
@php [$year,$month,$day]=array_pad(explode('-',$value),3,''); @endphp
<div class="app-field app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }}">
    @if($label)<span class="app-field-label" data-required="{{ $required?'true':'false' }}">{{ $label }}</span>@endif
    <div data-slot="date-input" class="app-date-input" data-variant="{{ $variant }}" data-invalid="{{ $invalid?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" role="group" @if($label) aria-label="{{ $label }}" @endif>
        <input type="hidden" data-date-value @if($name) name="{{ $name }}" @endif value="{{ $value }}">
        <input data-date-segment class="app-date-segment" value="{{ $year }}" maxlength="4" min="1" max="9999" placeholder="YYYY" aria-label="Year" @disabled($disabled) @required($required)><span>/</span>
        <input data-date-segment class="app-date-segment" value="{{ $month }}" maxlength="2" min="1" max="12" placeholder="MM" aria-label="Month" @disabled($disabled) @required($required)><span>/</span>
        <input data-date-segment class="app-date-segment" value="{{ $day }}" maxlength="2" min="1" max="31" placeholder="DD" aria-label="Day" @disabled($disabled) @required($required)>
    </div>
    @if($description && !$invalid)<span class="app-field-description">{{ $description }}</span>@endif
    @if($errorMessage && $invalid)<span class="app-field-error">{{ $errorMessage }}</span>@endif
</div>
