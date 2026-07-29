<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_root_redirects_to_installation_documentation(): void
    {
        $this->get('/')->assertRedirect('/installation');

        $this->get('/installation')
            ->assertOk()
            ->assertSee('설치하기')
            ->assertSee('composer require jetcar/jds');
    }

    public function test_component_test_page_renders_control_size_comparison(): void
    {
        $this->get('/component-test')
            ->assertOk()
            ->assertSee('Control size comparison')
            ->assertSee('data-control-measure="Input"', false)
            ->assertSee('data-control-measure="Group"', false)
            ->assertSee('app-group-xl', false);
    }

    public function test_each_component_has_ui_code_and_api_documentation(): void
    {
        $this->get('/components/button')
            ->assertOk()
            ->assertSee('Button')
            ->assertSee('미리보기')
            ->assertSee('코드')
            ->assertSee('API 안내')
            ->assertSee('variant');
    }

    public function test_all_component_pages_render_multiple_usage_examples(): void
    {
        $files = glob(base_path('components/*.php')) ?: [];
        $components = [];

        foreach ($files as $file) {
            $components[pathinfo($file, PATHINFO_FILENAME)] = require $file;
        }

        foreach ($components as $slug => $component) {
            $this->assertGreaterThanOrEqual(4, count($component['examples']), $slug . ' 문서에는 최소 4개의 예제가 필요합니다.');

            $response = $this->get('/components/' . $slug)
                ->assertOk()
                ->assertSee('미리보기')
                ->assertSee('코드')
                ->assertSee($component['examples'][0]['title'])
                ->assertSee($component['examples'][1]['title']);
        }
    }

    public function test_component_document_directory_is_the_only_catalog(): void
    {
        $this->assertCount(28, glob(base_path('components/*.php')) ?: []);
        $this->assertFileDoesNotExist(config_path('jds-docs.php'));
        $this->assertFileDoesNotExist(config_path('jds-doc-examples.php'));
    }

    public function test_select_documentation_only_exposes_current_properties(): void
    {
        $response = $this->get('/components/select')
            ->assertOk()
            ->assertSee('full-width');

        foreach ([
            'ajax-url',
            'is-searchable',
            'ajax-delay',
            'minimum-input-length',
            'is-disabled',
            'is-required',
            'is-invalid',
            'is-multiple',
        ] as $removedProperty) {
            $response->assertDontSee($removedProperty);
        }
    }

    public function test_input_documentation_renders_variants_width_label_and_password_states(): void
    {
        $this->get('/components/input')
            ->assertOk()
            ->assertSee('data-required="true"', false)
            ->assertSee('app-input-full', false)
            ->assertSee('app-input-bordered', false)
            ->assertSee('app-input-flat', false)
            ->assertSee('app-input-faded', false)
            ->assertSee('app-input-ghost', false)
            ->assertDontSee('app-input-underlined', false)
            ->assertSee('data-password-visible', false)
            ->assertSee('eye-closed-linear', false);
    }

    public function test_width_capable_controls_render_full_width_classes(): void
    {
        $this->get('/components/button')
            ->assertOk()
            ->assertSee('app-button-full', false)
            ->assertSee('full-width');

        $this->get('/components/textarea')
            ->assertOk()
            ->assertSee('app-textarea-full', false)
            ->assertSee('full-width');

        $this->get('/components/rich-text-editor')
            ->assertOk()
            ->assertSee('app-rich-text-editor-full', false)
            ->assertSee('full-width');

        $this->get('/components/date-picker')
            ->assertOk()
            ->assertSee('app-date-picker-full', false)
            ->assertSee('app-datetime-picker-full', false)
            ->assertSee('app-time-field-full', false)
            ->assertSee('full-width');
    }

    public function test_public_api_uses_group_and_input_mask_attributes_without_legacy_components(): void
    {
        $this->get('/components/group')
            ->assertOk()
            ->assertSee('selection')
            ->assertSee('&lt;x-toggle value=&quot;left&quot;&gt;', false);

        $this->get('/components/input')
            ->assertOk()
            ->assertSee('mask=&quot;999-9999-9999&quot;', false)
            ->assertSee('AAA-9999');

        foreach (['toggle-group', 'control-group', 'input-group', 'button-group', 'input-mask'] as $removedComponent) {
            $this->assertFileDoesNotExist(base_path('../package/resources/views/components/' . $removedComponent . '.blade.php'));
        }
    }

    public function test_card_documentation_renders_shadowless_outline_variant(): void
    {
        $this->get('/components/card')
            ->assertOk()
            ->assertSee('app-card-outline', false)
            ->assertSee('variant=&quot;outline&quot;', false);
    }

    public function test_single_line_controls_share_size_tokens(): void
    {
        $root = base_path('../package/resources/css/components');
        $base = file_get_contents($root . '/base.css');
        $group = file_get_contents($root . '/group.css');

        $this->assertStringContainsString('--app-ui-control-xs: 1.75rem', $base);
        $this->assertStringContainsString('--app-ui-control-sm: 2rem', $base);
        $this->assertStringContainsString('--app-ui-control-md: 2.5rem', $base);
        $this->assertStringContainsString('--app-ui-control-lg: 3rem', $base);
        $this->assertStringContainsString('--app-ui-control-xl: 3.5rem', $base);
        $this->assertStringContainsString('--app-group-height: var(--app-ui-control-md)', $group);

        foreach (['button/button.css', 'input/input.css', 'select.css', 'combobox.css', 'group.css'] as $file) {
            $css = file_get_contents($root . '/' . $file);
            $this->assertStringContainsString('var(--app-ui-control-xs)', $css, $file);
            $this->assertStringContainsString('var(--app-ui-control-sm)', $css, $file);
            $this->assertStringContainsString('var(--app-ui-control-md)', $css, $file);
            $this->assertStringContainsString('var(--app-ui-control-lg)', $css, $file);
            $this->assertStringContainsString('var(--app-ui-control-xl)', $css, $file);
        }
    }

    public function test_heroui_interaction_states_use_reference_tokens(): void
    {
        $root = base_path('../package/resources/css/components');
        $theme = file_get_contents($root . '/theme.css');
        $states = file_get_contents($root . '/heroui.css');
        $group = file_get_contents($root . '/group.css');
        $otp = file_get_contents($root . '/input/input-otp.css');

        $this->assertStringContainsString('--heroui-hover-opacity: 0.8', $theme);
        $this->assertStringContainsString('--primary-50: hsl(212.5 92.31% 94.9%)', $theme);
        $this->assertStringContainsString('--danger-50: hsl(339.13 92% 95.1%)', $theme);
        $this->assertStringContainsString('opacity: var(--heroui-hover-opacity)', $states);
        $this->assertStringContainsString('transform: scale(0.97)', $states);
        $this->assertStringContainsString('0 0 0 2px var(--background), 0 0 0 4px var(--focus)', $states);
        $this->assertStringContainsString('background: var(--danger-50)', $states);
        $this->assertStringContainsString('background: var(--danger-100)', $states);
        $this->assertStringContainsString('opacity: var(--disabled-opacity)', $states);
        $this->assertStringContainsString('--app-field-faded-border: var(--default-400)', $states);
        $this->assertStringContainsString('.app-input-otp-flat', $otp);
        $this->assertStringContainsString('--app-otp-active-shadow:', $otp);
        $this->assertStringContainsString('margin-inline-start: -2px', $group);
    }

    public function test_unknown_component_returns_not_found(): void
    {
        $this->get('/components/not-a-component')->assertNotFound();
    }
}
