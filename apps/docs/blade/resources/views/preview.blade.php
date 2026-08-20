<!doctype html>
<html lang="ko" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $example['title'] }}</title>
    <link rel="stylesheet" href="{{ url('/_jds/jds.css') }}">
    <script type="module" src="{{ url('/_jds/jds.js') }}"></script>
    <style>@include('preview-style')</style>
</head>
<body>
    <main class="jds-blade-preview-root" data-preview-name="{{ $example['key'] }}">
        {!! \Illuminate\Support\Facades\Blade::render($example['code']) !!}
    </main>
    <script>
        (() => {
            const id = @json($component.'/'.$example['key']);
            let frame = 0;
            const visible = element => {
                const style = getComputedStyle(element);
                return !element.hidden && style.display !== 'none' && style.visibility !== 'hidden';
            };
            const report = () => {
                frame = 0;
                const overlays = [...document.querySelectorAll([
                    '.app-select-popover',
                    '.app-autocomplete-popover',
                    '.app-dropdown-content',
                    '.app-popover-base',
                    '.app-tooltip-content',
                    '.app-date-picker-popover',
                    '.app-date-range-picker-popover',
                    '.app-overlay-layer',
                ].join(','))].filter(visible);
                const overlayBottom = overlays.reduce((bottom, element) => {
                    const bounds = element.getBoundingClientRect();
                    return Math.max(bottom, bounds.bottom + scrollY + 16);
                }, 0);
                const previewRoot = document.querySelector('.jds-blade-preview-root');
                const rootBottom = previewRoot
                    ? previewRoot.getBoundingClientRect().bottom + scrollY + parseFloat(getComputedStyle(document.body).paddingBottom || '0')
                    : document.body.scrollHeight;
                const contentHeight = Math.max(
                    rootBottom,
                    overlayBottom,
                );
                parent.postMessage({
                    source: 'jds-blade-preview',
                    id,
                    height: Math.ceil(Math.max(contentHeight, overlays.length ? 480 : 120)),
                }, '*');
            };
            const scheduleReport = () => {
                if (frame) cancelAnimationFrame(frame);
                frame = requestAnimationFrame(() => requestAnimationFrame(report));
            };

            const resizeObserver = new ResizeObserver(scheduleReport);
            resizeObserver.observe(document.documentElement);
            resizeObserver.observe(document.body);
            new MutationObserver(scheduleReport).observe(document.body, {
                subtree: true,
                childList: true,
                attributes: true,
                attributeFilter: ['hidden', 'style', 'class', 'data-open', 'data-state'],
            });
            addEventListener('resize', scheduleReport);
            addEventListener('load', scheduleReport, {once: true});
            document.addEventListener('transitionend', scheduleReport, true);
            document.addEventListener('animationend', scheduleReport, true);
            scheduleReport();
        })();
    </script>
</body>
</html>
