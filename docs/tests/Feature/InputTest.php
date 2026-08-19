<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class InputTest extends TestCase
{
    public function test_input_renders_heroui_slots_and_native_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-input id="email" name="email" type="email" value="user@example.com" label="이메일" placeholder="입력" description="안내" variant="bordered" color="primary" size="lg" radius="full" label-placement="outside-top" required invalid error-message="오류" maxlength="80" clearable>
    <x-slot:startContent>앞</x-slot:startContent>
    <x-slot:endContent>끝</x-slot:endContent>
</x-input>
BLADE);

        $this->assertStringContainsString('data-ui-component="input"', $html);
        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-slot="main-wrapper"', $html);
        $this->assertStringContainsString('data-slot="input-wrapper"', $html);
        $this->assertStringContainsString('data-slot="inner-wrapper"', $html);
        $this->assertStringContainsString('data-slot="input"', $html);
        $this->assertStringContainsString('data-slot="clear-button"', $html);
        $this->assertStringContainsString('data-slot="helper-wrapper"', $html);
        $this->assertStringContainsString('data-slot="error-message"', $html);
        $this->assertStringContainsString('data-variant="bordered"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-size="lg"', $html);
        $this->assertStringContainsString('data-radius="full"', $html);
        $this->assertStringContainsString('data-label-placement="outside-top"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringContainsString('maxlength="80"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('앞', $html);
        $this->assertStringContainsString('끝', $html);
    }

    public function test_input_boolean_states_and_password_toggle_render(): void
    {
        $html = Blade::render('<x-input type="password" label="비밀번호" disabled read-only password-toggle :full-width="false" disable-animation />');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-readonly="true"', $html);
        $this->assertStringContainsString('data-full-width="false"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('data-slot="password-toggle"', $html);
        $this->assertStringContainsString('data-password-visible-icon', $html);
        $this->assertStringContainsString('data-password-hidden-icon', $html);
    }

    public function test_input_document_contains_complete_examples(): void
    {
        $this->get('/components/input')
            ->assertOk()
            ->assertSee('input-disabled')
            ->assertSee('input-readonly')
            ->assertSee('input-sizes')
            ->assertSee('input-colors')
            ->assertSee('input-variants')
            ->assertSee('input-label-placement')
            ->assertSee('input-password')
            ->assertSee('input-clearable')
            ->assertSee('input-content')
            ->assertSee('input-invalid')
            ->assertSee('app-ui:input:clear');
    }
}
