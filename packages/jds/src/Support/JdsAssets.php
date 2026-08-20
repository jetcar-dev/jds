<?php

namespace Jetcar\Jds\Support;

use Composer\InstalledVersions;

final class JdsAssets
{
    public static function styles(): string
    {
        return sprintf(
            '<link rel="stylesheet" href="%s">',
            htmlspecialchars(self::url('jds.css'), ENT_QUOTES, 'UTF-8'),
        );
    }

    public static function scripts(): string
    {
        return sprintf(
            '<script type="module" src="%s"></script>',
            htmlspecialchars(self::url('jds.js'), ENT_QUOTES, 'UTF-8'),
        );
    }

    public static function url(string $path): string
    {
        return url('/_jds-assets/'.ltrim($path, '/'))
            .'?v='.rawurlencode(self::version());
    }

    private static function version(): string
    {
        if (InstalledVersions::isInstalled('jetcar/jds')) {
            return InstalledVersions::getPrettyVersion('jetcar/jds') ?? 'dev';
        }

        $manifest = dirname(__DIR__, 2).'/public/dist/.vite/manifest.json';

        return is_file($manifest) ? (string) filemtime($manifest) : 'dev';
    }
}
