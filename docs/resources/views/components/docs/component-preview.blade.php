@props(['example', 'previewData' => []])

<div class="jds-docs-mdx-preview" data-preview-name="{{ $example['key'] }}">
    <x-tabs value="ui" class="jds-docs-example-tabs">
        <div class="jds-docs-example-toolbar">
            <x-tabs-list variant="underlined">
                <x-tabs-trigger value="ui">미리보기</x-tabs-trigger>
                <x-tabs-trigger value="code">코드</x-tabs-trigger>
            </x-tabs-list>
            <x-copy-button :value="$example['code']" label="예제 코드 복사">복사</x-copy-button>
        </div>

        <x-tabs-content value="ui" class="jds-docs-example-panel jds-docs-example-ui">
            <div class="jds-docs-rendered-example">
                {!! \Illuminate\Support\Facades\Blade::render($example['code'], $previewData) !!}
            </div>
        </x-tabs-content>

        <x-tabs-content value="code" class="jds-docs-example-panel jds-docs-example-code">
            <pre class="jds-docs-code"><code>{{ $example['code'] }}</code></pre>
        </x-tabs-content>
    </x-tabs>
</div>
