<!doctype html>
<html lang="ko" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JDS Icons</title>
    <link rel="stylesheet" href="{{ url('/_jds/jds.css') }}">
    <script type="module" src="{{ url('/_jds/jds.js') }}"></script>
    <style>
        html { color-scheme: light; background: hsl(var(--content1)); }
        html[data-theme="dark"] { color-scheme: dark; }
        * { box-sizing: border-box; }
        body { margin: 0; background: hsl(var(--content1)); color: hsl(var(--foreground)); font-family: var(--font-sans); }
        .icons { min-height: 100vh; padding: 1rem; }
        .icons-toolbar { position: sticky; z-index: 10; top: 0; display: flex; align-items: center; gap: .75rem; padding-bottom: 1rem; background: hsl(var(--content1) / .92); backdrop-filter: blur(10px); }
        .icons-search { display: flex; min-width: 0; flex: 1; align-items: center; gap: .625rem; height: 2.75rem; border-radius: .75rem; padding-inline: .875rem; background: hsl(var(--default-100)); color: hsl(var(--default-500)); }
        .icons-search:focus-within { box-shadow: inset 0 0 0 2px hsl(var(--focus)); }
        .icons-search .app-icon { width: 1.125rem; height: 1.125rem; }
        .icons-search input { min-width: 0; flex: 1; border: 0; outline: 0; background: transparent; color: hsl(var(--foreground)); font: inherit; }
        .icons-count { flex: none; color: hsl(var(--default-500)); font-size: .875rem; }
        .icons-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(10rem, 1fr)); gap: .625rem; }
        .icon-card { display: grid; min-width: 0; min-height: 7rem; place-items: center; align-content: center; gap: .75rem; border: 1px solid hsl(var(--divider)); border-radius: .75rem; padding: .75rem; background: hsl(var(--content1)); color: hsl(var(--foreground)); cursor: pointer; transition: background-color .15s ease, border-color .15s ease, transform .15s ease; }
        .icon-card:hover { border-color: hsl(var(--default-300)); background: hsl(var(--default-100)); }
        .icon-card:active { transform: scale(.98); }
        .icon-card:focus-visible { outline: 2px solid hsl(var(--focus)); outline-offset: 2px; }
        .icon-card > .app-icon { width: 1.75rem; height: 1.75rem; }
        .icon-card code { width: 100%; overflow: hidden; color: hsl(var(--default-600)); font-family: var(--font-mono); font-size: .6875rem; text-align: center; text-overflow: ellipsis; white-space: nowrap; }
        .icon-copy { color: hsl(var(--default-500)); font-size: .6875rem; opacity: 0; transition: opacity .15s ease; }
        .icon-card:hover .icon-copy, .icon-copy[data-copied="true"] { opacity: 1; }
        .icons-empty { color: hsl(var(--default-500)); text-align: center; padding-block: 4rem; }
        @media (max-width: 36rem) { .icons-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .icons { padding: .75rem; } }
    </style>
</head>
<body>
<main class="icons" data-icon-gallery>
    <div class="icons-toolbar">
        <label class="icons-search">
            <x-icon name="magnifer-linear" />
            <input type="search" placeholder="아이콘 이름 검색" data-icon-search autocomplete="off">
        </label>
        <span class="icons-count" data-icon-count>{{ number_format(count($iconNames)) }}개</span>
    </div>
    <div class="icons-grid" data-icon-list aria-live="polite"></div>
    <p class="icons-empty" data-icon-empty hidden>일치하는 아이콘이 없습니다.</p>
    <script type="application/json" data-icon-names>@json($iconNames)</script>
</main>
<script>
    (() => {
        const gallery = document.querySelector('[data-icon-gallery]');
        const search = gallery.querySelector('[data-icon-search]');
        const list = gallery.querySelector('[data-icon-list]');
        const count = gallery.querySelector('[data-icon-count]');
        const empty = gallery.querySelector('[data-icon-empty]');
        const names = JSON.parse(gallery.querySelector('[data-icon-names]').textContent || '[]');
        const fragment = document.createDocumentFragment();

        names.forEach(name => {
            const bladeCode = `<x-icon name="${name}" />`;
            const card = document.createElement('button');
            card.type = 'button';
            card.className = 'icon-card';
            card.dataset.iconName = name;
            card.dataset.copyCode = bladeCode;
            card.setAttribute('aria-label', `${name} 사용 코드 복사`);
            card.innerHTML = `<span class="app-icon" data-slot="icon" data-icon="${name}" aria-hidden="true"></span><code></code><span class="icon-copy">복사</span>`;
            card.querySelector('code').textContent = name;
            fragment.append(card);
        });
        list.append(fragment);

        const cards = [...list.children];
        const filter = () => {
            const query = search.value.trim().toLocaleLowerCase();
            let visible = 0;
            cards.forEach(card => {
                const matches = !query || card.dataset.iconName.toLocaleLowerCase().includes(query);
                card.hidden = !matches;
                if (matches) visible++;
            });
            count.textContent = `${visible.toLocaleString('ko-KR')}개`;
            empty.hidden = visible !== 0;
        };

        search.addEventListener('input', filter);
        gallery.addEventListener('click', async event => {
            const card = event.target.closest('.icon-card');
            if (!card) return;
            event.preventDefault();
            event.stopPropagation();

            const copyCode = card.dataset.copyCode;
            if (navigator.clipboard?.writeText) {
                await navigator.clipboard.writeText(copyCode);
            } else {
                const fallback = document.createElement('textarea');
                fallback.value = copyCode;
                fallback.setAttribute('readonly', '');
                fallback.style.position = 'fixed';
                fallback.style.opacity = '0';
                document.body.append(fallback);
                fallback.select();
                document.execCommand('copy');
                fallback.remove();
            }
            const label = card.querySelector('.icon-copy');
            label.textContent = '복사됨';
            label.dataset.copied = 'true';
            setTimeout(() => {
                label.textContent = '복사';
                delete label.dataset.copied;
            }, 1200);
        });
    })();
</script>
</body>
</html>
