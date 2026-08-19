<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class TextareaTest extends TestCase
{
    public function test_textarea_renders_heroui_slots_and_defaults(): void
    {
        $html = Blade::render('<x-textarea name="memo" label="설명" placeholder="내용 입력" clearable required />');

        $this->assertStringContainsString('data-ui-component="textarea"', $html);
        $this->assertStringContainsString('data-label-placement="inside"', $html);
        $this->assertStringContainsString('data-slot="input-wrapper"', $html);
        $this->assertStringContainsString('data-slot="inner-wrapper"', $html);
        $this->assertStringContainsString('data-slot="input"', $html);
        $this->assertStringContainsString('data-slot="clear-button"', $html);
        $this->assertStringContainsString('name="memo"', $html);
        $this->assertStringContainsString('required', $html);
    }

    public function test_textarea_supports_states_content_and_autosize_options(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-textarea
                label="설명"
                label-placement="outside-left"
                variant="bordered"
                color="primary"
                min-rows="2"
                max-rows="4"
                disable-autosize
                read-only
                invalid
                error-message="오류"
            >
                <x-slot:startContent>시작</x-slot:startContent>
                <x-slot:endContent>끝</x-slot:endContent>
            </x-textarea>
        BLADE);

        $this->assertStringContainsString('data-label-placement="outside-left"', $html);
        $this->assertStringContainsString('data-variant="bordered"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-min-rows="2"', $html);
        $this->assertStringContainsString('data-max-rows="4"', $html);
        $this->assertStringContainsString('data-disable-autosize="true"', $html);
        $this->assertStringContainsString('data-slot="start-content"', $html);
        $this->assertStringContainsString('data-slot="end-content"', $html);
        $this->assertStringContainsString('data-slot="error-message"', $html);
    }

    public function test_textarea_document_contains_complete_examples(): void
    {
        $content = file_get_contents(base_path('content/components/textarea.mdx'));

        foreach ([
            'textarea-basic',
            'textarea-clearable',
            'textarea-autosize',
            'textarea-disable-autosize',
            'textarea-variants',
            'textarea-colors',
            'textarea-invalid',
            'textarea-label-placement',
            'textarea-content',
            'textarea-form',
        ] as $example) {
            $this->assertStringContainsString($example, $content);
        }
    }
}
