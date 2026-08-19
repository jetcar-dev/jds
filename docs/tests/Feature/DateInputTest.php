<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class DateInputTest extends TestCase
{
    public function test_date_input_renders_heroui_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-date-input
                name="scheduled_at"
                value="2026-08-05T15:45"
                label="방문 일시"
                variant="bordered"
                color="primary"
                size="lg"
                radius="lg"
                label-placement="outside-left"
                granularity="minute"
                :hour-cycle="24"
                min-value="2026-08-01T00:00"
                max-value="2026-08-31T23:59"
                required
                clearable
            >
                <x-slot:startContent>시작</x-slot:startContent>
                <x-slot:endContent>KST</x-slot:endContent>
            </x-date-input>
        BLADE);

        foreach ([
            'data-ui-component="date-input"',
            'data-slot="base"',
            'data-slot="label"',
            'data-slot="input-wrapper"',
            'data-slot="inner-wrapper"',
            'data-slot="start-content"',
            'data-slot="input-field"',
            'data-slot="end-content"',
            'data-slot="clear-button"',
            'data-variant="bordered"',
            'data-color="primary"',
            'data-size="lg"',
            'data-label-placement="outside-left"',
            'data-required="true"',
            'data-date-value',
            'data-slot="input"',
            'type="text"',
            'name="scheduled_at"',
            '"granularity":"minute"',
            '"hourCycle":24',
            '"minValue":"2026-08-01T00:00"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_date_input_boolean_states_use_attribute_presence(): void
    {
        $html = Blade::render('<x-date-input label="날짜" disabled read-only required invalid auto-focus disable-animation />');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-readonly="true"', $html);
        $this->assertStringContainsString('data-required="true"', $html);
        $this->assertStringContainsString('data-invalid="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('aria-readonly="true"', $html);
    }

    public function test_date_input_document_covers_supported_examples_and_api(): void
    {
        $response = $this->get('/components/date-input')->assertOk();

        foreach ([
            'date-input-basic',
            'date-input-locales',
            'date-input-disabled',
            'date-input-readonly',
            'date-input-required',
            'date-input-variants',
            'date-input-label-placements',
            'date-input-content',
            'date-input-description',
            'date-input-invalid',
            'date-input-sizes',
            'date-input-radius',
            'date-input-colors',
            'date-input-bounds',
            'date-input-granularity',
            'date-input-clearable',
            'date-input-form',
            'date-input-placeholder',
            'app-ui:date-input:change',
            'outside-left',
            'clearable',
        ] as $fragment) {
            $response->assertSee($fragment);
        }
    }
}
