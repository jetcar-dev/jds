<?php

namespace Tests\Feature;

use App\Support\MdxComponentDocument;
use Tests\TestCase;

final class HomePageTest extends TestCase
{
    private const COMPONENTS = [
        'accordion', 'alert', 'autocomplete', 'avatar', 'badge', 'breadcrumbs',
        'button', 'calendar', 'card', 'checkbox', 'checkbox-group', 'chip',
        'circular-progress', 'code', 'date-input', 'date-picker',
        'date-range-picker', 'divider', 'dropdown', 'drawer', 'form', 'image',
        'input', 'input-otp', 'kbd', 'link', 'listbox', 'modal', 'navbar',
        'number-input', 'pagination', 'popover', 'progress', 'radio-group',
        'range-calendar', 'scroll-shadow', 'select', 'skeleton', 'slider',
        'snippet', 'spacer', 'spinner', 'switch', 'table', 'tabs', 'toast',
        'textarea', 'time-input', 'tooltip', 'user',
    ];

    public function test_installation_and_component_catalog_render(): void
    {
        $this->get('/')->assertRedirect('/installation');
        $response = $this->get('/installation')->assertOk();

        foreach (self::COMPONENTS as $component) {
            $response->assertSee(route('components.show', $component));
        }
    }

    public function test_exactly_fifty_heroui_component_documents_exist_and_render(): void
    {
        $files = glob(base_path('content/components/*.mdx')) ?: [];
        $slugs = array_map(static fn (string $file): string => pathinfo($file, PATHINFO_FILENAME), $files);
        sort($slugs);
        $expected = self::COMPONENTS;
        sort($expected);

        $this->assertCount(50, $files);
        $this->assertSame($expected, $slugs);

        foreach ($files as $file) {
            $document = MdxComponentDocument::load($file);
            $this->assertCount(4, $document['examples'], basename($file));
            $this->assertNotEmpty($document['headings'], basename($file));
            $this->get('/components/'.pathinfo($file, PATHINFO_FILENAME))
                ->assertOk()
                ->assertSee('미리보기')
                ->assertSee('코드');
        }
    }

    public function test_documented_component_roots_and_parts_exist(): void
    {
        $root = base_path('../package/resources/views/components');

        foreach (self::COMPONENTS as $component) {
            $this->assertFileExists("{$root}/{$component}.blade.php", $component);
            $document = MdxComponentDocument::load(base_path("content/components/{$component}.mdx"));
            foreach ($document['parts'] as $part) {
                $this->assertFileExists("{$root}/{$part}.blade.php", $part);
            }
        }
    }

    public function test_legacy_public_components_are_removed(): void
    {
        $root = base_path('../package/resources/views/components');
        $removed = [
            'field', 'group', 'toggle', 'file-upload', 'json-viewer',
            'rich-text-editor', 'copy-button', 'datetime-picker', 'time-field',
            'combobox', 'label', 'dropdown-menu',
        ];

        foreach ($removed as $component) {
            $this->assertFileDoesNotExist("{$root}/{$component}.blade.php");
            $this->assertFileDoesNotExist(base_path("content/components/{$component}.mdx"));
        }
    }

    public function test_v2_theme_controller_and_stable_state_contract_exist(): void
    {
        $theme = file_get_contents(base_path('../package/resources/css/components/theme.css'));
        $styles = file_get_contents(base_path('../package/resources/css/components/heroui-v2.css'));
        $core = file_get_contents(base_path('../package/resources/js/components/core.js'));
        $components = file_get_contents(base_path('../package/resources/js/components/components-v2.js'));

        foreach (['.light', '.dark', '[data-theme="light"]', '[data-theme="dark"]', '--content1', '--primary-50', '--primary-900', '--disabled-opacity'] as $token) {
            $this->assertStringContainsString($token, $theme);
        }
        foreach (['data-hover', 'data-focus-visible', 'data-pressed', 'data-selected', 'data-invalid', 'data-disabled', 'data-open'] as $state) {
            $this->assertStringContainsString($state, $styles.$core.$components);
        }
        foreach (['getValue', 'setValue', 'open', 'close', 'focus', 'destroy', 'MutationObserver'] as $api) {
            $this->assertStringContainsString($api, $core.$components);
        }
    }

    public function test_bundles_stay_within_gzip_budget(): void
    {
        $dist = base_path('../package/public/dist');
        $this->assertLessThanOrEqual(40 * 1024, strlen(gzencode(file_get_contents("{$dist}/jds.css"), 9)));
        $this->assertLessThanOrEqual(100 * 1024, strlen(gzencode(file_get_contents("{$dist}/jds.js"), 9)));
    }

    public function test_unknown_component_returns_not_found(): void
    {
        $this->get('/components/not-a-component')->assertNotFound();
    }
}
