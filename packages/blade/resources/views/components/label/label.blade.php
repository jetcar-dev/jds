<label {{ $attributes->class([
    'jds-label',
    'jds-label--required' => $isRequired,
    'jds-label--disabled' => $isDisabled,
    'jds-label--invalid' => $isInvalid,
])->merge(['data-slot' => 'label']) }}>{{ $slot }}</label>
