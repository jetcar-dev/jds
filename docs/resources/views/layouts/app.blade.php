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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="jds-docs">
<div class="jds-docs-shell">
    <aside class="jds-docs-sidebar">
        <a href="{{ route('installation') }}" class="jds-docs-brand">
            <strong>JDS</strong>
            <span>JetCar Design System</span>
        </a>

        <nav class="jds-docs-sidebar-nav" aria-label="문서 목차">
            <div class="jds-docs-sidebar-group">
                <div class="jds-docs-sidebar-title">시작하기</div>
                <a href="{{ route('installation') }}" @if(request()->routeIs('installation')) aria-current="page" @endif>설치하기</a>
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
                </div>
            </div>
        </nav>
    </aside>

    <main class="jds-docs-main">
        @yield('content')
    </main>
</div>
</body>
</html>
