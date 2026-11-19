<?php

namespace Jetcar\Jds\Components\Card;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

final class Card extends Component
{
    private const VARIANTS = ['default', 'secondary', 'tertiary', 'transparent'];
    private const ELEMENT_TYPES = ['div', 'article', 'section', 'aside', 'a'];

    public function __construct(
        public string $variant = 'default',
        public string $elementType = 'div',
    ) {
        if (! in_array($this->variant, self::VARIANTS, true)) {
            throw new InvalidArgumentException("Invalid JDS card variant: {$this->variant}.");
        }

        if (! in_array($this->elementType, self::ELEMENT_TYPES, true)) {
            throw new InvalidArgumentException("Invalid JDS card element type: {$this->elementType}.");
        }
    }

    public function render(): View
    {
        return view('jds::components.card.card');
    }
}
