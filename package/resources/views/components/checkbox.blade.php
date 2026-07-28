@props([
    'id' => null,
    'name' => null,
    'value' => 'on',
    'checked' => false,
    'disabled' => false,
    'indeterminate' => false,
    'native' => false,
    'label' => null,
])

@php
    $controlAttributes = $label !== null && !$attributes->has('aria-label')
        ? $attributes->merge(['aria-label' => $label])
        : $attributes;
@endphp

@if($label !== null)<label class="app-checkbox-label">@endif

@if($native)
    <input
        type="checkbox"
        @if($id) id="{{ $id }}" @endif
        @if($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        @checked($checked)
        @disabled($disabled)
        data-slot="checkbox"
        {{ $controlAttributes->class('app-checkbox-native') }}
    />
@else
    <button
        type="button"
        role="checkbox"
        @if($id) id="{{ $id }}" @endif
        data-slot="checkbox"
        data-state="{{ $indeterminate ? 'indeterminate' : ($checked ? 'checked' : 'unchecked') }}"
        data-checked="{{ $checked ? 'true' : 'false' }}"
        data-indeterminate="{{ $indeterminate ? 'true' : 'false' }}"
        aria-checked="{{ $indeterminate ? 'mixed' : ($checked ? 'true' : 'false') }}"
        @disabled($disabled)
        {{ $controlAttributes->class('app-checkbox') }}
    >
        <span data-slot="checkbox-indicator" class="app-checkbox-indicator"
              @if(!$checked && !$indeterminate) hidden @endif>
            <svg data-checkbox-minus @if(!$indeterminate) hidden @endif
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                <path d="M5 12h14"/>
            </svg>
            <svg data-checkbox-check @if($indeterminate) hidden @endif
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <path d="m5 12 4 4L19 6"/>
            </svg>
        </span>

        @if($name)
            <input
                type="hidden"
                data-checkbox-input
                data-name="{{ $name }}"
                @if($checked && !$indeterminate) name="{{ $name }}" @endif
                value="{{ $value }}"
            >
        @endif
    </button>
@endif

@if($label !== null)<span>{{ $label }}</span></label>@endif
