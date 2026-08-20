@props([
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'lg',
    'symbol' => '$',
    'codeString' => null,
    'hideSymbol' => false,
    'hideCopyButton' => false,
    'disableCopy' => false,
    'disableTooltip' => false,
    'disableAnimation' => false,
    'timeout' => 2000,
    'tooltip' => '복사',
])

@php
    $variant = in_array($variant, ['flat', 'bordered', 'solid', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg'], true) ? $radius : 'lg';
    $timeout = max(0, (int) $timeout);

    if (is_array($codeString)) {
        $lines = array_map(static fn ($line) => (string) $line, $codeString);
    } else {
        $source = $codeString !== null ? (string) $codeString : trim((string) $slot);
        $lines = preg_split('/\R/u', $source) ?: [''];
    }
@endphp

<div
    data-slot="base"
    data-ui-component="snippet"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-copied="false"
    data-disable-copy="{{ $disableCopy ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-timeout="{{ $timeout }}"
    {{ $attributes->class("app-snippet app-color-$color app-size-$size app-radius-$radius") }}
>
    <div data-slot="content" class="app-snippet-content" data-snippet-value>
        @foreach($lines as $line)
            <pre data-slot="pre" class="app-snippet-pre">@unless($hideSymbol)<span data-slot="symbol" class="app-snippet-symbol">{{ $symbol }}</span>@endunless<code class="app-snippet-code">{{ $line }}</code></pre>
        @endforeach
    </div>

    @unless($hideCopyButton)
        <span @unless($disableTooltip) data-slot="tooltip-root" data-delay="500" @endunless class="app-snippet-copy-wrap">
            <span @unless($disableTooltip) data-slot="tooltip-trigger" @endunless class="app-snippet-copy-trigger">
                <button
                    type="button"
                    data-slot="copy-button"
                    data-snippet-copy
                    class="app-snippet-copy"
                    aria-label="{{ $disableCopy ? '복사할 수 없음' : '클립보드에 복사' }}"
                    @disabled($disableCopy)
                >
                    <span data-slot="copy-icon" class="app-snippet-copy-icon">
                        @isset($copyIcon)
                            {{ $copyIcon }}
                        @else
                            <x-icon name="copy-linear" />
                        @endisset
                    </span>
                    <span data-slot="check-icon" class="app-snippet-check-icon" hidden>
                        @isset($checkIcon)
                            {{ $checkIcon }}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m5 12 4 4L19 6" />
                            </svg>
                        @endisset
                    </span>
                </button>
            </span>
            @unless($disableTooltip)
                <span data-slot="tooltip" data-placement="top" class="app-tooltip" role="tooltip" hidden>{{ $tooltip }}</span>
            @endunless
        </span>
    @endunless
</div>
