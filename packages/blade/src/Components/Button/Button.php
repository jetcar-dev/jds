<?php

namespace Jetcar\Jds\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

final class Button extends Component
{
    private const VARIANTS = ['primary', 'secondary', 'tertiary', 'outline', 'ghost', 'danger', 'danger-soft'];

    private const SIZES = ['sm', 'md', 'lg'];

    private const TYPES = ['button', 'submit', 'reset'];

    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public string $type = 'button',
        public bool $fullWidth = false,
        public bool $isIconOnly = false,
        public bool $isDisabled = false,
        public bool $isPending = false,
        public ?string $loadingText = null,
    ) {
        foreach (['variant' => self::VARIANTS, 'size' => self::SIZES, 'type' => self::TYPES] as $prop => $choices) {
            if (! in_array($this->{$prop}, $choices, true)) {
                throw new InvalidArgumentException("Invalid JDS button {$prop}: {$this->{$prop}}.");
            }
        }
    }

    public function classes(): array
    {
        return [
            'jds-button',
            "jds-button--{$this->size}",
            "jds-button--{$this->variant}",
            'jds-button--full-width' => $this->fullWidth,
            'jds-button--icon-only' => $this->isIconOnly,
        ];
    }

    public function render(): View
    {
        return view('jds::components.button.button');
    }
}
