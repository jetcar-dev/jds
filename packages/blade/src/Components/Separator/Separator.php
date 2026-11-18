<?php

namespace Jetcar\Jds\Components\Separator;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

final class Separator extends Component
{
    private const ORIENTATIONS = ['horizontal', 'vertical'];
    private const VARIANTS = ['default', 'secondary', 'tertiary'];
    private const ELEMENT_TYPES = ['hr', 'div', 'span'];

    public function __construct(
        public string $orientation = 'horizontal',
        public string $variant = 'default',
        public bool $isDecorative = false,
        public ?string $elementType = null,
    ) {
        foreach (['orientation' => self::ORIENTATIONS, 'variant' => self::VARIANTS, 'elementType' => self::ELEMENT_TYPES] as $prop => $choices) {
            if ($this->{$prop} !== null && ! in_array($this->{$prop}, $choices, true)) {
                throw new InvalidArgumentException("Invalid JDS separator {$prop}: {$this->{$prop}}.");
            }
        }
    }

    public function tag(): string
    {
        $tag = $this->elementType ?? ($this->isDecorative ? 'div' : 'hr');

        return $tag === 'hr' && $this->orientation === 'vertical' ? 'div' : $tag;
    }

    public function classes(): array
    {
        return [
            'jds-separator',
            "jds-separator--{$this->orientation}",
            "jds-separator--{$this->variant}",
        ];
    }

    public function render(): View
    {
        return view('jds::components.separator.separator');
    }
}
