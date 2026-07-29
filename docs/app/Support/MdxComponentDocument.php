<?php

namespace App\Support;

use Illuminate\Support\Str;
use RuntimeException;

final class MdxComponentDocument
{
    public static function load(string $path): array
    {
        $source = file_get_contents($path);

        if ($source === false || ! preg_match('/\A---\R(.*?)\R---\R(.*)\z/su', $source, $matches)) {
            throw new RuntimeException("Invalid component MDX document: {$path}");
        }

        $meta = self::frontMatter($matches[1]);
        $body = trim($matches[2]);
        $headings = [];
        $headingIndex = 0;

        $body = preg_replace_callback('/^(##|###)\s+(.+)$/mu', static function (array $match) use (&$headings, &$headingIndex): string {
            $level = strlen($match[1]);
            $title = trim($match[2]);
            $id = Str::slug($title) ?: 'section-' . (++$headingIndex);
            $headings[] = ['id' => $id, 'title' => $title, 'level' => $level];

            return sprintf('<h%d id="%s">%s</h%d>', $level, e($id), e($title), $level);
        }, $body) ?? $body;

        $segments = [];
        $examples = [];
        $pattern = '~```blade[ \t]+preview(?:[ \t]+name="(?P<name>[^"]+)")?[ \t]*\R(?P<code>.*?)\R```~su';
        $offset = 0;

        preg_match_all($pattern, $body, $previews, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($previews as $index => $preview) {
            $match = $preview[0];
            $markdown = trim(substr($body, $offset, $match[1] - $offset));
            if ($markdown !== '') {
                $segments[] = ['type' => 'markdown', 'html' => self::markdown($markdown)];
            }

            $name = ($preview['name'][1] ?? -1) >= 0
                ? $preview['name'][0]
                : 'preview-' . ($index + 1);
            $code = trim($preview['code'][0]);

            $example = ['key' => $name, 'title' => Str::headline($name), 'code' => $code];
            $examples[] = $example;
            $segments[] = ['type' => 'preview', 'example' => $example];
            $offset = $match[1] + strlen($match[0]);
        }

        $markdown = trim(substr($body, $offset));
        if ($markdown !== '') {
            $segments[] = ['type' => 'markdown', 'html' => self::markdown($markdown)];
        }

        return [
            'title' => $meta['title'] ?? Str::headline(pathinfo($path, PATHINFO_FILENAME)),
            'description' => $meta['description'] ?? '',
            'parts' => $meta['parts'] ?? [pathinfo($path, PATHINFO_FILENAME)],
            'examples' => $examples,
            'segments' => $segments,
            'headings' => $headings,
            'mdx' => true,
        ];
    }

    private static function frontMatter(string $source): array
    {
        $meta = [];

        foreach (preg_split('/\R/u', trim($source)) ?: [] as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode(':', $line, 2));
            $value = trim($value, " \t\n\r\0\x0B\"'");

            if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
                $value = array_values(array_filter(array_map(
                    static fn (string $item): string => trim($item, " \t\n\r\0\x0B\"'"),
                    explode(',', substr($value, 1, -1)),
                )));
            }

            $meta[$key] = $value;
        }

        return $meta;
    }

    private static function markdown(string $source): string
    {
        return Str::markdown($source, [
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
    }
}
