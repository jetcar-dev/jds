<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jetcar\Jds\JdsServiceProvider;
use Tests\TestCase;

require_once __DIR__ . '/../../../../../packages/jds/src/Console/InstallCommand.php';
require_once __DIR__ . '/../../../../../packages/jds/src/JdsServiceProvider.php';

final class ComposerPackageTest extends TestCase
{
    public function test_it_registers_components_command_and_publishable_assets(): void
    {
        $this->app->register(JdsServiceProvider::class);

        $html = Blade::render('<x-button color="primary">저장</x-button>');

        $this->assertStringContainsString('data-ui-component="button"', $html);
        $this->assertArrayHasKey('jds:install', Artisan::all());
        $this->assertSame([
            realpath(base_path('../../../packages/jds/public/dist')) => public_path('vendor/jds'),
        ], ServiceProvider::pathsToPublish(JdsServiceProvider::class, 'jds-assets'));
    }
}
