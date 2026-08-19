<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JDS')</title>
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        (() => {
            try {
                const saved = localStorage.getItem('jds-docs-theme');
                document.documentElement.dataset.theme = saved || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            } catch {
                document.documentElement.dataset.theme = 'light';
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="jds-docs">
<div class="jds-docs-shell">
    <header class="jds-docs-header">
        <a href="{{ route('installation') }}" class="jds-docs-brand">
            <span class="jds-docs-brand-mark" aria-hidden="true">JDS </span>
            <span class="jds-docs-brand-copy">
                <strong>JDS</strong>
                <span>JetCar Design System</span>
            </span>
        </a>

        <label class="jds-docs-header-search">
            <span aria-hidden="true">⌕</span>
            <input type="search" placeholder="Search components..." data-docs-component-search autocomplete="off">
        </label>

        <button class="jds-docs-theme-toggle" type="button" data-docs-theme-toggle aria-label="다크 모드로 전환">
            <x-icon name="solar:moon-bold" class="jds-docs-theme-moon" />
            <x-icon name="solar:sun-2-bold" class="jds-docs-theme-sun" />
        </button>

        <details class="jds-docs-mobile-nav">
            <summary aria-label="문서 메뉴 열기">Menu</summary>
            <nav aria-label="모바일 문서 목차">
                <a href="{{ route('installation') }}">Installation</a>
                <a href="{{ route('icons') }}">Icons</a>
                @foreach($componentDocs as $component)
                    <a href="{{ route('components.show', $component['slug']) }}">{{ $component['title'] }}</a>
                @endforeach
            </nav>
        </details>
    </header>

    <aside class="jds-docs-sidebar">
        <nav class="jds-docs-sidebar-nav" aria-label="문서 목차">
            <div class="jds-docs-sidebar-group">
                <div class="jds-docs-sidebar-title">시작하기</div>
                <a href="{{ route('installation') }}" @if(request()->routeIs('installation')) aria-current="page" @endif>프로젝트에 추가하기</a>
            </div>

            <div class="jds-docs-sidebar-group">
                <div class="jds-docs-sidebar-title">리소스</div>
                <a href="{{ route('icons') }}" @if(request()->routeIs('icons')) aria-current="page" @endif>Icons</a>
            </div>

            <div class="jds-docs-sidebar-group jds-docs-sidebar-components">
                <div class="jds-docs-sidebar-title">컴포넌트</div>
                <div class="jds-docs-sidebar-links">
                    @foreach($componentDocs as $component)
                        <a
                            href="{{ route('components.show', $component['slug']) }}"
                            @if(request()->routeIs('components.show') && request()->route('component') === $component['slug']) aria-current="page" @endif
                        >{{ $component['title'] }}</a>
                    @endforeach
                    <p class="jds-docs-sidebar-empty" data-docs-search-empty hidden>검색 결과가 없습니다.</p>
                </div>
            </div>
        </nav>
    </aside>

    <div class="jds-docs-toc-popover">
        @yield('toc-popover')
    </div>

    <main class="jds-docs-main">
        @yield('content')
    </main>

    <aside class="jds-docs-page-rail">
        @yield('page-toc')
    </aside>
</div>
</body>
</html>
