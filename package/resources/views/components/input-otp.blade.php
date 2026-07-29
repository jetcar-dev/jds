@props([
    'name' => null,
    'maxlength' => 6,
    'value' => '',
    'disabled' => false,
    'invalid' => false,
    'alphanumeric' => false,
    'ariaLabel' => '일회용 인증번호',
    'separatorAt' => null,
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
])

@php
    $inputmode = $alphanumeric ? 'text' : 'numeric';
    $pattern = $alphanumeric ? '[a-zA-Z0-9]*' : '[0-9]*';
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
@endphp

<div
    data-slot="input-otp"
    data-maxlength="{{ $maxlength }}"
    data-alphanumeric="{{ $alphanumeric ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    {{ $attributes->class(['app-input-otp', 'app-input-otp-'.$variant, 'app-input-otp-'.$size, 'app-color-'.$color]) }}
>
    <input
        value="{{ $value }}"
        maxlength="{{ $maxlength }}"
        inputmode="{{ $inputmode }}"
        autocomplete="one-time-code"
        pattern="{{ $pattern }}"
        aria-label="{{ $ariaLabel }}"
        aria-invalid="{{ $invalid ? 'true' : 'false' }}"
        @if($name) name="{{ $name }}" @endif
        @disabled($disabled)
        class="app-input-otp-control"
    >
    @if($slot->isEmpty())
        @php
            $separatorIndexes = array_values(array_unique(array_filter(
                array_map('intval', (array)($separatorAt ?? [])),
                fn ($index) => $index > 0 && $index < $maxlength,
            )));
            sort($separatorIndexes);
            $otpGroups = [];
            $groupStart = 0;
            foreach ([...$separatorIndexes, $maxlength] as $groupEnd) {
                $otpGroups[] = range($groupStart, $groupEnd - 1);
                $groupStart = $groupEnd;
            }
        @endphp
        @foreach($otpGroups as $group)
            <x-input-otp-group>
                @foreach($group as $index)
                    <x-input-otp-slot :index="$index" />
                @endforeach
            </x-input-otp-group>
            @unless($loop->last)<x-input-otp-separator />@endunless
        @endforeach
    @else
        {{ $slot }}
    @endif
</div>
