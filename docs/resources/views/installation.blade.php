@extends('layouts.app')

@section('title', '설치하기 · JDS')

@section('content')
    <section class="jds-docs-intro">
        <span class="jds-docs-eyebrow">GETTING STARTED</span>
        <h1 class="jds-docs-title">설치하기</h1>
        <p>Laravel 프로젝트에 JDS Blade 컴포넌트와 빌드된 CSS·JavaScript를 연결합니다.</p>
    </section>

    <section class="jds-docs-installation">
        <div class="jds-docs-install-steps">
            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">1</span>
                <div class="jds-docs-install-content">
                    <strong>설치</strong>
                    <p>프로젝트에서 명령 한 줄로 설치합니다. 별도의 Composer 저장소 설정은 필요하지 않습니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>composer require jetcar/jds</code></pre>
                        <x-copy-button value="composer require jetcar/jds" label="설치 명령 복사">복사</x-copy-button>
                    </div>

                    <strong>업데이트</strong>
                    <div class="jds-docs-command">
                           <pre class="jds-docs-code"><code>composer clear-cache
composer update jetcar/jds -W
php artisan optimize:clear
composer show jetcar/jds</code></pre>
                        <x-copy-button value="composer clear-cache
composer update jetcar/jds -W
php artisan optimize:clear
composer show jetcar/jds" label="설치 명령 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">2</span>
                <div class="jds-docs-install-content">
                    <strong>자동 연결</strong>
                    <p>서비스 프로바이더와 Blade 컴포넌트가 자동 등록되고, 빌드된 CSS와 JavaScript도 HTML 응답에 한 번만 자동으로 연결됩니다.</p>
                    <p>레이아웃에 별도의 <code>&lt;link&gt;</code> 또는 <code>&lt;script&gt;</code>를 추가할 필요가 없습니다.</p>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">3</span>
                <div class="jds-docs-install-content">
                    <strong>Blade에서 사용</strong>
                    <p>camelCase PHP 속성은 kebab-case로 전달합니다. boolean은 <code>full-width</code>처럼 속성만 쓰면 true이며, 생략하면 false입니다. 배열, PHP 변수와 명시적인 false만 <code>:</code>를 사용합니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>{{ $usageCode }}</code></pre>
                        <x-copy-button :value="$usageCode" label="사용 코드 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
