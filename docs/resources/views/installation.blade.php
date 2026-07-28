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
                    <strong>Composer 패키지 설치</strong>
                    <p>사내 Composer 저장소가 등록된 Laravel 프로젝트에서 설치합니다. 서비스 프로바이더와 Blade 컴포넌트 경로는 package discovery로 자동 등록됩니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>composer require jetcar/jds</code></pre>
                        <x-copy-button value="composer require jetcar/jds" label="설치 명령 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">2</span>
                <div class="jds-docs-install-content">
                    <strong>빌드된 리소스 배포</strong>
                    <p>패키지에 포함된 빌드 결과를 <code>public/vendor/jds</code>로 복사합니다. ERP에서 <code>npm run dev</code>를 계속 실행할 필요는 없습니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>php artisan vendor:publish --tag=jds-assets --force</code></pre>
                        <x-copy-button value="php artisan vendor:publish --tag=jds-assets --force" label="배포 명령 복사">복사</x-copy-button>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">3</span>
                <div class="jds-docs-install-content">
                    <strong>CSS와 JavaScript 연결</strong>
                    <p>공통 레이아웃에서 배포된 파일을 한 번씩 불러옵니다.</p>
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

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">5</span>
                <div class="jds-docs-install-content">
                    <strong>테마 색상 변경</strong>
                    <p>JDS는 Tailwind 없이 BlatUI의 의미 기반 CSS 변수를 사용합니다. 필요한 값만 애플리케이션 CSS에서 덮어쓰면 hover, focus, soft 상태까지 함께 반영됩니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>:root {
    --accent: #0485f7;
    --background: #f5f5f5;
    --surface: #ffffff;
    --field-background: #ffffff;
    --border: #dedee0;
    --separator: #e4e4e7;
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
