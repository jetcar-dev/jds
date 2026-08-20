@props([
    'method' => 'POST',
    'action' => null,
    'validationBehavior' => 'native',
    'validationErrors' => [],
])
@php
    $validationBehavior = in_array($validationBehavior, ['native', 'aria'], true) ? $validationBehavior : 'native';
    $validationErrors = is_array($validationErrors) ? $validationErrors : [];
@endphp
<form
    data-slot="form"
    data-ui-component="form"
    data-validation-behavior="{{ $validationBehavior }}"
    data-validation-errors="{{ json_encode($validationErrors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    data-invalid="{{ collect($validationErrors)->filter()->isNotEmpty() ? 'true' : 'false' }}"
    method="{{ strtolower($method) }}"
    @if($action) action="{{ $action }}" @endif
    @if($validationBehavior === 'aria') novalidate @endif
    {{ $attributes->class('app-form') }}
>{{ $slot }}</form>
