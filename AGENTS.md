# AGENTS.md

JDS v3 저장소에서 작업하는 에이전트를 위한 지침입니다.

## 저장소 개요

JDS v3는 HeroUI v3의 디자인 구조를 따르는 Laravel Blade 컴포넌트 라이브러리입니다. 워크스페이스에 한국어 문서 앱과 Blade·스타일 패키지가 있습니다.

### 기술 스택

| 영역 | 기술 |
| --- | --- |
| 워크스페이스 | Node.js 22 이상, npm, Turborepo |
| 문서 앱 | Next.js, Fumadocs, MDX, TypeScript, Tailwind CSS v4 |
| Blade 예제 렌더러 | PHP 8.3 이상, Composer, Laravel |
| JDS 컴포넌트 | Laravel Blade, 일반 CSS, 필요한 브라우저 JavaScript |

의존성의 정확한 버전은 각 `package.json`과 `package-lock.json`을 확인합니다.

### 모노레포 구조

```text
apps/docs/
  content/docs/        한국어 MDX와 사이드바 순서(meta.json)
  src/app/            Next.js 라우트, 문서 검색 및 내보내기
  src/components/     문서에 사용하는 UI를 둘 위치
  src/demos/          렌더링과 복사에 사용하는 Blade 예제
  src/lib/            문서 소스와 Markdown 출력
  blade/              Laravel Blade 예제 렌더러
  source.config.ts    Fumadocs MDX 컬렉션 설정
  turbo.json          문서 앱에만 적용되는 Turbo 설정
packages/blade/      Blade 컴포넌트와 브라우저 동작
packages/styles/     컴포넌트 CSS와 기본 테마
skills/jds-blade/    Blade 작업 지침과 HeroUI 원본 조회 스크립트
package.json         npm 워크스페이스 범위와 루트 명령
package-lock.json    워크스페이스 의존성 락파일
turbo.json           공통 작업 설정
```

## 개발 명령

저장소 루트에서 실행합니다.

| 작업 | 명령 |
| --- | --- |
| 락파일 기준으로 의존성 설치 | `npm ci` |
| Blade 예제 의존성 설치 | `composer install --working-dir=apps/docs/blade` |
| 의존성 변경 후 락파일 갱신 | `npm install` |
| 문서 개발 서버 | `npm run dev` |
| 프로덕션 빌드 | `npm run build` |
| 빌드 결과 실행 | `npm start` |

문서 기본 주소는 `http://localhost:3403/docs`입니다.

## 동작 검증

모든 구현과 수정은 완료를 보고하기 전에 변경 범위에 맞춰 검수합니다. 실제로 확인한 결과와 검증하지 못한 항목을 완료 보고에 적습니다.

문서와 Blade 예제를 검증합니다. 별도 테스트 러너는 등록되어 있지 않습니다.

| 변경 | 확인할 동작 |
| --- | --- |
| MDX와 탐색 | `npm run build` 성공, 문서 URL과 사이드바 링크 일치 |
| 문서 라우트 | 개발 서버에서 대상 페이지와 존재하지 않는 페이지의 응답 |
| Blade 예제 | `php apps/docs/blade/render.php button.usage`의 HTML과 문서 미리보기 동작 |
| 검색과 출력 | 검색 결과의 URL과 `/AGENTS.md`, `/DESIGN.md`, `/llms.txt`, `/llms-full.txt`의 관련 내용 |

### Blade 컴포넌트의 런타임 검증

| 검증 대상 | 확인할 계약 |
| --- | --- |
| Blade 렌더링 | 기본 속성, 슬롯 생략과 조합, 최종 HTML 요소, ID와 ARIA 참조, 공개 `data-slot`과 BEM 클래스, 호출자가 전달한 속성 |
| 폼과 상태 | 접근성 이름과 오류 메시지, 비활성·읽기 전용 상태, 실제 폼 제출 값과 리셋, 화면 상태와 ARIA 상태의 일치 |
| 브라우저 동작 | 키보드 이동과 활성화, 오버레이 닫기와 포커스 복귀, 포인터 캡처와 드래그, 스크롤과 레이아웃 측정, 전환 종료, 모션 감소 |

동작 테스트는 역할·접근성 이름·레이블·텍스트로 요소를 찾고, 공개 상태 속성과 포커스·선택·제출 결과를 단언합니다. `data-slot`이나 BEM 클래스는 공개 계약에 해당하는 항목만 검사합니다. 색상·크기·모션의 일치는 대상 HeroUI 버전과 같은 상태에서 실제 렌더링이나 계산된 스타일을 비교해 확인합니다.

포커스 트랩, 실제 치수·스크롤 측정, 포인터 캡처, 전환 종료에 의존하는 변경은 브라우저에서 확인합니다. 검증 결과에는 실행한 검사와 실제로 확인한 상태를 적습니다.

## Git 커밋 규칙

커밋은 `<type>(선택 scope): <한국어 설명>` 형식을 사용합니다. 제목은 30자 이내로 쓰고 마침표를 붙이지 않습니다. 예시는 `docs: 문서 탐색 구조 정리`입니다. 본문이 필요하면 한 줄을 비우고 무엇을 왜 바꿨는지 적습니다.

## 컴포넌트 아키텍처

아래 기준은 JDS Blade 컴포넌트 구현을 요청받은 작업에 적용합니다. 문서 틀, 컴포넌트 패키지, 미리보기 앱은 각각 요청된 범위에서 작업합니다.

JDS는 대상 HeroUI v3 배포본의 시각적 결과와 상호작용을 Blade, 일반 CSS, 필요한 브라우저 JavaScript로 재현합니다. 구현 기술을 바꿀 때도 기본 스타일, 상태별 동작, 키보드 조작, 접근성 결과를 동일하게 유지합니다. 차이가 필요한 경우 작업 전에 사용자와 범위를 정합니다.

JDS Blade 컴포넌트를 설계하거나 수정·검수할 때는 [디자인 원칙](apps/docs/content/docs/getting-started/design-principles.mdx)의 구현·검수 기준에서 변경에 해당하는 행을 적용합니다. 기존 컴포넌트를 수정할 때는 그 컴포넌트의 공개 속성, 슬롯, 렌더링 결과를 먼저 확인합니다.

화면의 시각 및 상호작용 결정에는 [DESIGN.md 원본](apps/docs/content/docs/design.mdx)을 적용합니다. 색상과 파생 토큰, 간격·반경·그림자, 동작의 시각적 표현을 판단할 때 참조합니다. Blade 작업 절차와 공식 소스 조회 방법은 [JDS Blade 스킬](skills/jds-blade/SKILL.md)에서 확인합니다.

### 파일 구성과 새 컴포넌트

HeroUI 원본에서 컴포넌트의 공개 API, 슬롯, 마크업, CSS, 상태를 먼저 확인합니다. JDS에서는 Blade 마크업, 스타일, 브라우저 동작을 역할별로 구분합니다. 패키지 경로와 등록 방식은 구현 시 확정한 구조를 따릅니다.

1. 참고할 HeroUI v3 버전과 컴포넌트의 문서, 구현, 스타일 매핑, CSS 및 동작 테스트를 확인합니다.
2. 기본 variant와 크기, 슬롯 순서, 네이티브 HTML 및 ARIA 의미를 정리합니다. 기본 크기는 블록 클래스에 두고 크기 modifier에는 필요한 차이만 둡니다.
3. Blade 속성에서 공개 modifier 클래스로 매핑하고 선언은 컴포넌트 CSS에 둡니다. 공통 요소의 스타일을 먼저 불러오고 특화 스타일을 그 위에 적용합니다.
4. 패키지의 Blade 등록과 스타일 진입점에서 새 컴포넌트를 사용할 수 있는지 확인합니다. 구현과 함께 사용 예제와 지원 컴포넌트 목록을 갱신합니다.
5. 서버 렌더링과 영향받는 브라우저 동작을 확인합니다.

### 복합 컴포넌트와 공개 API

HeroUI의 루트와 하위 요소를 Blade로 조합할 수 있게 표현합니다. 예를 들어 Card의 Header, Title, Content, Footer는 독립적으로 배치할 수 있는 요소입니다. 공개 API는 실제로 등록된 Blade 태그, 속성, 슬롯을 기준으로 문서화합니다. 공통 Label, Description, FieldError 같은 요소가 있으면 여러 컴포넌트에서 조합해 씁니다.

호출자가 전달한 `id`, `class`, `aria-*`, `data-*`는 `$attributes`로 해당 의미를 가진 실제 HTML 요소에 전달합니다. 예를 들어 버튼 속성은 래퍼 대신 상호작용 요소에 연결합니다. 공개 하위 요소의 `data-slot` 이름과 레이블·패널의 ID 관계를 유지합니다.

### 스타일링과 테마

- 테마의 의미 기반 토큰과 컴포넌트의 variant 매핑을 구분합니다. 예를 들어 `accent`는 색상 토큰이고 `primary`는 버튼 variant입니다.
- 라이트·다크 모드는 동일한 토큰 이름을 사용합니다. 모드별 색상 값은 테마에서 해석합니다.
- 대상 HeroUI 배포본의 BEM 구조를 유지하고 JDS 공개 CSS 클래스에는 `jds-`를 붙입니다. 예를 들어 블록은 `jds-button`, 하위 요소는 `jds-accordion__trigger`, 변형은 `jds-button--primary`입니다.
- 패키지가 제공하는 Blade 컴포넌트는 `jds` 네임스페이스에 등록합니다. 문서와 앱에서는 `<x-jds::button>`처럼 호출합니다.
- 배포하는 JDS Blade 컴포넌트의 마크업과 스타일은 공개 BEM 클래스, 일반 CSS, CSS 변수로 작성합니다. 컴포넌트 동작이나 스타일을 위해 소비자에게 Tailwind 설치 또는 빌드를 요구하지 않습니다. 문서 앱에서는 Tailwind를 사용할 수 있습니다.
- 문서 앱에서 Tailwind 유틸리티를 조건에 따라 선택할 때는 유틸리티 이름 전체를 소스에 적습니다.

### 상호작용과 애니메이션

원본이 지원하는 네이티브 선택자와 상태 속성을 함께 적용합니다. 예를 들어 버튼 hover는 `@media (hover: hover)`에서 `:hover`와 `[data-hovered="true"]`를 사용하며, 누름은 `:active`와 `[data-pressed="true"]`를 사용합니다. 브라우저 컨트롤러에서 포커스를 관리하는 대상은 `[data-focus-visible="true"]`를 사용하고, 원본과 같이 `:focus-visible:not([data-focused])`로 네이티브 폴백을 제한합니다. 필드처럼 포커스 규칙이 다른 컴포넌트는 해당 스타일을 따릅니다.

애니메이션은 `data-entering`·`data-exiting`의 적용 시점, 재생 시간, 지연, easing, 배율, 이동 거리와 방향, 불투명도를 대상 배포본에 맞춥니다. 시스템의 모션 감소 설정과 HeroUI의 `data-reduce-motion`도 해당 동작에 포함합니다.

## 문서와 외부 참조

- 한국어 문서는 `apps/docs/content/docs/`에 작성하고, 탐색 순서는 같은 경로의 `meta.json`에서 관리합니다.
- 문서 URL은 `/docs`를 기준으로 작성합니다. 언어 코드가 붙은 경로를 추가하지 않습니다.
- 문서 앱 내부 import는 `@/`로 `apps/docs/src/`를, `@generated/`로 Fumadocs가 생성한 `apps/docs/.source/`를 참조합니다.
- `/DESIGN.md`, `/llms.txt`, `/llms-full.txt`는 문서 원본에서 생성합니다. 내용을 변경할 때는 MDX를 수정합니다. `.source`, `.next`, `.turbo`는 생성물입니다.
- App Router 코드를 수정할 때는 설치된 Next.js 버전의 가이드를 확인합니다. 공개 예제에는 문서화된 설치 방식으로 호출할 수 있는 Blade 태그와 자산을 사용합니다.
- 컴포넌트를 문서화할 때는 실제로 제공하는 속성, 슬롯, 접근성 동작을 기준으로 설명합니다. 미리보기를 추가할 때는 Blade 파일 하나를 렌더링과 복사 코드에 함께 사용합니다.
- 기본 사용법과 변형·상태 예제에는 패키지의 색상과 상태 스타일을 적용합니다. 미리보기 배치에 지정한 여백과 모서리 반경이 패키지 기본값과 다르면 문서에 명시합니다. 사용자 정의를 보여주는 예제에는 해당 CSS를 복사 코드에 포함합니다.
- HeroUI는 문서 구성과 예제의 참고로 사용합니다. 설명은 JDS Blade에서 독자가 실제로 할 일을 먼저 보여주고, API 이름은 코드와 동일하게 적습니다.
- 문서의 본문은 Pretendard, 코드는 JetBrains Mono를 사용합니다. 기존 자간과 행간을 유지하고, 제목과 본문 크기, 라이트·다크 모드의 글자 대비, 모바일 표와 API 이름의 줄바꿈을 브라우저에서 확인합니다.
- JDS의 기본 동작을 설명하는 예제는 컴포넌트와 같은 값을 사용합니다. HeroUI 가이드의 예제와 배포본의 기본값이 다를 때는 배포본을 기준으로 기본 동작을 설명합니다. 가이드의 값이 필요한 경우 사용자 정의 예제로 표시합니다.

참고 원본은 [HeroUI v3 디자인 기준](https://heroui.com/react/DESIGN.md), [공식 컴포넌트 문서](https://heroui.com/react/llms-full.txt), [HeroUI v3 저장소](https://github.com/heroui-inc/heroui/tree/v3)입니다. `heroui-react` MCP 연결을 사용할 수 있으면 공개 API와 예제를 조회합니다. 로컬 체크아웃이나 공식 문서에서도 확인할 수 있습니다. 세부 동작이나 스타일이 문서와 다르면 JDS가 대상으로 삼는 버전의 배포본을 기준으로 판단합니다.

## 현재 컴포넌트

- Button — `<x-jds::button>`. 지원 속성과 예제는 [Button 레퍼런스](apps/docs/content/docs/components/button.mdx)에서 확인합니다.
- Card — `<x-jds::card>`. 지원 속성과 예제는 [Card 레퍼런스](apps/docs/content/docs/components/card.mdx)에서 확인합니다.
- Checkbox — `<x-jds::checkbox>`. 지원 속성과 예제는 [Checkbox 레퍼런스](apps/docs/content/docs/components/checkbox.mdx)에서 확인합니다.
- Separator — `<x-jds::separator>`. 지원 속성과 예제는 [Separator 레퍼런스](apps/docs/content/docs/components/separator.mdx)에서 확인합니다.
- Surface — `<x-jds::surface>`. 지원 속성과 기본 스타일은 [Surface 레퍼런스](apps/docs/content/docs/components/surface.mdx)에서 확인합니다.
- Input — `<x-jds::input>`. 지원 속성과 예제는 [Input 레퍼런스](apps/docs/content/docs/components/input.mdx)에서 확인합니다.
- Label — `<x-jds::label>`. 지원 속성과 예제는 [Label 레퍼런스](apps/docs/content/docs/components/label.mdx)에서 확인합니다.
- TextArea — `<x-jds::textarea>`. 지원 속성과 예제는 [TextArea 레퍼런스](apps/docs/content/docs/components/text-area.mdx)에서 확인합니다.

## 놓치기 쉬운 점

1. `npm run build`는 문서 앱을 빌드하고 Blade 예제를 렌더링합니다. 먼저 문서의 Composer 의존성을 설치합니다.
2. `@generated/`가 가리키는 `.source/`는 Fumadocs가 생성합니다. 컬렉션 경로를 바꿀 때는 `source.config.ts`와 문서 출력에서 읽는 경로를 함께 확인합니다.
3. 문서 앱이 React를 사용하더라도 JDS 컴포넌트의 공개 API와 검증 대상은 Blade입니다.
