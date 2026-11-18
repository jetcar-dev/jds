---
name: jds-blade
description: Implement or review JDS v3 Laravel Blade components and interface examples using the corresponding HeroUI v3 appearance, behavior, state, and accessibility.
---

# JDS v3 Blade 개발 가이드

JDS v3는 HeroUI v3의 UI와 행동을 Laravel Blade, 일반 CSS, 브라우저 JavaScript로 구현한다.

## 스킬 설치

문서 앱의 [JDS 설치 스크립트](/skills/jds-blade/install.mjs)를 받아 Node.js로 실행하거나, JDS 저장소 루트에서 Skills CLI를 사용한다.

```bash
npx skills add ./skills/jds-blade --skill jds-blade
```

## 작업 대상 버전과 기술

| 영역 | JDS v3 기준 |
| --- | --- |
| 마크업 | Laravel Blade와 네이티브 HTML |
| 구성 | 루트와 하위 요소, 이름 있는 슬롯 |
| 스타일 | 일반 CSS, 의미 기반 변수, `jds-` BEM 클래스 |
| 동작 | HTML 기본 동작과 필요한 브라우저 JavaScript |
| 참조 버전 | 같은 버전의 HeroUI v3 문서, 소스, 배포 CSS |

JDS v2와 HeroUI v2의 예시는 버전별 레퍼런스로 취급한다. 작업 전에 대상 v3 컴포넌트의 문서와 구현을 확인한다.

## 핵심 원칙

1. **동작의 의미에 따라 변형을 고른다.** 한 작업 영역의 주 동작에는 `primary`, 대안에는 `secondary`, 닫기와 건너뛰기에는 `tertiary`, 삭제 같은 파괴적 동작에는 `danger`를 쓴다. 예를 들어 편집 화면에서 저장, 미리보기, 닫기는 각각 다른 역할을 가진다.
2. **설정보다 구성을 우선한다.** Card의 헤더, 본문, 푸터 같은 영역을 Blade 하위 요소나 슬롯으로 조합한다. 기본 사용에 필요한 속성만 요구하고, 선택 슬롯을 생략하면 해당 영역도 생략한다.
3. **OKLCH 색상과 CSS 변수로 테마를 구성한다.** 테마에서 배경과 전경을 쌍으로 정의하고, 컴포넌트는 해당 역할의 변수를 읽는다. 라이트와 다크는 같은 토큰 이름을 공유한다. 앱의 테마 변경은 CSS 변수로, 특정 컴포넌트의 스타일 변경은 공개 클래스로 적용한다.
4. **BEM 이름으로 구조와 변형을 드러낸다.** HeroUI v3의 구조에 `jds-`를 붙여 블록은 `jds-button`, 변형은 `jds-button--primary`, 하위 요소는 `jds-accordion__trigger`로 표기한다. 패키지 태그는 `<x-jds::button>` 형식을 사용한다.

### JDS 구현에 적용하기

- **접근성과 상태를 함께 설계한다.** 실행에는 `button`, 이동에는 `a[href]`를 사용하고 접근 가능한 이름을 제공한다. 상태가 바뀌면 스타일과 ARIA를 함께 갱신하며, 키보드 조작, 포커스 표시와 복귀, 모션 감소를 확인한다.
- **기본값과 공개 API를 일관되게 유지한다.** 변형과 크기의 허용 값, 기본값, 지원 슬롯을 문서화한다. 클래스 기반 Blade 컴포넌트에서는 PHP 타입과 입력 검증을 활용한다.
- **마크업·스타일·동작의 책임을 나눈다.** Blade는 HTML과 슬롯을 렌더링하고 CSS는 테마와 상태를 표현한다. JavaScript는 브라우저에서 상태나 상호작용을 관리해야 할 때 사용한다.
- **확장과 문서 예제가 실제 출력과 맞아야 한다.** 래퍼는 호출자가 전달한 `id`, 클래스, ARIA 속성을 대상 요소에 합친다. 미리보기와 복사 예제는 같은 Blade 원본을 사용한다.

## 문서와 원본 찾기

1. JDS 저장소를 수정할 때는 루트 `AGENTS.md`에서 작업 범위와 검수 기준을 확인한다. JDS 문서의 `/llms.txt`에서 관련 가이드를 찾고 화면 작업에는 `/DESIGN.md`를 참고한다.
2. 대응하는 HeroUI v3 컴포넌트 문서에서 공개 속성, 변형, 슬롯을 확인한다. 선택한 v3 버전의 소스에서는 `packages/react/src/components/<컴포넌트>/`에서 마크업과 상태를, `packages/styles/components/<컴포넌트>.css`와 `packages/styles/src/components/<컴포넌트>/`에서 CSS와 변형 기본값을 확인한다.
3. 화면에 들어가는 기본 색상이나 모션 토큰이 필요하면 같은 버전의 `packages/styles/themes/`를 확인한다. 가이드의 사용자 정의 예제와 배포 기본값이 다르면 JDS의 기본 동작은 배포본을 기준으로 삼는다.

### 로컬 HeroUI 조회 도구

스킬 디렉터리에서 다음 명령을 실행한다. 첫 인수로 확인할 HeroUI v3 체크아웃 경로를 전달한다. 컴포넌트나 가이드 이름은 kebab-case를 사용한다.

```bash
node scripts/inspect-heroui.mjs C:/Dev/heroui list
node scripts/inspect-heroui.mjs C:/Dev/heroui docs button
node scripts/inspect-heroui.mjs C:/Dev/heroui source button
node scripts/inspect-heroui.mjs C:/Dev/heroui styles button
node scripts/inspect-heroui.mjs C:/Dev/heroui theme
node scripts/inspect-heroui.mjs C:/Dev/heroui guide theming
```

### JDS 문서 주소

JDS 문서 서버에서 `/llms.txt`로 문서 목록을 보고, 여러 가이드가 필요하면 `/llms-full.txt`를 읽는다. 화면 기준은 `/DESIGN.md`, 저장소 작업 지침은 `/AGENTS.md`에 있다. 실제 컴포넌트 사용법은 `/docs/components`에 등록된 레퍼런스를 기준으로 확인한다.

## 개발 환경

JDS 저장소의 문서 앱은 루트에서 실행한다. 문서 서버 기본 주소는 `http://localhost:3403/docs`다.

```bash
npm ci
npm run dev
```

Blade 컴포넌트를 사용하는 앱에서는 현재 프로젝트에 등록된 태그와 스타일 진입점을 확인한다. 스타일을 구성할 때는 기본 스타일, 테마, 컴포넌트, 앱 재정의 순서로 CSS 레이어를 적용한다. 문서 앱의 Next.js와 Tailwind는 문서 환경에 사용한다.

### 적용 전에 확인할 것

- 대상 HeroUI v3 버전의 기본 변형, 크기, 슬롯 순서와 CSS 상태를 확인한다.
- 스타일 진입점이 기본 CSS와 테마를 컴포넌트 스타일보다 먼저 연결하는지 확인한다. JDS Blade의 배포 스타일은 일반 CSS로 적용한다.
- HTML 기본 동작과 컴포넌트가 제공하는 키보드·포커스 동작을 실제 렌더링 결과에서 확인한다.

## 컴포넌트 구성

복합 컴포넌트는 HeroUI v3의 Root, Header, Content, Footer 같은 구조를 Blade 하위 요소나 슬롯으로 표현한다. 아래는 Card의 출력 구조를 검토하기 위한 Blade 예시다. 현재 저장소에서 등록된 태그와 슬롯은 실제 레퍼런스를 확인한다.

```blade
<article class="jds-card">
    @isset($header)
        <header class="jds-card__header">{{ $header }}</header>
    @endisset
    <div class="jds-card__content">{{ $slot }}</div>
    @isset($footer)
        <footer class="jds-card__footer">{{ $footer }}</footer>
    @endisset
</article>
```

Button의 v3 기본 변형은 `primary`, 크기는 `md`다. 버튼은 명령 실행에, 링크는 페이지 이동에 사용한다. 링크에 버튼 스타일을 붙일 때도 `href`와 링크의 키보드 동작을 유지한다.

```blade
<a href="{{ url('/dashboard') }}" class="jds-button jds-button--primary jds-button--md">
    대시보드
</a>
```

## 의미 기반 변형

| 변형 | 적용 상황 |
| --- | --- |
| `primary` | 현재 화면의 주 동작 |
| `secondary` | 사용자가 선택할 수 있는 대안 동작 |
| `tertiary` | 닫기나 건너뛰기 같은 보조 동작 |
| `danger` | 삭제 같은 파괴적 동작 |
| `danger-soft` | 강조 강도를 낮춘 파괴적 동작 |
| `ghost` | 시각적 비중이 낮은 동작 |
| `outline` | 테두리로 구분하는 대안 동작 |

변형을 고를 때는 동작의 역할을 먼저 정하고, 배경과 전경은 테마 토큰에서 읽는다.

## 테마

라이트와 다크에 같은 토큰 이름을 사용한다. `--accent`와 `--surface`는 배경에, `--accent-foreground`와 `--surface-foreground`는 그 위의 텍스트나 아이콘에 적용한다.

```css
/* HeroUI v3 기본 라이트 테마의 일부 */
:root {
  --accent: oklch(0.6204 0.195 253.83);
  --accent-foreground: var(--snow);
  --background: oklch(0.9702 0 0);
  --foreground: var(--eclipse);
}

.jds-button--primary {
  background-color: var(--accent);
  color: var(--accent-foreground);
}
```

```html
<html lang="ko" data-theme="dark">
```

실제 값은 대상 v3 버전의 기본 테마를 확인한다. 앱이 테마를 바꿀 때는 `--background`와 `--foreground`처럼 함께 쓰는 배경·전경의 대비를 두 모드에서 검증한다.

## 구현과 검수

1. 등록된 JDS 태그와 슬롯을 사용하고, HeroUI의 기본 변형, 출력 요소, 슬롯 순서, 상태 선택자, 키보드와 포커스 동작을 보존한다.
2. 변경한 상태에서 라이트·다크, 포커스와 키보드, 비활성화, 모션 감소를 검증한다. 애니메이션 값은 시간, 지연, easing, 배율, 이동 방향과 거리까지 대조한다.
3. 문서 예시는 등록된 Blade 태그, 허용 속성, 슬롯과 실제 출력에 맞춘다. 설계 단계의 코드는 예제로 표시한다.
