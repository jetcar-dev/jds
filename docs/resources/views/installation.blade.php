@extends('layouts.app')

@section('title', '프로젝트에 추가하기 · JDS')

@section('content')
    <section class="jds-docs-intro">
        <span class="jds-docs-eyebrow">GETTING STARTED</span>
        <h1 class="jds-docs-title">프로젝트에 추가하기</h1>
        <p>JDS 소스를 Laravel 프로젝트에 직접 복사해서 사용합니다.</p>
    </section>

    <section class="jds-docs-installation">
        <div class="jds-docs-install-steps">
            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">1</span>
                <div class="jds-docs-install-content">
                    <strong>파일 복사</strong>
                    <p>Blade 컴포넌트를 대상 프로젝트의 컴포넌트 폴더에 복사합니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>package/resources/views/components → resources/views/components</code></pre>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">2</span>
                <div class="jds-docs-install-content">
                    <strong>압축 파일 빌드 및 복사</strong>
                    <p>JDS를 빌드하고 생성된 CSS와 JavaScript 두 파일만 대상 프로젝트의 <code>public/jds</code>에 복사합니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>cd package
npm install
npm run build

package/public/dist/jds.css → public/jds/jds.css
package/public/dist/jds.js  → public/jds/jds.js</code></pre>
                    </div>
                </div>
            </div>

            <div class="jds-docs-install-step">
                <span class="jds-docs-install-number">3</span>
                <div class="jds-docs-install-content">
                    <strong>레이아웃에 연결하고 사용</strong>
                    <p>공통 레이아웃에 압축 파일을 연결한 뒤 Blade 컴포넌트를 사용합니다.</p>
                    <div class="jds-docs-command">
                        <pre class="jds-docs-code"><code>&lt;link rel="stylesheet" href="&#123;&#123; asset('jds/jds.css') &#125;&#125;"&gt;
&lt;script type="module" src="&#123;&#123; asset('jds/jds.js') &#125;&#125;"&gt;&lt;/script&gt;

{{ $usageCode }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
