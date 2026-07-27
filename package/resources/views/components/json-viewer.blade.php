@props(['data' => null, 'expanded' => true, 'rootLabel' => null])
@php
    $value = $data;
    if (is_string($data)) {
        $decoded = json_decode($data, true);
        $value = json_last_error() === JSON_ERROR_NONE ? $decoded : $data;
    } elseif (is_object($data)) {
        $value = json_decode(json_encode($data), true);
    }
    $pretty = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<div data-slot="json-viewer" {{ $attributes->class('app-json-viewer') }}>
    <div data-slot="json-viewer-header" class="app-json-viewer-header">
        <span data-slot="json-viewer-label"><x-icon name="code-linear" /><span>{{ $rootLabel ?? 'JSON' }}</span></span>
        <x-copy-button :value="$pretty" label="Copy JSON" class="app-json-viewer-copy" />
    </div>
    <div dir="ltr" data-slot="json-viewer-tree" class="app-json-viewer-tree">
        <x-json-viewer-node :value="$value" :depth="0" :expanded="$expanded" />
    </div>
</div>
