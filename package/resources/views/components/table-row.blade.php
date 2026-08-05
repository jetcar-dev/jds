@props(['key' => null, 'selected' => false, 'disabled' => false, 'selectable' => false])
<tr data-slot="tr" data-key="{{ $key }}" data-selected="{{ $selected?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" data-selectable="{{ $selectable?'true':'false' }}" tabindex="{{ $selectable?0:-1 }}" {{ $attributes }}>{{ $slot }}</tr>
