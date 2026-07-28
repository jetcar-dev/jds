@props([
    'name' => null,
    'value' => null,
    'options' => null,
    'orientation' => 'vertical',
])

@php
    $orientation = $orientation === 'horizontal' ? 'horizontal' : 'vertical';
    $normalizedOptions = [];
    if (is_iterable($options)) {
        foreach ($options as $optionValue => $option) {
            if (is_array($option)) {
                $normalizedOptions[] = [
                    'value' => (string)($option['value'] ?? $optionValue),
                    'label' => (string)($option['label'] ?? $optionValue),
                    'description' => $option['description'] ?? null,
                    'disabled' => (bool)($option['disabled'] ?? false),
                ];
            } else {
                $normalizedOptions[] = [
                    'value' => (string)$optionValue,
                    'label' => (string)$option,
                    'description' => null,
                    'disabled' => false,
                ];
            }
        }
    }
@endphp

<div
    data-slot="radio-group"
    data-value="{{ $value }}"
    data-has-value="{{ $value === null ? 'false' : 'true' }}"
    role="radiogroup"
    {{ $attributes->class(['app-radio-group', 'app-radio-group-horizontal' => $orientation === 'horizontal']) }}
>
    @if($name)
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ $value }}"
            data-radio-group-input
        >
    @endif

    @if(count($normalizedOptions))
        @foreach($normalizedOptions as $option)
            <label class="app-radio-option">
                <x-radio-group-item :value="$option['value']" :disabled="$option['disabled']" />
                <span class="app-radio-option-copy">
                    <span>{{ $option['label'] }}</span>
                    @if($option['description'])
                        <small>{{ $option['description'] }}</small>
                    @endif
                </span>
            </label>
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>
