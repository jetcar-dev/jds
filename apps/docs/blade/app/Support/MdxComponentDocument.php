<?php

namespace App\Support;

use Illuminate\Support\Str;

final class MdxComponentDocument
{
    public static function findExample(string $documentPath, string $key): ?array
    {
        if (! is_file($documentPath)) {
            return null;
        }

        $source = file_get_contents($documentPath);

        if ($source === false) {
            return null;
        }

        preg_match_all(
            '~```blade[ \t]+preview(?:[ \t]+name="(?P<name>[^"]+)")?[ \t]*\\R(?P<code>.*?)\\R```~su',
            $source,
            $matches,
            PREG_SET_ORDER,
        );

        foreach ($matches as $index => $match) {
            $name = $match['name'] !== '' ? $match['name'] : 'preview-'.($index + 1);

            if ($name === $key) {
                return [
                    'key' => $name,
                    'title' => Str::headline($name),
                    'code' => trim($match['code']),
                ];
            }
        }

        return null;
    }
}
