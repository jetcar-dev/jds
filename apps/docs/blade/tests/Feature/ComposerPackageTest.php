<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jetcar\Jds\JdsServiceProvider;
use Jetcar\Jds\Support\JdsAssets;
use Tests\TestCase;

require_once __DIR__.'/../../../../../packages/jds/src/Console/InstallCommand.php';
require_once __DIR__.'/../../../../../packages/jds/src/Http/Controllers/AssetController.php';
require_once __DIR__.'/../../../../../packages/jds/src/Support/JdsAssets.php';
require_once __DIR__.'/../../../../../packages/jds/src/JdsServiceProvider.php';

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

    public function test_it_renders_asset_directives_and_serves_the_bundled_files(): void
    {
        $this->app->register(JdsServiceProvider::class);

        $compiled = Blade::compileString('@jdsStyles @jdsScripts');
        $html = JdsAssets::styles().' '.JdsAssets::scripts();

        $this->assertStringContainsString('JdsAssets::styles()', $compiled);
        $this->assertStringContainsString('JdsAssets::scripts()', $compiled);

        $this->assertStringContainsString('<link rel="stylesheet" href="', $html);
        $this->assertStringContainsString('/_jds-assets/jds.css?v=', $html);
        $this->assertStringContainsString('<script type="module" src="', $html);
        $this->assertStringContainsString('/_jds-assets/jds.js?v=', $html);

        $this->get('/_jds-assets/jds.css')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=utf-8')
            ->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');

        $this->get('/_jds-assets/jds.js')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/javascript; charset=utf-8');

        $manifest = json_decode(
            file_get_contents(base_path('../../../packages/jds/public/dist/.vite/manifest.json')),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $iconBundle = $manifest['resources/js/icons/iconify-bundle.js']['file'];

        $this->get('/_jds-assets/jds.woff2')
            ->assertOk()
            ->assertHeader('Content-Type', 'font/woff2');

        $this->get('/_jds-assets/'.$iconBundle)
            ->assertOk()
            ->assertHeader('Content-Type', 'text/javascript; charset=utf-8');
    }
}
