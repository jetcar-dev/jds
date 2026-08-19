<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class CardTest extends TestCase
{
    public function test_card_renders_heroui_v2_structure_and_props(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-card pressable hoverable blurred footer-blurred disabled full-width radius="md" shadow="lg" disable-animation disable-ripple>
    <x-card-header>Header</x-card-header>
    <x-card-body>Body</x-card-body>
    <x-card-footer>Footer</x-card-footer>
</x-card>
BLADE);

        foreach ([
            '<button',
            'type="button"',
            'disabled',
            'data-slot="base"',
            'data-ui-component="card"',
            'data-radius="md"',
            'data-shadow="lg"',
            'data-pressable="true"',
            'data-hoverable="true"',
            'data-blurred="true"',
            'data-footer-blurred="true"',
            'data-disabled="true"',
            'data-full-width="true"',
            'data-disable-animation="true"',
            'data-disable-ripple="true"',
            'data-slot="header"',
            'data-slot="body"',
            'data-slot="footer"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_non_pressable_card_renders_as_layout_container(): void
    {
        $html = Blade::render('<x-card><x-card-body>Body</x-card-body></x-card>');

        $this->assertStringContainsString('<div', $html);
        $this->assertStringNotContainsString('role="button"', $html);
        $this->assertStringContainsString('data-radius="lg"', $html);
        $this->assertStringContainsString('data-shadow="md"', $html);
    }

    public function test_card_document_contains_heroui_examples(): void
    {
        $this->get('/components/card')
            ->assertOk()
            ->assertSee('card-divider')
            ->assertSee('card-image')
            ->assertSee('card-footer-blurred')
            ->assertSee('card-composition')
            ->assertSee('card-blurred')
            ->assertSee('card-pressable')
            ->assertSee('card-cover-image')
            ->assertSee('none, sm, md, lg 중에서 모서리 크기를 선택합니다.')
            ->assertDontSee('is-pressable')
            ->assertDontSee('is-disabled');
    }
}
