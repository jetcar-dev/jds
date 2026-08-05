@props(['name'=>null,'value'=>'','label'=>null,'description'=>null,'errorMessage'=>null,'variant'=>'flat','color'=>'default','hourCycle'=>24,'showSeconds'=>false,'size'=>'md','radius'=>'md','disabled'=>false,'required'=>false,'invalid'=>false])
@php [$hour,$minute,$second]=array_pad(explode(':',$value),3,''); @endphp
<div class="app-field app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }}">
    @if($label)<span class="app-field-label" data-required="{{ $required?'true':'false' }}">{{ $label }}</span>@endif
    <div data-slot="time-input" class="app-date-input" data-variant="{{ $variant }}" data-invalid="{{ $invalid?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" role="group" @if($label) aria-label="{{ $label }}" @endif>
        <input type="hidden" data-date-value @if($name) name="{{ $name }}" @endif value="{{ $value }}">
        <input data-date-segment class="app-date-segment" value="{{ $hour }}" maxlength="2" min="0" max="{{ $hourCycle===12?12:23 }}" placeholder="HH" aria-label="Hour" @disabled($disabled) @required($required)><span>:</span>
        <input data-date-segment class="app-date-segment" value="{{ $minute }}" maxlength="2" min="0" max="59" placeholder="MM" aria-label="Minute" @disabled($disabled) @required($required)>
        @if($showSeconds)<span>:</span><input data-date-segment class="app-date-segment" value="{{ $second }}" maxlength="2" min="0" max="59" placeholder="SS" aria-label="Second" @disabled($disabled) @required($required)>@endif
    </div>
    @if($description && !$invalid)<span class="app-field-description">{{ $description }}</span>@endif
    @if($errorMessage && $invalid)<span class="app-field-error">{{ $errorMessage }}</span>@endif
</div>
