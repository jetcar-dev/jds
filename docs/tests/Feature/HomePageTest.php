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
            ->assertSee('프로젝트에 추가하기')
            ->assertSee('package/resources/views/components')
            ->assertSee('package/public/dist/jds.css');
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
        $components = [];

        foreach (glob(base_path('content/components/*.mdx')) ?: [] as $file) {
            $components[pathinfo($file, PATHINFO_FILENAME)] = \App\Support\MdxComponentDocument::load($file);
        }

        foreach ($components as $slug => $component) {
            $this->assertGreaterThanOrEqual(4, count($component['examples']), $slug . ' 문서에는 최소 4개의 예제가 필요합니다.');

            $response = $this->get('/components/' . $slug)
                ->assertOk()
                ->assertSee('미리보기')
                ->assertSee('코드');

            $response
                ->assertSee('data-preview-name="' . $component['examples'][0]['key'] . '"', false)
                ->assertSee('data-preview-name="' . $component['examples'][1]['key'] . '"', false);
        }
    }

    public function test_component_document_directory_is_the_only_catalog(): void
    {
        $this->assertCount(0, glob(base_path('components/*.php')) ?: []);
        $this->assertCount(28, glob(base_path('content/components/*.mdx')) ?: []);
        $this->assertCount(0, glob(base_path('content/examples/*.blade.php')) ?: []);
        $this->assertFileExists(base_path('content/components/button.mdx'));
        $this->assertFileDoesNotExist(base_path('content/examples/button-basic.blade.php'));
        $this->assertFileDoesNotExist(config_path('jds-docs.php'));
        $this->assertFileDoesNotExist(config_path('jds-doc-examples.php'));
    }

    public function test_button_document_uses_inline_mdx_blade_previews(): void
    {
        $response = $this->get('/components/button')
            ->assertOk()
            ->assertSee('data-preview-name="button-basic"', false)
            ->assertSee('data-preview-name="button-variants"', false)
            ->assertSee('On this page')
            ->assertSee('API 안내');

        $this->assertStringContainsString(
            '```blade preview name="button-basic"',
            file_get_contents(base_path('content/components/button.mdx')),
        );
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

    public function test_boolean_properties_use_presence_syntax(): void
    {
        $this->get('/installation')
            ->assertOk()
            ->assertSee('속성만 쓰면 true')
            ->assertSee('&lt;x-button variant=&quot;bordered&quot; size=&quot;sm&quot; full-width&gt;', false);

        $sources = array_merge(
            glob(base_path('content/components/*.mdx')) ?: [],
            [resource_path('views/component-test.blade.php')],
        );

        foreach ($sources as $source) {
            $this->assertDoesNotMatchRegularExpression(
                '/:[a-zA-Z0-9-]+="true"/',
                file_get_contents($source),
                $source,
            );
        }

        $this->get('/components/button')
            ->assertOk()
            ->assertSee('app-button-full', false)
            ->assertSee('app-button-icon-only', false);
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

    public function test_explicit_child_variant_overrides_group_variant(): void
    {
        $group = file_get_contents(base_path('../package/resources/css/components/group.css'));
        $button = file_get_contents(base_path('../package/resources/views/components/button.blade.php'));

        $this->get('/components/group')
            ->assertOk()
            ->assertSee('&lt;x-group variant=&quot;outline&quot;&gt;', false)
            ->assertSee('&lt;x-button variant=&quot;solid&quot; color=&quot;primary&quot;&gt;다음&lt;/x-button&gt;', false)
            ->assertSee('data-group-variant="explicit"', false);

        $this->assertStringContainsString("'variant' => null", $button);
        $this->assertStringContainsString(':not([data-group-variant="explicit"])', $group);
    }

    public function test_tabs_render_semantic_colors_and_all_variants(): void
    {
        $this->get('/components/tabs')
            ->assertOk()
            ->assertSee('data-color="primary"', false)
            ->assertSee('data-color="secondary"', false)
            ->assertSee('data-color="success"', false)
            ->assertSee('data-color="warning"', false)
            ->assertSee('data-color="danger"', false)
            ->assertSee('data-variant="solid"', false)
            ->assertSee('data-variant="underlined"', false)
            ->assertSee('data-variant="bordered"', false)
            ->assertSee('data-variant="light"', false);
    }

    public function test_box_choice_controls_render_groups_and_optional_indicators(): void
    {
        $this->get('/components/checkbox')
            ->assertOk()
            ->assertSee('app-checkbox-group-box', false)
            ->assertSee('app-checkbox-group-full', false)
            ->assertSee('data-show-indicator="false"', false)
            ->assertSee('&lt;x-checkbox-group', false)
            ->assertSee('&lt;x-checkbox name=&quot;channels[]&quot;', false)
            ->assertDontSee(':options=', false);

        $this->get('/components/radio-group')
            ->assertOk()
            ->assertSee('app-radio-group-box', false)
            ->assertSee('app-radio-group-full', false)
            ->assertSee('aria-label="세금계산서"', false)
            ->assertSee('data-show-indicator="false"', false)
            ->assertSee('&lt;x-radio-group-item value=&quot;card&quot;', false)
            ->assertDontSee(':options=', false);

        $this->assertStringNotContainsString(
            "'options' =>",
            file_get_contents(base_path('../package/resources/views/components/radio-group.blade.php'))
        );
        $this->assertStringNotContainsString(
            "'options' =>",
            file_get_contents(base_path('../package/resources/views/components/checkbox-group.blade.php'))
        );
        $this->assertStringContainsString(
            '.app-checkbox-group-box .app-checkbox-label',
            file_get_contents(base_path('../package/resources/css/components/checkbox.css'))
        );
        $this->assertStringContainsString(
            'width: fit-content',
            file_get_contents(base_path('../package/resources/css/components/checkbox-group.css'))
        );
        $this->assertStringContainsString(
            'width: fit-content',
            file_get_contents(base_path('../package/resources/css/components/radio-group.css'))
        );
        $this->assertStringContainsString(
            '.app-checkbox-group-box.app-checkbox-group-horizontal .app-checkbox-group-items',
            file_get_contents(base_path('../package/resources/css/components/checkbox-group.css'))
        );
        $this->assertStringContainsString(
            '.app-radio-group-box.app-radio-group-horizontal .app-radio-option',
            file_get_contents(base_path('../package/resources/css/components/radio-group.css'))
        );
    }

    public function test_field_columns_and_toggle_groups_keep_their_layout(): void
    {
        $field = file_get_contents(base_path('../package/resources/css/components/field.css'));
        $group = file_get_contents(base_path('../package/resources/css/components/group.css'));
        $toggle = file_get_contents(base_path('../package/resources/js/components/toggle.js'));

        $this->get('/components/field')
            ->assertOk()
            ->assertSee('name="recipient"', false)
            ->assertSee('name="recipient_phone"', false);

        $this->assertStringContainsString(
            'repeat(auto-fit, minmax(min(100%, 14rem), 1fr))',
            $field
        );
        $this->assertStringContainsString(':has(+ input[type="hidden"]:last-child)', $group);
        $this->assertStringNotContainsString(':has(~ input[type="hidden"]:last-child)', $group);
        $this->assertStringContainsString('.app-group:not(.app-group-selection)', $group);
        $this->assertStringContainsString('transform: none', $group);
        $this->assertStringContainsString('const activeItem = currentItems.includes(document.activeElement)', $toggle);
    }

    public function test_modal_uses_a_body_component_instead_of_a_manual_class(): void
    {
        $this->get('/components/modal')
            ->assertOk()
            ->assertSee('data-slot="modal-body"', false)
            ->assertSee('&lt;x-modal-body&gt;', false)
            ->assertDontSee('&lt;div class=&quot;app-modal-body&quot;&gt;', false);

        $this->assertFileExists(
            base_path('../package/resources/views/components/modal-body.blade.php')
        );
    }

    public function test_modal_supports_inside_and_outside_scrolling(): void
    {
        $modal = file_get_contents(base_path('../package/resources/views/components/modal.blade.php'));
        $styles = file_get_contents(base_path('../package/resources/css/components/modal.css'));
        $script = file_get_contents(base_path('../package/resources/js/components/modal.js'));

        $this->get('/components/modal')
            ->assertOk()
            ->assertSee('data-scroll="inside"', false)
            ->assertSee('data-scroll="outside"', false)
            ->assertSee('&lt;x-modal scroll=&quot;inside&quot;', false)
            ->assertSee('&lt;x-modal scroll=&quot;outside&quot; backdrop-variant=&quot;blur&quot;&gt;', false);

        $this->assertStringContainsString("'scroll' => 'inside'", $modal);
        $this->assertStringContainsString('[data-scroll="inside"] .app-modal-body', $styles);
        $this->assertStringContainsString('[data-scroll="inside"] .app-modal-content[data-fullscreen="true"]', $styles);
        $this->assertStringContainsString('[data-scroll="outside"]', $styles);
        $this->assertStringContainsString("root.addEventListener('click'", $script);
    }

    public function test_long_fields_drop_underlined_and_dark_tokens_are_complete(): void
    {
        $this->get('/components/textarea')
            ->assertOk()
            ->assertDontSee('app-textarea-underlined', false);

        $this->get('/components/rich-text-editor')
            ->assertOk()
            ->assertDontSee('app-rich-text-editor-underlined', false);

        $theme = file_get_contents(base_path('../package/resources/css/components/theme.css'));
        $this->assertStringContainsString('--default-50: hsl(240 5.88% 10%)', $theme);
        $this->assertStringContainsString('--default-900: hsl(0 0% 98.04%)', $theme);
        $this->assertStringContainsString('--surface-hover:', $theme);
    }

    public function test_date_presets_are_localized_and_customizable(): void
    {
        $this->get('/components/date-picker')
            ->assertOk()
            ->assertSee('최근 7일')
            ->assertSee('올해 현재까지')
            ->assertSee('지난 일주일')
            ->assertSee('1분기');
    }

    public function test_icon_documentation_lists_bundled_solar_icons(): void
    {
        $response = $this->get('/components/icon')->assertOk();

        foreach (['calendar-search-linear', 'menu-dots-bold', 'settings-linear', 'widget-4-linear'] as $icon) {
            $response->assertSee($icon);
        }

        $response->assertSee('현재 패키지에 포함된 로컬 아이콘입니다.');
    }

    public function test_requested_solar_and_material_icons_are_bundled(): void
    {
        $bundle = file_get_contents(base_path('../package/resources/js/icons/iconify-extra.js'));
        $renderer = file_get_contents(base_path('../package/resources/js/components/icon.js'));

        foreach ([
            'add-rounded', 'bolt-bold', 'bookmark-linear', 'phone-calling-bold',
            'home-angle-2-linear', 'paperclip-2-bold', 'directions-car-rounded',
            'car-crash-outline-rounded', 'car-gear-rounded', 'car-tag-outline-rounded',
            'zip-file-linear', 'info-circle-bold', 'info-circle-linear',
            'square-top-down-linear',
        ] as $icon) {
            $this->assertStringContainsString('"' . $icon . '"', $bundle);
        }

        $this->assertStringContainsString("iconifyExtra['material-symbols']", $renderer);

        $this->get('/components/icon')
            ->assertOk()
            ->assertSee('material-symbols:add-rounded')
            ->assertSee('solar:bolt-bold')
            ->assertSee('material-symbols:car-tag-outline-rounded')
            ->assertSee('solar:zip-file-linear')
            ->assertSee('solar:info-circle-bold')
            ->assertSee('solar:info-circle-linear')
            ->assertSee('solar:square-top-down-linear');
    }

    public function test_usage_documentation_describes_direct_source_copying(): void
    {
        $this->get('/installation')
            ->assertOk()
            ->assertSee('JDS 소스를 Laravel 프로젝트에 직접 복사해서 사용합니다.')
            ->assertSee('resources/views/components')
            ->assertSee('public/jds/jds.css')
            ->assertDontSee('composer require', false)
            ->assertDontSee('vendor/jds', false);

        $readme = file_get_contents(base_path('../README.md'));

        $this->assertStringContainsString('직접 복사해서 사용하는', $readme);
        $this->assertStringNotContainsString('composer require', $readme);
        $this->assertFileDoesNotExist(base_path('../composer.json'));
        $this->assertFileDoesNotExist(base_path('../package/src/JdsServiceProvider.php'));
        $this->assertFileDoesNotExist(base_path('../package/src/Http/Middleware/InjectJdsAssets.php'));
    }

    public function test_unknown_component_returns_not_found(): void
    {
        $this->get('/components/not-a-component')->assertNotFound();
    }
}
