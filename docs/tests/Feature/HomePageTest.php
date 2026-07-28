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
            ->assertSee('app-input-outline', false)
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

    public function test_unknown_component_returns_not_found(): void
    {
        $this->get('/components/not-a-component')->assertNotFound();
    }
}
