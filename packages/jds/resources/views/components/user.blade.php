@props([
    'name',
    'description' => null,
    'avatar' => null,
    'focusable' => false,
    'avatarAttributes' => [],
    'classNames' => [],
])

@php
    $isFocusable = filter_var($focusable, FILTER_VALIDATE_BOOL);
    $avatarAttributes = new \Illuminate\View\ComponentAttributeBag((array) $avatarAttributes);
    $avatarSrc = $avatarAttributes->get('src', $avatar);
    $avatarName = $avatarAttributes->get('name', is_scalar($name) ? (string) $name : null);
    $avatarAlt = $avatarAttributes->get('alt');
    $avatarSize = $avatarAttributes->get('size', 'md');
    $avatarRadius = $avatarAttributes->get('radius', 'full');
    $avatarColor = $avatarAttributes->get('color', 'default');
    $avatarBordered = filter_var($avatarAttributes->get('bordered', false), FILTER_VALIDATE_BOOL);
    $avatarDisabled = filter_var($avatarAttributes->get('disabled', false), FILTER_VALIDATE_BOOL);
    $avatarFocusable = filter_var($avatarAttributes->get('focusable', false), FILTER_VALIDATE_BOOL);
    $avatarShowFallback = filter_var($avatarAttributes->get('show-fallback', $avatarAttributes->get('showFallback', false)), FILTER_VALIDATE_BOOL);
    $avatarDisableAnimation = filter_var($avatarAttributes->get('disable-animation', $avatarAttributes->get('disableAnimation', false)), FILTER_VALIDATE_BOOL);
    $avatarImageAttributes = $avatarAttributes->get('image-attributes', $avatarAttributes->get('imageAttributes', []));
    $avatarClass = $avatarAttributes->get('class', '');
    $classNames = is_array($classNames) ? $classNames : [];
    $descriptionContent = $description instanceof \Illuminate\View\ComponentSlot ? $description : null;
@endphp

<div
    data-ui-component="user"
    data-slot="base"
    data-focusable="{{ $isFocusable ? 'true' : 'false' }}"
    tabindex="{{ $isFocusable ? '0' : '-1' }}"
    @if($isFocusable) data-ui-interactive @endif
    {{ $attributes->class(['app-user', $classNames['base'] ?? null]) }}
>
    <x-avatar
        :src="$avatarSrc"
        :name="$avatarName"
        :alt="$avatarAlt"
        :size="$avatarSize"
        :radius="$avatarRadius"
        :color="$avatarColor"
        :bordered="$avatarBordered"
        :disabled="$avatarDisabled"
        :focusable="$avatarFocusable"
        :show-fallback="$avatarShowFallback"
        :disable-animation="$avatarDisableAnimation"
        :image-attributes="$avatarImageAttributes"
        :class="$avatarClass"
    />

    <div data-slot="wrapper" class="app-user-wrapper {{ $classNames['wrapper'] ?? '' }}">
        <span data-slot="name" class="app-user-name {{ $classNames['name'] ?? '' }}">@isset($nameContent){{ $nameContent }}@else{{ $name }}@endisset</span>

        @if($descriptionContent || $description !== null)
            <span data-slot="description" class="app-user-description {{ $classNames['description'] ?? '' }}">
                @if($descriptionContent){{ $descriptionContent }}@else{{ $description }}@endif
            </span>
        @endif
    </div>
</div>
