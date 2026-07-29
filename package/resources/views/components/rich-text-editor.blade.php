@props([
    'name' => null,
    'value' => '',
    'placeholder' => 'Write something…',
    'id' => null,
    'variant' => 'flat',
    'fullWidth' => false,
])

@php
    $editorId = $id ?: 'rte-' . \Illuminate\Support\Str::random(8);
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['flat', 'faded', 'bordered'], true) ? $variant : 'flat';
    $labelId = $editorId . '-label';
    $tools = [
        ['action' => 'command', 'value' => 'bold', 'state' => 'bold', 'icon' => 'lucide:bold', 'label' => 'Bold'],
        ['action' => 'command', 'value' => 'italic', 'state' => 'italic', 'icon' => 'lucide:italic', 'label' => 'Italic'],
        ['action' => 'command', 'value' => 'underline', 'state' => 'underline', 'icon' => 'lucide:underline', 'label' => 'Underline'],
        ['action' => 'command', 'value' => 'strikeThrough', 'state' => 'strikeThrough', 'icon' => 'lucide:strikethrough', 'label' => 'Strikethrough'],
        ['separator' => true],
        ['action' => 'block', 'value' => 'h1', 'icon' => 'lucide:heading-1', 'label' => 'Heading 1'],
        ['action' => 'block', 'value' => 'h2', 'icon' => 'lucide:heading-2', 'label' => 'Heading 2'],
        ['separator' => true],
        ['action' => 'command', 'value' => 'insertUnorderedList', 'state' => 'insertUnorderedList', 'icon' => 'lucide:list', 'label' => 'Bullet list'],
        ['action' => 'command', 'value' => 'insertOrderedList', 'state' => 'insertOrderedList', 'icon' => 'lucide:list-ordered', 'label' => 'Numbered list'],
        ['separator' => true],
        ['action' => 'link', 'value' => '', 'icon' => 'lucide:link', 'label' => 'Insert link'],
        ['action' => 'clear', 'value' => '', 'icon' => 'lucide:remove-formatting', 'label' => 'Clear formatting'],
    ];
    $wireAttrs = $attributes->whereStartsWith('wire:model');
    $hasWire = filled($wireAttrs->getAttributes());
    $attributes = $attributes->whereDoesntStartWith('wire:model');
@endphp

<div
    data-slot="rich-text-editor"
    data-variant="{{ $variant }}"
    {{ $attributes->class(['app-rich-text-editor', 'app-rich-text-editor-'.$variant, 'app-rich-text-editor-full' => $fullWidth]) }}
>
    <div role="toolbar" aria-label="Formatting" data-slot="rich-text-editor-toolbar" class="app-rich-text-editor-toolbar">
        @foreach($tools as $tool)
            @if(isset($tool['separator']))
                <span aria-hidden="true" class="app-rich-text-editor-separator"></span>
            @else
                <button
                    type="button"
                    data-slot="rich-text-editor-button"
                    data-rte-action="{{ $tool['action'] }}"
                    data-rte-value="{{ $tool['value'] }}"
                    @if(isset($tool['state'])) data-rte-state="{{ $tool['state'] }}" aria-pressed="false" @endif
                    class="app-rich-text-editor-button"
                    aria-label="{{ $tool['label'] }}"
                >
                    @isset($tool['icon'])
                        <x-icon :name="$tool['icon']" />
                    @else
                        <span class="app-rich-text-editor-text-icon">{{ $tool['text'] }}</span>
                    @endisset
                </button>
            @endif
        @endforeach
    </div>

    <div
        id="{{ $editorId }}"
        data-slot="rich-text-editor-content"
        data-rte-editor
        data-placeholder="{{ $placeholder }}"
        contenteditable="true"
        role="textbox"
        aria-multiline="true"
        aria-labelledby="{{ $labelId }}"
        dir="auto"
        class="app-rich-text-editor-content"
    >{!! $value !!}</div>

    <span id="{{ $labelId }}" class="app-sr-only">{{ $placeholder }}</span>

    @if($name || $hasWire)
        <textarea data-rte-input @if($name) name="{{ $name }}" @endif {{ $wireAttrs }} hidden aria-hidden="true" tabindex="-1">{!! $value !!}</textarea>
    @endif
</div>
