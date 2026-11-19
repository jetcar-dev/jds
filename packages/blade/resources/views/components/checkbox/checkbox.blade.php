@php
    $messageBaseId = isset($description) || isset($error)
        ? ($attributes->get('id') ?: 'jds-checkbox-'.\Illuminate\Support\Str::uuid())
        : null;
    $descriptionId = isset($description) ? $description->attributes->get('id', $messageBaseId.'-description') : null;
    $errorId = isset($error) ? $error->attributes->get('id', $messageBaseId.'-error') : null;
    $input = $inputAttributes($attributes, $descriptionId, $errorId);
    $invalid = ! in_array($input->get('aria-invalid'), [null, '', 'false'], true);
    $state = $stateAttributes($invalid);
    $contentAttributes = $content->attributes ?? new \Illuminate\View\ComponentAttributeBag;
@endphp

<div {{ $fieldAttributes($attributes)->merge($state) }}>
    <label {{ $contentAttributes->class('jds-checkbox__content')->merge(['data-slot' => 'checkbox-content', ...$state]) }}>
        <input {{ $input }}>
        @isset($content)
            {{ $content }}
        @else
            <x-jds::checkbox.control>
                <x-jds::checkbox.indicator />
            </x-jds::checkbox.control>
            {{ $slot }}
        @endisset
    </label>
    @isset($description)
        <div {{ $description->attributes->merge(['id' => $descriptionId, 'data-slot' => 'description']) }}>{{ $description }}</div>
    @endisset
    @isset($error)
        <div {{ $error->attributes->merge(['id' => $errorId, 'data-slot' => 'field-error']) }}>{{ $error }}</div>
    @endisset
</div>
