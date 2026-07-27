@props(['value' => null, 'depth' => 0, 'expanded' => true, 'keyName' => null, 'isLast' => true])
@php
    $container = is_array($value);
    $assoc = $container && (array_keys($value) !== range(0, count($value) - 1) || $value === []);
    $open = $assoc ? '{' : '[';
    $close = $assoc ? '}' : ']';
    $comma = $isLast ? '' : ',';
    $hasKey = $keyName !== null;
    $padding = 'padding-inline-start:' . ((int) $depth) . 'rem';
@endphp
@if($container)
    <div data-slot="json-viewer-node" data-state="{{ $expanded ? 'open' : 'closed' }}" class="app-json-node" style="{{ $padding }}">
        <button type="button" data-json-toggle aria-expanded="{{ $expanded ? 'true' : 'false' }}">
            <x-icon name="alt-arrow-right-linear" class="app-json-chevron" />
            @if($hasKey)
                @if(is_int($keyName))<span class="app-json-index">{{ $keyName }}</span><span>:</span>
                @else<span class="app-json-key">"{{ $keyName }}"</span><span>:</span>@endif
            @endif
            <span>{{ $open }}</span>
            <span data-json-summary @if($expanded) hidden @endif>…{{ $close }}{{ $comma }} <i>{{ count($value) }} {{ $assoc ? (count($value) === 1 ? 'key' : 'keys') : (count($value) === 1 ? 'item' : 'items') }}</i></span>
        </button>
        <div data-json-children @if(!$expanded) hidden @endif>
            @foreach($value as $key => $item)
                <x-json-viewer-node :value="$item" :depth="$depth + 1" :expanded="$expanded" :key-name="$key" :is-last="$loop->last" />
            @endforeach
            <div class="app-json-close" style="{{ $padding }}">{{ $close }}{{ $comma }}</div>
        </div>
    </div>
@else
    @php
        if (is_string($value)) { $token = '"'.$value.'"'; $type = 'string'; }
        elseif (is_bool($value)) { $token = $value ? 'true' : 'false'; $type = 'literal'; }
        elseif (is_null($value)) { $token = 'null'; $type = 'literal'; }
        else { $token = (string) $value; $type = 'number'; }
    @endphp
    <div data-slot="json-viewer-leaf" class="app-json-leaf" style="{{ $padding }}">
        @if($hasKey)
            @if(is_int($keyName))<span class="app-json-index">{{ $keyName }}</span><span>:</span>
            @else<span class="app-json-key">"{{ $keyName }}"</span><span>:</span>@endif
        @endif
        <span data-json-token="{{ $type }}">{{ $token }}</span><span>{{ $comma }}</span>
    </div>
@endif
