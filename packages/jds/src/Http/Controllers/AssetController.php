<?php

namespace Jetcar\Jds\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class AssetController
{
    public function __invoke(string $path): BinaryFileResponse
    {
        abort_unless(preg_match('/^[A-Za-z0-9._\/-]+$/', $path) === 1, 404);

        $root = realpath(dirname(__DIR__, 3).'/public/dist');
        $file = $root === false ? false : realpath($root.DIRECTORY_SEPARATOR.$path);

        abort_unless(
            $root !== false
            && $file !== false
            && str_starts_with($file, $root.DIRECTORY_SEPARATOR),
            404,
        );

        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Type', $this->contentType($file));
        $response->setPublic();
        $response->setMaxAge(31536000);
        $response->setImmutable();

        return $response;
    }

    private function contentType(string $file): string
    {
        return match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            'css' => 'text/css; charset=utf-8',
            'js' => 'text/javascript; charset=utf-8',
            'json' => 'application/json; charset=utf-8',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            default => 'application/octet-stream',
        };
    }
}
