@props([
    'keys' => [],
    'platform' => 'mac',
])
@php
    $keyMap = [
        'command' => ['symbol' => '⌘', 'label' => 'Command'],
        'shift' => ['symbol' => '⇧', 'label' => 'Shift'],
        'ctrl' => ['symbol' => '⌃', 'label' => 'Control'],
        'option' => ['symbol' => '⌥', 'label' => 'Option'],
        'enter' => ['symbol' => '↵', 'label' => 'Enter'],
        'delete' => ['symbol' => '⌫', 'label' => 'Delete'],
        'escape' => ['symbol' => '⎋', 'label' => 'Escape'],
        'tab' => ['symbol' => '⇥', 'label' => 'Tab'],
        'capslock' => ['symbol' => '⇪', 'label' => 'Caps Lock'],
        'up' => ['symbol' => '↑', 'label' => 'Up'],
        'right' => ['symbol' => '→', 'label' => 'Right'],
        'down' => ['symbol' => '↓', 'label' => 'Down'],
        'left' => ['symbol' => '←', 'label' => 'Left'],
        'pageup' => ['symbol' => '⇞', 'label' => 'Page Up'],
        'pagedown' => ['symbol' => '⇟', 'label' => 'Page Down'],
        'home' => ['symbol' => '↖', 'label' => 'Home'],
        'end' => ['symbol' => '↘', 'label' => 'End'],
        'help' => ['symbol' => '?', 'label' => 'Help'],
        'space' => ['symbol' => '␣', 'label' => 'Space'],
        'fn' => ['symbol' => 'Fn', 'label' => 'Fn'],
        'win' => ['symbol' => '⌘', 'label' => 'Win'],
        'alt' => ['symbol' => '⌥', 'label' => 'Alt'],
    ];
    $windowsKeyMap = [
        'command' => ['symbol' => 'Win', 'label' => 'Windows'],
        'shift' => ['symbol' => 'Shift', 'label' => 'Shift'],
        'ctrl' => ['symbol' => 'Ctrl', 'label' => 'Control'],
        'option' => ['symbol' => 'Alt', 'label' => 'Alt'],
        'enter' => ['symbol' => 'Enter', 'label' => 'Enter'],
        'delete' => ['symbol' => 'Del', 'label' => 'Delete'],
        'escape' => ['symbol' => 'Esc', 'label' => 'Escape'],
        'tab' => ['symbol' => 'Tab', 'label' => 'Tab'],
        'capslock' => ['symbol' => 'Caps', 'label' => 'Caps Lock'],
        'pageup' => ['symbol' => 'PgUp', 'label' => 'Page Up'],
        'pagedown' => ['symbol' => 'PgDn', 'label' => 'Page Down'],
        'space' => ['symbol' => 'Space', 'label' => 'Space'],
        'win' => ['symbol' => 'Win', 'label' => 'Windows'],
        'alt' => ['symbol' => 'Alt', 'label' => 'Alt'],
    ];
    if ($platform === 'windows') {
        $keyMap = array_replace($keyMap, $windowsKeyMap);
    }
    $keysToRender = is_array($keys) ? $keys : (filled($keys) ? [$keys] : []);
    $keysToRender = array_values(array_filter($keysToRender, fn ($key) => isset($keyMap[$key])));
    $hasContent = trim((string) $slot) !== '';
@endphp

<kbd
    data-ui-component="kbd"
    data-slot="base"
    data-platform="{{ $platform }}"
    {{ $attributes->class('app-kbd') }}
>
    @foreach($keysToRender as $key)
        <abbr data-slot="abbr" class="app-kbd-abbr" title="{{ $keyMap[$key]['label'] }}">{{ $keyMap[$key]['symbol'] }}</abbr>
        @if($platform === 'windows' && (!$loop->last || $hasContent))
            <span data-slot="separator" class="app-kbd-separator" aria-hidden="true">
                <x-icon name="material-symbols:add-rounded" />
            </span>
        @endif
    @endforeach
    @if($hasContent)
        <span data-slot="content" class="app-kbd-content">{{ $slot }}</span>
    @endif
</kbd>
