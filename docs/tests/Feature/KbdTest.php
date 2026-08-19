<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class KbdTest extends TestCase
{
    public function test_kbd_renders_keys_content_and_accessible_labels(): void
    {
        $html = Blade::render('<x-kbd :keys="[\'command\', \'shift\']">N</x-kbd>');

        foreach ([
            'data-ui-component="kbd"',
            'data-slot="base"',
            'data-slot="abbr"',
            'title="Command"',
            'title="Shift"',
            '⌘',
            '⇧',
            'data-slot="content"',
            '>N<',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_kbd_renders_windows_key_labels(): void
    {
        $html = Blade::render('<x-kbd platform="windows" :keys="[\'ctrl\', \'shift\', \'escape\']" />');

        foreach (['data-platform="windows"', '>Ctrl<', '>Shift<', '>Esc<', 'data-slot="separator"', 'data-icon="material-symbols:add-rounded"'] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_kbd_document_contains_supported_examples(): void
    {
        $response = $this->get('/components/kbd')->assertOk();

        foreach (['kbd-basic', 'kbd-combinations', 'kbd-platforms', 'kbd-keys', 'kbd-content', 'command', 'capslock', 'pagedown', 'Windows'] as $key) {
            $response->assertSee($key);
        }

        $response->assertDontSee('app-ui:kbd:change');
    }
}
