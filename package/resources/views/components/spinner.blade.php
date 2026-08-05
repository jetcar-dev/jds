@props(['size'=>'md','color'=>'primary','label'=>'Loading'])
<span data-slot="spinner" data-size="{{ $size }}" role="status" aria-label="{{ $label }}" {{ $attributes->class("app-spinner app-color-$color") }}></span>
