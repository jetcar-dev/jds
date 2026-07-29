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
                    <p>Composer 저장소가 등록된 Laravel 프로젝트에서 설치합니다. 서비스 프로바이더와 Blade 컴포넌트 경로는 package discovery로 자동 등록됩니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>composer require jetcar/jds</code></pre>
                        <x-copy-button value="composer require jetcar/jds" label="설치 명령 복사">복사</x-copy-button>
                    </div>

                    <strong>업데이트</strong>
                    <div class="jds-docs-command">
                           <pre class="jds-docs-code"><code>composer clear-cache
composer update jetcar/jds -W
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
composer show jetcar/jds</code></pre>
                        <x-copy-button value="composer clear-cache
composer update jetcar/jds -W
php artisan vendor:publish --tag=jds-assets --force
php artisan optimize:clear
composer show jetcar/jds" label="설치 명령 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">3</span>
                <div class="jds-docs-install-content">
                    <strong>CSS와 JavaScript 연결</strong>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>php artisan vendor:publish --tag=jds-assets --force</code></pre>
                        <x-copy-button value="php artisan vendor:publish --tag=jds-assets --force" label="배포 명령 복사">복사</x-copy-button>
                    </div>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>{{ $assetCode }}</code></pre>
                        <x-copy-button :value="$assetCode" label="리소스 코드 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">4</span>
                <div class="jds-docs-install-content">
                    <strong>Blade에서 사용</strong>
                    <p>camelCase PHP 속성은 kebab-case로 전달합니다. 배열, boolean, PHP 변수는 속성명 앞에 <code>:</code>를 붙입니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>{{ $usageCode }}</code></pre>
                        <x-copy-button :value="$usageCode" label="사용 코드 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
