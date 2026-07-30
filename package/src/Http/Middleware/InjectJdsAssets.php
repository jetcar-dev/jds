<?php

namespace Jetcar\Jds\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class InjectJdsAssets
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! config('jds.auto_assets', true)) {
            return $response;
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type'));

        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return $response;
        }

        $cssUrl = htmlspecialchars(asset('vendor/jds/jds.css'), ENT_QUOTES, 'UTF-8');
        $jsUrl = htmlspecialchars(asset('vendor/jds/jds.js'), ENT_QUOTES, 'UTF-8');

        if (! str_contains($content, 'vendor/jds/jds.css') && stripos($content, '</head>') !== false) {
            $css = sprintf('<link rel="stylesheet" href="%s" data-jds-auto-assets>', $cssUrl);
            $content = preg_replace('/<\/head>/i', "    {$css}\n</head>", $content, 1) ?? $content;
        }

        if (! str_contains($content, 'vendor/jds/jds.js') && stripos($content, '</body>') !== false) {
            $script = sprintf('<script type="module" src="%s" data-jds-auto-assets></script>', $jsUrl);
            $content = preg_replace('/<\/body>/i', "    {$script}\n</body>", $content, 1) ?? $content;
        }

        $response->setContent($content);

        return $response;
    }
}
