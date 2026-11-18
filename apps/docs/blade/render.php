<?php

use Illuminate\Config\Repository;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\View\ViewServiceProvider;
use Jetcar\Jds\JdsServiceProvider;

require __DIR__.'/vendor/autoload.php';

$app = new Application(__DIR__);
$app->instance('config', new Repository([
    'view' => [
        'paths' => [__DIR__.'/../src/demos'],
        'compiled' => __DIR__.'/vendor/jds-compiled-views',
    ],
]));

foreach ([EventServiceProvider::class, FilesystemServiceProvider::class, ViewServiceProvider::class, JdsServiceProvider::class] as $provider) {
    $app->register($provider);
}

$app->boot();

$example = $argv[1] ?? 'button.usage';
echo $app->make('view')->make($example)->render();
