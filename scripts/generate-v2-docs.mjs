import {mkdirSync, readdirSync, rmSync, writeFileSync} from 'node:fs'
import {join} from 'node:path'

const root = new URL('../docs/content/components/', import.meta.url)
mkdirSync(root, {recursive: true})
for (const file of readdirSync(root)) if (file.endsWith('.mdx')) rmSync(new URL(file, root))

const entries = [
['accordion','Accordion','접고 펼칠 수 있는 콘텐츠 영역입니다.',`<x-accordion><x-accordion-item value="one" title="첫 번째 항목">상세 내용</x-accordion-item><x-accordion-item value="two" title="두 번째 항목">추가 내용</x-accordion-item></x-accordion>`],
['autocomplete','Autocomplete','입력한 문자열로 선택 항목을 필터링합니다.',`<x-autocomplete name="city" label="도시"><x-autocomplete-item value="seoul">서울</x-autocomplete-item><x-autocomplete-item value="busan">부산</x-autocomplete-item></x-autocomplete>`],
['alert','Alert','중요한 상태와 피드백을 전달합니다.',`<x-alert title="저장되었습니다" description="변경 사항을 반영했습니다." color="success" />`],
['avatar','Avatar','사용자 또는 개체를 이미지와 이름으로 표현합니다.',`<x-avatar src="https://i.pravatar.cc/96" name="홍길동" />`],
['badge','Badge','다른 요소 위에 짧은 상태 정보를 표시합니다.',`<x-badge content="3" color="danger"><x-avatar name="알림" /></x-badge>`],
['breadcrumbs','Breadcrumbs','현재 페이지의 계층과 이동 경로를 보여줍니다.',`<x-breadcrumbs><x-breadcrumb-item href="/">Home</x-breadcrumb-item><x-breadcrumb-item href="/components">Components</x-breadcrumb-item><x-breadcrumb-item current>Breadcrumbs</x-breadcrumb-item></x-breadcrumbs>`],
['button','Button','사용자가 작업을 실행하도록 합니다.',`<x-button color="primary">저장</x-button>`],
['calendar','Calendar','하나 이상의 날짜를 달력에서 선택합니다.',`<x-calendar name="date" value="2026-08-05" />`],
['card','Card','관련 콘텐츠와 작업을 하나의 표면에 묶습니다.',`<x-card><x-card-header><x-card-title>차량 정보</x-card-title></x-card-header><x-card-body>등록 차량의 상세 정보입니다.</x-card-body><x-card-footer><x-button size="sm">확인</x-button></x-card-footer></x-card>`],
['checkbox','Checkbox','서로 독립적인 참·거짓 값을 선택합니다.',`<x-checkbox name="agree" value="yes">약관에 동의합니다</x-checkbox>`],
['checkbox-group','Checkbox Group','여러 체크박스를 하나의 질문으로 묶습니다.',`<x-checkbox-group label="알림"><x-checkbox name="channels[]" value="email">이메일</x-checkbox><x-checkbox name="channels[]" value="sms">문자</x-checkbox></x-checkbox-group>`],
['chip','Chip','짧은 값, 필터 또는 상태를 간결하게 표시합니다.',`<x-chip color="primary">진행 중</x-chip>`],
['circular-progress','Circular Progress','진행률을 원형 지표로 표시합니다.',`<x-circular-progress value="68" label="진행률" />`],
['code','Code','짧은 코드 토큰을 본문 안에 표시합니다.',`<x-code>composer install</x-code>`],
['date-input','Date Input','날짜를 접근 가능한 숫자 세그먼트로 입력합니다.',`<x-date-input name="started_at" value="2026-08-05" label="시작일" />`],
['date-picker','Date Picker','입력 필드와 달력 팝오버로 날짜를 선택합니다.',`<x-date-picker name="date" value="2026-08-05" label="예약일" />`],
['date-range-picker','Date Range Picker','시작일과 종료일을 한 번에 선택합니다.',`<x-date-range-picker name="period" :value="['start'=>'2026-08-05','end'=>'2026-08-12']" label="기간" />`],
['divider','Divider','콘텐츠 그룹 사이를 시각적으로 구분합니다.',`<div>위 콘텐츠</div><x-divider /><div>아래 콘텐츠</div>`],
['dropdown','Dropdown','트리거에서 작업 메뉴를 엽니다.',`<x-dropdown><x-dropdown-trigger><x-button>메뉴</x-button></x-dropdown-trigger><x-dropdown-content><x-dropdown-item key="edit">수정</x-dropdown-item><x-dropdown-item key="delete" color="danger">삭제</x-dropdown-item></x-dropdown-content></x-dropdown>`],
['drawer','Drawer','화면 가장자리에서 보조 패널을 엽니다.',`<x-drawer><x-drawer-trigger><x-button>필터 열기</x-button></x-drawer-trigger><x-drawer-content><x-drawer-header>필터</x-drawer-header><x-drawer-body>필터 조건을 선택하세요.</x-drawer-body></x-drawer-content></x-drawer>`],
['form','Form','필드 검증과 일반 HTML 폼 전송을 구성합니다.',`<x-form method="post" action="/save"><x-input name="name" label="이름" required /><x-button type="submit">저장</x-button></x-form>`],
['image','Image','로딩과 대체 콘텐츠를 지원하는 이미지를 표시합니다.',`<x-image src="https://picsum.photos/480/240" alt="샘플 풍경" width="480" />`],
['input','Input','한 줄 텍스트 값을 입력받습니다.',`<x-input name="email" type="email" label="이메일" placeholder="name@example.com" />`],
['input-otp','Input OTP','일회용 인증 코드를 여러 칸에 입력받습니다.',`<x-input-otp name="code" length="6" />`],
['kbd','Kbd','키보드 키 또는 단축키를 표시합니다.',`<x-kbd>Ctrl</x-kbd> + <x-kbd>K</x-kbd>`],
['link','Link','다른 페이지나 위치로 이동합니다.',`<x-link href="/installation">설치 방법</x-link>`],
['listbox','Listbox','키보드로 탐색 가능한 선택 목록입니다.',`<x-listbox name="status"><x-listbox-item value="ready">준비</x-listbox-item><x-listbox-item value="done">완료</x-listbox-item></x-listbox>`],
['modal','Modal','현재 작업 위에 집중해야 할 대화상자를 엽니다.',`<x-modal><x-modal-trigger><x-button>열기</x-button></x-modal-trigger><x-modal-content><x-modal-header><x-modal-title>회원 추가</x-modal-title><x-modal-description>회원 정보를 입력하세요.</x-modal-description></x-modal-header><x-modal-body><x-input name="name" label="이름" /></x-modal-body><x-modal-footer><x-button data-modal-close>닫기</x-button></x-modal-footer></x-modal-content></x-modal>`],
['navbar','Navbar','서비스의 주요 탐색과 작업을 배치합니다.',`<x-navbar><x-navbar-brand>JDS</x-navbar-brand><x-navbar-content><x-navbar-item href="/">Home</x-navbar-item><x-navbar-item href="/components">Components</x-navbar-item></x-navbar-content><x-navbar-menu-toggle /><x-navbar-menu><x-navbar-menu-item href="/installation">Installation</x-navbar-menu-item></x-navbar-menu></x-navbar>`],
['number-input','Number Input','증감 버튼과 검증을 포함한 숫자를 입력받습니다.',`<x-number-input name="quantity" label="수량" value="1" min="1" max="10" />`],
['pagination','Pagination','여러 페이지 사이를 이동합니다.',`<x-pagination :total="12" :page="4" show-controls />`],
['popover','Popover','트리거 주변에 보조 콘텐츠를 표시합니다.',`<x-popover><x-popover-trigger><x-button>상세 보기</x-button></x-popover-trigger><x-popover-content>추가 정보입니다.</x-popover-content></x-popover>`],
['progress','Progress','작업의 진행 정도를 막대로 표시합니다.',`<x-progress value="62" label="업로드" show-value-label />`],
['radio-group','Radio Group','여러 선택지 중 하나를 선택합니다.',`<x-radio-group name="plan" value="basic" label="요금제"><x-radio value="basic" label="기본" checked /><x-radio value="pro" label="프로" /></x-radio-group>`],
['range-calendar','Range Calendar','달력에서 시작일과 종료일 범위를 선택합니다.',`<x-range-calendar name="period" :value="['start'=>'2026-08-05','end'=>'2026-08-12']" :visible-months="2" />`],
['scroll-shadow','Scroll Shadow','스크롤 가능한 영역의 경계를 그림자로 알립니다.',`<x-scroll-shadow style="height:8rem"><p>첫 번째 내용</p><p>두 번째 내용</p><p>세 번째 내용</p><p>네 번째 내용</p><p>다섯 번째 내용</p></x-scroll-shadow>`],
['select','Select','목록에서 하나 이상의 값을 선택합니다.',`<x-select name="team" label="팀"><x-select-item value="sales">영업팀</x-select-item><x-select-item value="support">지원팀</x-select-item></x-select>`],
['skeleton','Skeleton','콘텐츠가 준비될 때까지 자리 표시자를 보여줍니다.',`<x-skeleton style="width:16rem;height:3rem" />`],
['slider','Slider','연속 범위 안에서 숫자 값을 선택합니다.',`<x-slider name="volume" label="음량" value="40" min="0" max="100" />`],
['snippet','Snippet','복사 가능한 코드나 명령을 표시합니다.',`<x-snippet>composer require jetcar/jds</x-snippet>`],
['spacer','Spacer','레이아웃 사이에 명시적인 빈 공간을 둡니다.',`<span>앞</span><x-spacer x="2" y="2" /><span>뒤</span>`],
['spinner','Spinner','완료 시점을 알 수 없는 진행 상태를 표시합니다.',`<x-spinner label="불러오는 중" color="primary" />`],
['switch','Switch','설정을 켜거나 끕니다.',`<x-switch name="notifications" checked>알림 받기</x-switch>`],
['table','Table','구조화된 행과 열 데이터를 표시하고 선택합니다.',`<x-table selection-mode="single"><x-table-header><x-table-row><x-table-column>이름</x-table-column><x-table-column>상태</x-table-column></x-table-row></x-table-header><x-table-body><x-table-row key="1" selectable><x-table-cell>홍길동</x-table-cell><x-table-cell>활성</x-table-cell></x-table-row></x-table-body></x-table>`],
['tabs','Tabs','관련된 콘텐츠 패널 사이를 전환합니다.',`<x-tabs value="photos"><x-tabs-list><x-tabs-trigger value="photos">사진</x-tabs-trigger><x-tabs-trigger value="music">음악</x-tabs-trigger></x-tabs-list><x-tabs-content value="photos">사진 콘텐츠</x-tabs-content><x-tabs-content value="music">음악 콘텐츠</x-tabs-content></x-tabs>`],
['toast','Toast','작업 결과를 방해하지 않는 임시 알림으로 표시합니다.',`<x-toast-region><x-toast title="저장 완료" description="변경 사항을 반영했습니다." color="success" /></x-toast-region>`],
['textarea','Textarea','여러 줄 텍스트를 입력받습니다.',`<x-textarea name="memo" label="메모" placeholder="내용을 입력하세요" />`],
['time-input','Time Input','시간을 접근 가능한 숫자 세그먼트로 입력합니다.',`<x-time-input name="time" value="14:30" label="방문 시간" />`],
['tooltip','Tooltip','호버 또는 포커스 시 짧은 도움말을 표시합니다.',`<x-tooltip content="설정을 엽니다"><x-button icon-only aria-label="설정">⚙</x-button></x-tooltip>`],
['user','User','아바타와 사용자 정보를 함께 표시합니다.',`<x-user name="홍길동" description="관리자" avatar="https://i.pravatar.cc/96" />`],
]

const variants = {
  accordion: ['light','shadow','bordered','splitted'],
  alert: ['flat','solid','bordered'],
  autocomplete: ['flat','bordered','faded','underlined'],
  button: ['solid','faded','bordered','light','flat','ghost','shadow'],
  chip: ['solid','bordered','light','flat','faded'],
  input: ['flat','bordered','faded','underlined'],
  'date-input': ['flat','bordered','faded','underlined'],
  'time-input': ['flat','bordered','faded','underlined'],
  'number-input': ['flat','bordered','faded','underlined'],
  select: ['flat','bordered','faded','underlined'],
  'date-picker': ['flat','bordered','faded','underlined'],
  'date-range-picker': ['flat','bordered','faded','underlined'],
  pagination: ['flat','bordered'],
  tabs: ['solid','underlined','bordered','light'],
  textarea: ['flat','bordered','faded'],
}
const colored = new Set(['alert','avatar','badge','button','calendar','checkbox','checkbox-group','chip','circular-progress','date-input','date-picker','date-range-picker','link','listbox','number-input','pagination','progress','radio-group','select','slider','spinner','switch','table','tabs','time-input','toast'])
const sized = new Set(['avatar','breadcrumbs','button','circular-progress','date-input','date-picker','date-range-picker','input','input-otp','number-input','pagination','progress','select','slider','spinner','switch','tabs','time-input'])
const disabled = new Set(['accordion','autocomplete','button','checkbox','chip','date-input','date-picker','date-range-picker','input','input-otp','link','number-input','radio-group','select','slider','switch','textarea','time-input'])
const invalid = new Set(['autocomplete','checkbox-group','date-input','date-picker','date-range-picker','input','number-input','radio-group','select','textarea','time-input'])
const withAttrs = (example, slug, attrs) => example.replace(new RegExp(`<x-${slug}(?=[\\s/>])`), `<x-${slug} ${attrs}`)

for (const [slug,title,description,example] of entries) {
  let appearance = example
  if (variants[slug]) appearance = variants[slug].map(value => withAttrs(example,slug,`variant="${value}"`)).join('\n')
  if (colored.has(slug)) appearance += '\n' + ['default','primary','secondary','success','warning','danger'].map(value => withAttrs(example,slug,`color="${value}"`)).join('\n')
  if (sized.has(slug)) appearance += '\n' + ['sm','md','lg'].map(value => withAttrs(example,slug,`size="${value}"`)).join('\n')

  let states = `<div data-theme="dark" style="padding:1rem;background:var(--background);color:var(--foreground)">${example}</div>`
  if (slug === 'button') states = `${withAttrs(example,slug,'loading')}\n${withAttrs(example,slug,'disabled')}`
  else if (invalid.has(slug)) states = `${withAttrs(example,slug,'invalid error-message="입력값을 확인하세요"')}\n${disabled.has(slug)?withAttrs(example,slug,'disabled'):''}`
  else if (disabled.has(slug)) states = `${example}\n${withAttrs(example,slug,'disabled')}`
  else if (slug === 'progress') states = `${example}\n${withAttrs(example,slug,'indeterminate')}`
  else if (slug === 'skeleton') states = `${example}\n${withAttrs(example,slug,'loaded')}`
  else if (slug === 'alert' || slug === 'chip') states = `${example}\n${withAttrs(example,slug,'dismissible')}`
  else if (slug === 'card') states = `${example}\n${withAttrs(example,slug,'pressable hoverable')}`

  const source = `---\ntitle: ${title}\ndescription: ${description}\nparts: [${slug}]\n---\n\n## 기본 사용법\n\n${description}\n\n\`\`\`blade preview name="${slug}-basic"\n${example}\n\`\`\`\n\n## 형태와 색상\n\n컴포넌트가 제공하는 \`variant\`와 의미 색상은 용도에 맞게 조합합니다. 지원 값과 기본값은 아래 API 표에서 확인할 수 있습니다.\n\n\`\`\`blade preview name="${slug}-appearance"\n${appearance}\n\`\`\`\n\n## 크기와 상태\n\n\`sm\`, \`md\`, \`lg\` 크기와 disabled, invalid, selected, loading 상태는 컴포넌트 성격에 맞게 사용합니다. 상태는 루트의 안정적인 \`data-*\` 속성에도 반영됩니다.\n\n\`\`\`blade preview name="${slug}-states"\n${states}\n\`\`\`\n\n## 폼 전송\n\n입력 컴포넌트는 실제 input 또는 hidden input을 렌더링합니다. 표시 전용 컴포넌트는 폼 안에서도 값 전송에 관여하지 않습니다.\n\n\`\`\`blade preview name="${slug}-form"\n<form method="post">${example}<x-button type="submit" size="sm">전송</x-button></form>\n\`\`\`\n\n## 슬롯\n\n기본 슬롯과 컴포넌트별 named slot을 사용해 아이콘, 설명, 사용자 정의 콘텐츠를 배치합니다. 목록형 컴포넌트는 배열보다 명시적인 하위 컴포넌트를 기본으로 사용합니다.\n\n## 이벤트와 Controller\n\n값 변경 시 native \`input/change\`와 \`app-ui:${slug}:change\` 이벤트가 발생합니다. \`AppUI.get(element)\`로 controller를 가져와 지원되는 \`getValue\`, \`setValue\`, \`open\`, \`close\`, \`focus\`, \`destroy\`를 호출할 수 있습니다.\n\n## 접근성\n\n접근 가능한 이름을 제공하고 키보드 탐색, focus-visible, reduced motion을 보존합니다. 아이콘만 있는 작업에는 반드시 \`aria-label\`을 지정합니다.\n`
  writeFileSync(new URL(`${slug}.mdx`, root), source)
}

console.log(`Generated ${entries.length} component documents in ${join(root.pathname)}`)
