@extends('layouts.app')

@section('title', 'Icons · JDS')

@section('content')
    <header class="jds-docs-component-header">
        <h1 class="jds-docs-title">Icons</h1>
        <p>JDS에 저장되어 별도 설치나 네트워크 요청 없이 사용할 수 있는 아이콘입니다.</p>
    </header>

    <section class="jds-icons-page" data-icon-gallery>
        <div class="jds-icons-guide">
            <h2>사용법</h2>
            <p>아이콘 이름에는 접두사를 포함할 수 있습니다. <code>solar:</code>를 생략하면 Solar 아이콘으로 처리합니다.</p>
            @php($iconUsage = html_entity_decode('&lt;x-icon name=&quot;solar:copy-linear&quot; /&gt;'))
            <x-snippet hide-symbol hide-copy-button :code-string="$iconUsage" />
        </div>

        <div class="jds-icons-toolbar">
            <label class="jds-icons-search">
                <span aria-hidden="true">⌕</span>
                <input type="search" placeholder="아이콘 이름 검색" data-icon-search autocomplete="off">
            </label>
            <span class="jds-icons-count" data-icon-count>{{ number_format(count($iconNames)) }}개</span>
        </div>

        <div class="jds-icon-gallery" data-icon-list aria-live="polite"></div>
        <script type="application/json" data-icon-names>@json($iconNames)</script>

        <p class="jds-icons-empty" data-icon-empty hidden>일치하는 아이콘이 없습니다.</p>
    </section>
@endsection
