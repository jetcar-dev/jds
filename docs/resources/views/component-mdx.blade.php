@extends('layouts.app')

@section('title', $doc['title'] . ' · JDS')

@section('content')
    <header class="jds-docs-component-header">
        <h1 class="jds-docs-title">{{ $doc['title'] }}</h1>
        <p>{{ $doc['description'] }}</p>
    </header>

    @php
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
        <article class="jds-docs-component-content jds-docs-mdx-content">
            @foreach($doc['segments'] as $segment)
                @if($segment['type'] === 'markdown')
                    <div class="jds-docs-prose">{!! $segment['html'] !!}</div>
                @else
                    <x-docs.component-preview :example="$segment['example']" :preview-data="$previewData" />
                @endif
            @endforeach

            <section id="api-reference" class="jds-docs-api-reference" aria-labelledby="api-reference-heading">
                <h2 class="jds-docs-section-title" id="api-reference-heading">API 안내</h2>
                <p class="jds-docs-section-description">속성, 기본값과 슬롯을 확인할 수 있습니다. 일반 HTML 속성도 그대로 전달됩니다.</p>
                <x-docs.api-reference :doc="$doc" />
            </section>
        </article>

        <aside class="jds-docs-page-rail">
            <nav class="jds-docs-page-index" aria-label="페이지 내 목차">
                <span>On this page</span>
                <div>
                    @foreach($doc['headings'] as $heading)
                        <a href="#{{ $heading['id'] }}" @class(['is-child' => $heading['level'] === 3])>{{ $heading['title'] }}</a>
                    @endforeach
                    <a href="#api-reference">API 안내</a>
                </div>
            </nav>
        </aside>
    </div>
@endsection
