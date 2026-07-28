@extends('layouts.app')

@section('title', $doc['title'] . ' · JDS')

@section('content')
    <header class="jds-docs-component-header">
        <h1 class="jds-docs-title">{{ $doc['title'] }}</h1>
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

    <div class="jds-docs-component-layout">
        <div class="jds-docs-component-content">
        @foreach($examples as $example)
        <section id="example-{{ $example['key'] }}" class="jds-docs-example-section" aria-labelledby="example-{{ $example['key'] }}-heading">
            <div class="jds-docs-example-heading">
                <h2 class="jds-docs-section-title" id="example-{{ $example['key'] }}-heading">{{ $example['title'] }}</h2>
                @if($example['description'] ?? null)
                    <p class="jds-docs-section-description">{{ $example['description'] }}</p>
                @endif
            </div>
            <x-tabs value="ui" class="jds-docs-example-tabs">
                <div class="jds-docs-example-toolbar">
                <x-tabs-list appearance="line">
                        <x-tabs-trigger value="ui">미리보기</x-tabs-trigger>
                        <x-tabs-trigger value="code">코드</x-tabs-trigger>
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

        <section id="api-reference" class="jds-docs-api-reference" aria-labelledby="api-reference-heading">
            <h2 class="jds-docs-section-title" id="api-reference-heading">API 안내</h2>
            <p class="jds-docs-section-description">구성 요소별 속성과 기본값, 기본 슬롯을 확인할 수 있습니다. 표에 없는 <code>class</code>, <code>id</code>, <code>data-*</code>, <code>aria-*</code> 속성도 그대로 전달됩니다.</p>
            <x-docs.api-reference :doc="$doc" />
        </section>
        </div>

        <aside class="jds-docs-page-rail">
            <nav class="jds-docs-page-index" aria-label="페이지 내 예제">
                <span>이 페이지에서</span>
                <div>
                    @foreach($examples as $example)
                        <a href="#example-{{ $example['key'] }}">{{ $example['title'] }}</a>
                    @endforeach
                    <a href="#api-reference">API 안내</a>
                </div>
            </nav>
        </aside>
    </div>
@endsection
