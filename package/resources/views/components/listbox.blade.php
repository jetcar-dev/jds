@props(['name'=>null,'value'=>null,'selectionMode'=>'none','color'=>'default','label'=>null,'emptyContent'=>'표시할 항목이 없습니다'])
@php $values=is_array($value)?$value:($value===null?[]:[$value]); @endphp
<div data-slot="listbox-base" data-name="{{ $name }}" class="app-color-{{ $color }}">
    <input data-listbox-input type="hidden" value="{{ $selectionMode==='multiple' ? json_encode($values) : ($values[0] ?? '') }}">
    <span data-listbox-form-values>@foreach($values as $selectedValue)<input type="hidden" name="{{ $name }}{{ $selectionMode==='multiple'?'[]':'' }}" value="{{ $selectedValue }}">@endforeach</span>
    <div data-slot="listbox" data-selection-mode="{{ $selectionMode }}" class="app-listbox" role="listbox" @if($selectionMode==='multiple') aria-multiselectable="true" @endif @if($label) aria-label="{{ $label }}" @endif {{ $attributes }}>@if(trim((string)$slot)==='')<div data-slot="empty-content">{{ $emptyContent }}</div>@else{{ $slot }}@endif</div>
</div>
