<!doctype html>
<html lang="ko" data-theme="light">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JDS v2 컴포넌트 테스트</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="padding:2rem;background:hsl(var(--background));color:hsl(var(--foreground))">
<main style="display:grid;gap:2rem;max-width:72rem;margin:auto">
    <header><h1>JDS v2 컴포넌트 테스트</h1><p>핵심 상태, 폼 전송, 중첩 Overlay를 한 화면에서 확인합니다.</p></header>

    <section style="display:grid;gap:1rem"><h2>Button · Input</h2>
        <div style="display:flex;flex-wrap:wrap;gap:.75rem">@foreach(['solid','faded','bordered','light','flat','ghost','shadow'] as $variant)<x-button :variant="$variant" color="primary">{{ $variant }}</x-button>@endforeach</div>
        <x-input name="email" label="이메일" placeholder="name@example.com" clearable />
        <x-textarea name="memo" label="메모" placeholder="내용을 입력하세요" />
    </section>

    <section style="display:grid;gap:1rem"><h2>Selection</h2>
        <x-checkbox-group label="알림" orientation="horizontal"><x-checkbox name="channels[]" value="email">이메일</x-checkbox><x-checkbox name="channels[]" value="sms">문자</x-checkbox></x-checkbox-group>
        <x-radio-group name="plan" value="basic" label="요금제" orientation="horizontal"><x-radio value="basic" label="기본" checked /><x-radio value="pro" label="프로" /></x-radio-group>
        <x-switch name="notification">알림 받기</x-switch>
        <x-select name="team" label="팀"><x-select-item value="sales">영업팀</x-select-item><x-select-item value="support">지원팀</x-select-item></x-select>
        <x-autocomplete name="city" label="도시"><x-autocomplete-item value="seoul">서울</x-autocomplete-item><x-autocomplete-item value="busan">부산</x-autocomplete-item></x-autocomplete>
    </section>

    <section style="display:grid;gap:1rem"><h2>Date</h2>
        <x-date-input name="date" value="2026-08-05" label="기준일" />
        <x-time-input name="time" value="14:30" label="시간" />
        <x-date-picker name="picked" value="2026-08-05" label="날짜 선택" />
        <x-date-range-picker name="period" :value="['start'=>'2026-08-05','end'=>'2026-08-12']" label="기간" />
    </section>

    <section style="display:grid;gap:1rem"><h2>Tabs · Accordion</h2>
        <x-tabs value="one"><x-tabs-list><x-tabs-trigger value="one">첫 번째</x-tabs-trigger><x-tabs-trigger value="two">두 번째</x-tabs-trigger></x-tabs-list><x-tabs-content value="one">첫 번째 패널</x-tabs-content><x-tabs-content value="two">두 번째 패널</x-tabs-content></x-tabs>
        <x-accordion><x-accordion-item value="one" title="첫 번째">상세 내용</x-accordion-item><x-accordion-item value="two" title="두 번째">추가 내용</x-accordion-item></x-accordion>
    </section>

    <section style="display:grid;gap:1rem"><h2>Modal 안 Select</h2>
        <x-modal id="nested-modal" backdrop="blur"><x-modal-trigger><x-button>모달 열기</x-button></x-modal-trigger><x-modal-content><x-modal-header><x-modal-title>담당자 선택</x-modal-title><x-modal-description>Overlay 중첩과 focus 복원을 확인합니다.</x-modal-description></x-modal-header><x-modal-body style="min-height:18rem"><x-select name="manager" label="담당자"><x-select-item value="kim">김담당</x-select-item><x-select-item value="lee">이담당</x-select-item></x-select></x-modal-body><x-modal-footer><x-button data-modal-close>닫기</x-button></x-modal-footer></x-modal-content></x-modal>
    </section>
</main>
</body>
</html>
