<?php

namespace Jetcar\Jds\Support;

final class FieldAttributes
{
    private const ARIA_STATES = [
        'aria-busy',
        'aria-disabled',
        'aria-expanded',
        'aria-haspopup',
        'aria-hidden',
        'aria-invalid',
        'aria-readonly',
        'aria-required',
    ];

    public static function normalizeAriaStates(array $attributes): array
    {
        foreach (self::ARIA_STATES as $name) {
            $state = $attributes[$name] ?? null;

            if (is_bool($state)) {
                $attributes[$name] = $state ? 'true' : 'false';
            }
        }

        return $attributes;
    }
}
