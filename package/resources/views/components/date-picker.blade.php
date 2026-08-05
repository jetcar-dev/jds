@props(['name' => null, 'value' => null, 'label' => null, 'placeholder' => 'YYYY-MM-DD', 'variant' => 'flat', 'color' => 'default', 'size' => 'md', 'radius' => 'md', 'disabled' => false, 'invalid' => false, 'required' => false, 'minValue' => null, 'maxValue' => null, 'locale' => 'ko-KR', 'fullWidth' => false])
<div data-slot="date-picker" data-variant="{{ $variant }}" data-color="{{ $color }}" data-size="{{ $size }}" data-radius="{{ $radius }}" data-disabled="{{ $disabled ? 'true' : 'false' }}" data-invalid="{{ $invalid ? 'true' : 'false' }}" @class(['app-picker',"app-color-$color","app-size-$size","app-radius-$radius",'app-full-width'=>$fullWidth]) {{ $attributes }}>
    @if($label)<span data-slot="label" class="app-field-label">{{ $label }}</span>@endif
    <input type="hidden" data-picker-input name="{{ $name }}" value="{{ $value }}" @required($required)>
    <button type="button" data-slot="date-picker-trigger" data-variant="{{ $variant }}" data-invalid="{{ $invalid?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" class="app-input-wrapper" aria-haspopup="dialog" aria-expanded="false" @disabled($disabled)>
        <x-icon name="calendar-search-linear" data-slot="start-content" /><span data-picker-display @class(['app-picker-value','is-placeholder'=>!$value])>{{ $value ?: $placeholder }}</span>
    </button>
    <div data-slot="date-picker-popover" class="app-date-picker-popover" hidden><x-calendar :value="$value" :locale="$locale" :min-value="$minValue" :max-value="$maxValue" /></div>
</div>
