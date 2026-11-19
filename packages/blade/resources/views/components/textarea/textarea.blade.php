{{-- The HTML parser skips the first line break inside a textarea. --}}
<textarea {{ $textareaAttributes($attributes) }}>
{{ $initialValue($attributes, $slot) }}</textarea>
