# JetCar Design System v2

HeroUI v2의 공개 API와 사용자 경험을 Blade 문법으로 옮긴 50개 UI 컴포넌트입니다.
React, Tailwind CSS, Alpine, Livewire 런타임 없이 일반 CSS와 Vanilla JS로 동작합니다.

## 사용하기

릴리스의 `jetcar-jds-v2.0.0.zip`을 내려받아 다음 폴더를 Laravel 프로젝트로
복사합니다.

```text
package/resources/views/components → resources/views/components
package/public/dist/jds.css         → public/jds/jds.css
package/public/dist/jds.js          → public/jds/jds.js
```

공통 Blade 레이아웃에서 파일을 한 번 불러옵니다.

```blade
<link rel="stylesheet" href="{{ asset('jds/jds.css') }}">
<script type="module" src="{{ asset('jds/jds.js') }}"></script>
```

이후 컴포넌트는 Blade에서 바로 사용합니다. Boolean 속성은 값 없이 작성할 수
있고, 목록은 명시적인 하위 컴포넌트를 사용합니다.

```blade
<x-button color="primary" disabled>저장</x-button>

<x-select name="team" label="팀">
    <x-select-item value="sales">영업팀</x-select-item>
    <x-select-item value="support">지원팀</x-select-item>
</x-select>
```

## 테마 바꾸기

JDS 파일을 수정하지 않고 프로젝트 CSS에서 변수만 한 번 덮어씁니다. 중첩된
영역에는 `light`, `dark` 또는 `data-theme`을 사용할 수 있습니다.

```css
:root {
    --primary: 216 100% 43%;
    --focus: 216 100% 43%;
    --radius-md: .625rem;
}

.dark {
    --background: 216 28% 7%;
    --foreground: 210 25% 95%;
}
```

## 로컬 개발

문서와 번들 감시를 한 명령으로 실행합니다.

```bash
cd docs
composer install
npm install
npm run dev
```

배포 가능한 파일만 다시 만들려면 다음을 실행합니다.

```bash
cd package
npm install
npm run build
```

v2는 기존 JDS API와 호환되지 않습니다. 기존 프로젝트는 컴포넌트 사용처를
마이그레이션한 뒤 파일을 교체해야 합니다.
