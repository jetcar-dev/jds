<?php

namespace Jetcar\Jds\Components\TextArea;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;
use InvalidArgumentException;
use Jetcar\Jds\Support\FieldAttributes;

final class TextArea extends Component
{
    public function __construct(
        public string $variant = 'primary',
        public bool $fullWidth = false,
    ) {
        if (! in_array($this->variant, ['primary', 'secondary'], true)) {
            throw new InvalidArgumentException("Invalid JDS textarea variant: {$this->variant}.");
        }
    }

    public function textareaAttributes(ComponentAttributeBag $attributes): ComponentAttributeBag
    {
        $normalized = FieldAttributes::normalizeAriaStates($attributes->except('value')->getAttributes());

        return (new ComponentAttributeBag($normalized))->class([
            'jds-textarea',
            "jds-textarea--{$this->variant}",
            'jds-textarea--full-width' => $this->fullWidth,
        ])->merge(['data-slot' => 'textarea']);
    }

    public function initialValue(ComponentAttributeBag $attributes, ComponentSlot $slot): string
    {
        // Decode Blade's HTML before the view escapes it as textarea text.
        $value = $attributes->get('value') ?? $slot;

        return html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function render(): View
    {
        return view('jds::components.textarea.textarea');
    }
}
