<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class AvatarTest extends TestCase
{
    public function test_avatar_renders_heroui_slots_and_states(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-avatar
    src="/avatar.jpg"
    name="김민준"
    alt="프로필"
    size="lg"
    radius="md"
    color="primary"
    bordered
    disabled
    focusable
    show-fallback
    :image-attributes="['loading' => 'eager']"
/>
BLADE);

        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-ui-component="avatar"', $html);
        $this->assertStringContainsString('data-slot="img"', $html);
        $this->assertStringContainsString('data-slot="fallback"', $html);
        $this->assertStringContainsString('data-slot="name"', $html);
        $this->assertStringContainsString('data-size="lg"', $html);
        $this->assertStringContainsString('data-radius="md"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-bordered="true"', $html);
        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-focusable="true"', $html);
        $this->assertStringContainsString('data-show-fallback="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('alt="프로필"', $html);
        $this->assertStringContainsString('loading="eager"', $html);
    }

    public function test_avatar_supports_custom_fallback_and_group_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-avatar name="담당자" color="success">
    <x-slot:fallback><strong>JD</strong></x-slot:fallback>
</x-avatar>
<x-avatar-group :max="2" :total="7" size="sm" color="secondary" radius="lg" disabled grid>
    <x-avatar name="김민준" />
    <x-avatar name="이서연" />
    <x-avatar name="박지훈" />
</x-avatar-group>
BLADE);

        $this->assertStringContainsString('<strong>JD</strong>', $html);
        $this->assertStringContainsString('data-ui-component="avatar-group"', $html);
        $this->assertStringContainsString('data-slot="count"', $html);
        $this->assertStringContainsString('data-max="2"', $html);
        $this->assertStringContainsString('data-total="7"', $html);
        $this->assertStringContainsString('data-size="sm"', $html);
        $this->assertStringContainsString('data-color="secondary"', $html);
        $this->assertStringContainsString('data-radius="lg"', $html);
        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-grid="true"', $html);
    }

    public function test_avatar_document_contains_complete_examples(): void
    {
        $this->get('/components/avatar')
            ->assertOk()
            ->assertSee('avatar-sizes')
            ->assertSee('avatar-bordered')
            ->assertSee('avatar-fallbacks')
            ->assertSee('avatar-custom-fallback')
            ->assertSee('avatar-custom-icon')
            ->assertSee('avatar-group-count')
            ->assertSee('avatar-group-disabled')
            ->assertSee('avatar-group-grid');
    }
}
