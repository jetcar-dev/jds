<?php

namespace Tests\Feature;

use Tests\TestCase;

final class IconBundleTest extends TestCase
{
    public function test_only_the_generated_iconify_bundle_is_kept(): void
    {
        $package = dirname(base_path()).DIRECTORY_SEPARATOR.'package';
        $bundle = file_get_contents($package.'/resources/js/icons/iconify-bundle.js');

        preg_match_all('/^    "[^"]+": \{"body":/m', $bundle, $matches);

        $this->assertCount(553, $matches[0]);
        $this->assertStringContainsString('"solar:map-arrow-up-linear"', $bundle);
        $this->assertStringContainsString('"solar:map-arrow-right-linear"', $bundle);
        $this->assertStringNotContainsString('"solar:map-arrow-up-linea"', $bundle);
        $this->assertStringNotContainsString('"solar:map-arrow-right-linearr"', $bundle);
        $this->assertFileDoesNotExist($package.'/resources/js/icons/solar-common.js');
        $this->assertFileDoesNotExist($package.'/resources/js/icons/iconify-extra.js');
    }

    public function test_icon_catalog_renders_every_locally_bundled_icon(): void
    {
        $response = $this->get('/icons')->assertOk();

        $response->assertSee('JDS에 저장되어');
        $response->assertSee('553개');
        $response->assertSee('solar:copy-linear');
        $response->assertSee('solar:map-point-linear');
        preg_match('/<script type="application\/json" data-icon-names>(.*?)<\/script>/s', $response->getContent(), $matches);
        $this->assertCount(553, json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR));
    }
}
