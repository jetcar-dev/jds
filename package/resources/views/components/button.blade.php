@props([
    'variant' => 'default',
    'size' => 'default',
    'href' => null,
    'type' => 'button',
    'as' => null,
    'color' => null,
    'colorForeground' => null,
    'disabled' => false,
    'isDisabled' => false,
])

@php
    $variant = ['primary' => 'default', 'danger' => 'destructive'][$variant] ?? $variant;
    $isButtonDisabled = (bool) ($disabled || $isDisabled);

    $variants = [
        'default' => 'app-button-default',
        'destructive' => 'app-button-destructive',
        'outline' => 'app-button-outline',
        'secondary' => 'app-button-secondary',
        'ghost' => 'app-button-ghost',
        'link' => 'app-button-link',
    ];

    $sizes = [
        'default' => 'app-button-default-size',
        'xs' => 'app-button-xs',
        'sm' => 'app-button-sm',
        'lg' => 'app-button-lg',
        'icon' => 'app-button-icon',
        'icon-xs' => 'app-button-icon-xs',
        'icon-sm' => 'app-button-icon-sm',
        'icon-lg' => 'app-button-icon-lg',
    ];

    $classes = 'app-button '
        . ($variants[$variant] ?? $variants['default']) . ' '
        . ($sizes[$size] ?? $sizes['default']);

    // as를 지정하면 해당 태그를 사용하고 없으면 href 유무로 링크와 버튼을 결정
    $tag = $as ?: ($href ? 'a' : 'button');

    // 개별 버튼 색상은 공통 색상값을 변경하지 않고 현재 버튼에만 적용
    $colorStyle = '';
    if ($color) {
        $foreground = $colorForeground ?: '#ffffff';
        $colorStyle = "--app-button-primary: {$color}; "
            . "--app-button-secondary: {$color}; "
            . "--app-button-ring: {$color}; "
            . "--app-button-primary-foreground: {$foreground};";
    }

    $userStyle = (string) $attributes->get('style', '');
    $style = trim($colorStyle . ($colorStyle && $userStyle ? ' ' : '') . $userStyle);
    $attributes = $attributes->except('style');
@endphp

<{{ $tag }}
    data-slot="button"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    @if($tag === 'a' && $href) href="{{ $href }}" @endif
    @if($tag === 'button') type="{{ $type }}" @endif
    @if($tag === 'button') @disabled($isButtonDisabled) @elseif($isButtonDisabled) aria-disabled="true" tabindex="-1" @endif
    @if($style) style="{{ $style }}" @endif
    {{ $attributes->class($classes) }}
>
    @isset($before){{ $before }}@endisset
    {{ $slot }}
    @isset($after){{ $after }}@endisset
</{{ $tag }}>
