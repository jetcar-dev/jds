@extends('layouts.app')

@section('title', $doc['title'] . ' · JDS')

@section('content')
    <header class="jds-docs-component-header">
        <span class="jds-docs-eyebrow">COMPONENT</span>
        <div class="jds-docs-title">{{ $doc['title'] }}</div>
        <p>{{ $doc['description'] }}</p>
    </header>

    @php
        $examples = $doc['examples'];
        $previewData = [
            'workspaces' => $workspaces,
            'members' => [1 => '김민수', 2 => '이지은', 3 => '박서준'],
            'teams' => ['sales' => '영업팀', 'support' => '지원팀'],
            'roles' => ['admin' => '관리자', 'manager' => '매니저', 'viewer' => '조회자'],
            'options' => ['active' => '활성', 'inactive' => '비활성'],
            'savedFiles' => [[
                'id' => 101,
                'name' => 'office.svg',
                'size' => 18432,
                'mime' => 'image/svg+xml',
                'preview' => '/images/office.svg',
                'download' => '/images/office.svg',
            ]],
            'response' => ['status' => 'success', 'count' => 2, 'items' => [['id' => 1], ['id' => 2]]],
            'post' => (object) ['content' => '<h2>공지사항</h2><p>저장된 내용을 수정하세요.</p>'],
            'errors' => $errors,
        ];
    @endphp

    @foreach($examples as $example)
        <section class="jds-docs-example-section" aria-labelledby="example-{{ $example['key'] }}-heading">
            <div class="jds-docs-section-title" id="example-{{ $example['key'] }}-heading">{{ $example['title'] }}</div>
            @if($example['description'] ?? null)
                <p class="jds-docs-section-description">{{ $example['description'] }}</p>
            @endif
            <x-tabs value="ui" class="jds-docs-example-tabs">
                <div class="jds-docs-example-toolbar">
                    <x-tabs-list variant="segmented">
                        <x-tabs-trigger value="ui">UI</x-tabs-trigger>
                        <x-tabs-trigger value="code">Code</x-tabs-trigger>
                    </x-tabs-list>
                    <x-copy-button :value="$example['code']" label="{{ $example['title'] }} 예제 코드 복사">복사</x-copy-button>
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
        </section>
    @endforeach

    <section class="jds-docs-api-reference" aria-labelledby="api-reference-heading">
        <div class="jds-docs-section-title" id="api-reference-heading">API Reference</div>
        <p class="jds-docs-section-description">구성 컴포넌트별 속성, 기본값, default slot을 확인합니다. 표에 없는 <code>class</code>, <code>id</code>, <code>data-*</code>, <code>aria-*</code> 속성도 전달할 수 있습니다.</p>
        <x-docs.api-reference :doc="$doc" />
    </section>
@endsection
