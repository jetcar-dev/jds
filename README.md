# JetCar Design System

Laravel 프로젝트에 직접 복사해서 사용하는 Blade 컴포넌트 모음입니다.
Composer 패키지나 별도의 서비스 프로바이더를 사용하지 않습니다.

## 다른 프로젝트에 적용

압축된 파일만 사용할 때는 먼저 JDS 루트에서 빌드합니다.

```bash
cd package
npm install
npm run build
```

생성된 두 파일을 대상 프로젝트의 `public/jds`에 복사합니다.

```text
package/public/dist/jds.css → public/jds/jds.css
package/public/dist/jds.js  → public/jds/jds.js
```

대상 프로젝트의 공통 레이아웃에 연결합니다.

```blade
<link rel="stylesheet" href="{{ asset('jds/jds.css') }}">
<script type="module" src="{{ asset('jds/jds.js') }}"></script>
```

Blade 컴포넌트는 `package/resources/views/components`의 내용을 대상 프로젝트에
복사합니다.

```text
package/resources/views/components → resources/views/components
```

```blade
<x-button>저장</x-button>
```

## 문서 개발

```bash
cd docs
composer install
npm install
npm run dev
```
