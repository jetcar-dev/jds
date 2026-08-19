<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class TimeInputTest extends TestCase
{
    public function test_time_input_renders_heroui_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-time-input
                name="meeting_time"
                value="15:45:22+09:00[Asia/Seoul]"
                label="회의 시간"
                variant="bordered"
                color="primary"
                size="lg"
                radius="full"
                label-placement="outside-left"
                locale="ko-KR"
                granularity="second"
                :hour-cycle="24"
                time-zone="Asia/Seoul"
                min-value="09:00:00"
                max-value="18:00:00"
                required
            >
                <x-slot:startContent>시작</x-slot:startContent>
                <x-slot:endContent>KST</x-slot:endContent>
            </x-time-input>
        BLADE);

        foreach ([
            'data-ui-component="time-input"',
            'data-slot="base"',
            'data-slot="label"',
            'data-slot="input-wrapper"',
            'data-slot="inner-wrapper"',
            'data-slot="start-content"',
            'data-slot="input"',
            'data-slot="end-content"',
            'data-time-value',
            'data-variant="bordered"',
            'data-color="primary"',
            'data-size="lg"',
            'data-label-placement="outside-left"',
            'data-required="true"',
            'name="meeting_time"',
            'value="15:45:22+09:00[Asia/Seoul]"',
            '"granularity":"second"',
            '"hourCycle":24',
            '"timeZone":"Asia\\/Seoul"',
            '"minValue":"09:00:00"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_time_input_boolean_states_use_attribute_presence(): void
    {
        $html = Blade::render('<x-time-input label="시간" disabled read-only required invalid hide-time-zone auto-focus disable-animation full-width />');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-readonly="true"', $html);
        $this->assertStringContainsString('data-required="true"', $html);
        $this->assertStringContainsString('data-invalid="true"', $html);
        $this->assertStringContainsString('data-full-width="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('aria-readonly="true"', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_time_input_document_covers_supported_examples_and_api(): void
    {
        $response = $this->get('/components/time-input')->assertOk();

        foreach ([
            'time-input-basic',
            'time-input-required',
            'time-input-disabled',
            'time-input-readonly',
            'time-input-without-label',
            'time-input-description',
            'time-input-invalid',
            'time-input-label-placements',
            'time-input-content',
            'time-input-variants',
            'time-input-colors',
            'time-input-sizes',
            'time-input-radius',
            'time-input-granularity',
            'time-input-bounds',
            'time-input-placeholder',
            'time-input-hour-cycle',
            'time-input-time-zone',
            'time-input-form',
            'app-ui:time-input:change',
            'outside-left',
            'should-force-leading-zeros',
        ] as $fragment) {
            $response->assertSee($fragment);
        }
    }
}
