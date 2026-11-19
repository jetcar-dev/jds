<?php

namespace Jetcar\Jds\Components\Input;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use InvalidArgumentException;
use Jetcar\Jds\Support\FieldAttributes;

final class Input extends Component
{
    private const VARIANTS = ['primary', 'secondary'];

    private const MASKABLE_TYPES = ['text', 'tel', 'search', 'url', 'password'];

    public function __construct(
        public string $variant = 'primary',
        public bool $fullWidth = false,
        public ?string $mask = null,
    ) {
        if (! in_array($this->variant, self::VARIANTS, true)) {
            throw new InvalidArgumentException("Invalid JDS input variant: {$this->variant}.");
        }

        if ($this->mask !== null && $this->mask !== 'comma') {
            $tokens = $this->unescapedMask();

            if (! preg_match('/[9ak]/', $tokens) || str_contains($tokens, '\\') || preg_match('/[\x00-\x1F]/', $this->mask)) {
                throw new InvalidArgumentException('A JDS input mask must be comma or a pattern using 9, a or k.');
            }
        }
    }

    public function inputAttributes(ComponentAttributeBag $attributes): ComponentAttributeBag
    {
        $normalized = FieldAttributes::normalizeAriaStates($attributes->getAttributes());

        if ($this->mask !== null) {
            $type = strtolower(trim($normalized['type'] ?? 'text'));

            if (! in_array($type, self::MASKABLE_TYPES, true)) {
                throw new InvalidArgumentException('A JDS input mask requires type text, tel, search, url or password.');
            }

            if ($this->mask === 'comma' || ! preg_match('/[ak]/', $this->unescapedMask())) {
                $normalized['inputmode'] ??= 'numeric';
            }
        }

        $value = $normalized['value'] ?? null;

        if (is_string($value)) {
            // The attribute bag trims serialized strings. Keep whitespace in the input value.
            $normalized['value'] = preg_replace_callback(
                '/\A\s+|\s+\z/',
                static fn (array $match): string => strtr($match[0], [
                    ' ' => '&#32;', "\t" => '&#9;', "\r" => '&#13;', "\n" => '&#10;', "\v" => '&#11;', "\f" => '&#12;',
                ]),
                $value,
            );
        }

        return (new ComponentAttributeBag($normalized))->class([
            'jds-input',
            "jds-input--{$this->variant}",
            'jds-input--full-width' => $this->fullWidth,
        ])->merge([
            'type' => 'text',
            'data-slot' => 'input',
            'data-jds-mask' => $this->mask,
        ]);
    }

    public function render(): View
    {
        return view('jds::components.input.input');
    }

    private function unescapedMask(): string
    {
        return preg_replace('/\x5c./u', '', $this->mask ?? '') ?? '';
    }
}
