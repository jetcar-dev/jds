@props([
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
        {{ $slot }}
    </div>
    @if($error)<p class="app-checkbox-group-error">{{ $error }}</p>@endif
</fieldset>
