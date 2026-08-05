@extends('layouts.app')

@section('title', $doc['title'] . ' · JDS')

@section('content')
    <header class="jds-docs-component-header">
        <h1 class="jds-docs-title">{{ $doc['title'] }}</h1>
        <p>{{ $doc['description'] }}</p>
    </header>

    <div class="jds-docs-component-layout">
        <article class="jds-docs-component-content jds-docs-mdx-content">
            @foreach($doc['segments'] as $segment)
                @if($segment['type'] === 'markdown')
                    <div class="jds-docs-prose">{!! $segment['html'] !!}</div>
                @else
                    <x-docs.component-preview :example="$segment['example']" />
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
