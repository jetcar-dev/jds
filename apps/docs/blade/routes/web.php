<?php

use App\Support\MdxComponentDocument;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/_jds/{path}', function (string $path) {
    abort_unless(preg_match('/^[A-Za-z0-9._\/-]+$/', $path) === 1, 404);

    $distRoot  = realpath(base_path('../../../packages/jds/public/dist'));
    $assetPath = $distRoot ? realpath($distRoot . DIRECTORY_SEPARATOR . $path) : false;

    abort_unless(
        $distRoot !== false
        && $assetPath !== false
        && Str::startsWith($assetPath, $distRoot . DIRECTORY_SEPARATOR),
        404,
    );

    $contentType = match (strtolower(pathinfo($assetPath, PATHINFO_EXTENSION))) {
        'css' => 'text/css; charset=utf-8',
        'js' => 'text/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        default => 'application/octet-stream',
    };

    return response()->file($assetPath, ['Content-Type' => $contentType]);
})->where('path', '.*')->name('jds.asset');

Route::get('/_preview/{component}/{example}', function (string $component, string $example) {
    abort_unless(preg_match('/^[a-z0-9-]+$/', $component) === 1, 404);
    abort_unless(preg_match('/^[a-z0-9-]+$/', $example) === 1, 404);

    $preview = MdxComponentDocument::findExample(
        base_path("../content/components/{$component}.mdx"),
        $example,
    );

    abort_unless($preview !== null, 404);

    return view('preview', [
        'component' => $component,
        'example'   => $preview,
        'theme'     => request()->query('theme') === 'dark' ? 'dark' : 'light',
    ]);
})->name('preview.show');
