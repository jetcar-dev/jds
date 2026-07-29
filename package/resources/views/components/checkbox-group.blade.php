@props([
    'name' => null,
    'value' => [],
    'options' => null,
    'orientation' => 'vertical',
    'variant' => 'default',
    'showIndicator' => true,
    'label' => null,
    'description' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $orientation = $orientation === 'horizontal' ? 'horizontal' : 'vertical';
    $variant = $variant === 'box' ? 'box' : 'default';
    $selected = is_array($value) ? array_map('strval', $value) : [(string) $value];
@endphp

<fieldset
    data-slot="checkbox-group"
    data-variant="{{ $variant }}"
    data-show-indicator="{{ $showIndicator ? 'true' : 'false' }}"
    @disabled($disabled)
    {{ $attributes->class(['app-checkbox-group', 'app-checkbox-group-'.$orientation, 'app-checkbox-group-'.$variant, 'app-checkbox-group-invalid' => $error, 'app-checkbox-group-disabled' => $disabled]) }}
>
    @if($label)<legend class="app-checkbox-group-label">{{ $label }}@if($required)<span class="app-checkbox-group-required">*</span>@endif</legend>@endif
    @if($description)<p class="app-checkbox-group-description">{{ $description }}</p>@endif
    <div class="app-checkbox-group-items">
        @if(is_iterable($options))
            @foreach($options as $optionValue => $option)
                @php
                    $data = is_array($option) ? $option : ['label' => $option];
                    $itemValue = (string) ($data['value'] ?? $optionValue);
                @endphp
                <x-checkbox
                    :name="$name ? $name.'[]' : null"
                    :value="$itemValue"
                    :checked="in_array($itemValue, $selected, true)"
                    :label="$data['label'] ?? $itemValue"
                    :description="$data['description'] ?? null"
                    :disabled="$disabled || ($data['disabled'] ?? false)"
                    :variant="$variant"
                    :show-indicator="$showIndicator"
                />
            @endforeach
        @else
            {{ $slot }}
        @endif
    </div>
    @if($error)<p class="app-checkbox-group-error">{{ $error }}</p>@endif
</fieldset>
