# JDS v3

Turborepo로 관리하는 Laravel Blade 컴포넌트와 Fumadocs 문서 워크스페이스입니다.

```text
apps/docs/
  content/docs/       한국어 MDX 문서
  src/app/           Next.js 페이지와 검색, 문서 내보내기 경로
  src/components/    문서용 UI를 둘 위치
  src/demos/         문서에서 렌더링하는 Blade 예제
  blade/             Laravel 예제 렌더러
packages/blade/      Blade 컴포넌트와 브라우저 스크립트
packages/styles/     일반 CSS 스타일과 테마
```

문서 개발과 빌드에는 Node.js 22 이상, npm, PHP 8.3 이상, Composer가 필요합니다.

```sh
npm ci
composer install --working-dir=apps/docs/blade
npm run dev
```

문서는 `http://localhost:3403/docs`에서 열립니다. 프로덕션 빌드는 `npm run build`로 실행합니다.

## Laravel 앱에서 사용하기

Laravel 앱의 Composer 경로 저장소에 JDS 체크아웃의 `packages/blade`를 추가하고 `jetcar/jds-blade:@dev`를 설치합니다. 패키지가 Blade 네임스페이스를 등록합니다.

스타일은 `packages/styles`를 npm 로컬 패키지로 연결한 뒤 앱 CSS에서 `@import "@jetcar/jds-styles";`로 불러옵니다. 기본 Button 글꼴은 패키지에 포함된 Pretendard Variable입니다. 앱에서 다른 글꼴이 필요하면 `--jds-font-sans`를 지정합니다. 상태 관리 스크립트는 `vendor/jetcar/jds-blade/index.js`를 앱 번들에 포함합니다. CSS와 스크립트는 브라우저에서 직접 사용할 수 있는 형식입니다.

```blade
<x-jds::button type="submit">저장</x-jds::button>
```
