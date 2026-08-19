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

    <section id="alert-test" style="display:grid;gap:1rem"><h2>Alert</h2>
        <div style="display:grid;gap:.75rem">
            <x-alert color="default" title="기본 안내" description="일반적인 정보를 전달합니다." />
            <x-alert color="primary" variant="solid" title="Solid" description="강한 배경으로 강조합니다." />
            <x-alert color="secondary" variant="bordered" title="Bordered" description="투명한 배경과 테두리를 사용합니다." />
            <x-alert color="success" variant="flat" title="Flat" description="부드러운 배경을 사용합니다." />
            <x-alert color="warning" variant="faded" title="Faded" description="옅은 테두리를 함께 사용합니다." />
            <x-alert color="danger" closable title="닫을 수 있는 알림" description="닫기 버튼의 동작을 확인하세요." />
        </div>
    </section>

    <section id="button-test" style="display:grid;gap:1rem"><h2>Button</h2>
        <div data-button-variants style="display:flex;flex-wrap:wrap;gap:.75rem">@foreach(['solid','bordered','light','flat','faded','shadow','ghost'] as $variant)<x-button :variant="$variant" color="primary">{{ $variant }}</x-button>@endforeach</div>
        <div data-button-colors style="display:flex;flex-wrap:wrap;gap:.75rem">@foreach(['default','primary','secondary','success','warning','danger'] as $color)<x-button :color="$color">{{ $color }}</x-button>@endforeach</div>
        <div data-button-sizes style="display:flex;align-items:center;flex-wrap:wrap;gap:.75rem"><x-button size="sm">sm</x-button><x-button size="md">md</x-button><x-button size="lg">lg</x-button></div>
        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.75rem"><x-button loading color="primary">loading</x-button><x-button disabled>disabled</x-button><x-button icon-only color="primary" aria-label="빠른 실행"><x-icon name="solar:bolt-bold" /></x-button></div>
        <x-button color="primary" full-width>full width</x-button>
    </section>

    <section style="display:grid;gap:1rem"><h2>Input</h2>
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

    <section id="tabs-test" style="display:grid;gap:1rem"><h2>Tabs</h2>
        <div data-tabs-variants style="display:grid;gap:1rem">
            @foreach(['solid','underlined','bordered','light'] as $variant)
                <x-tabs :variant="$variant" color="primary" value="one"><x-tabs-list><x-tabs-trigger value="one">{{ ucfirst($variant) }}</x-tabs-trigger><x-tabs-trigger value="two">두 번째</x-tabs-trigger><x-tabs-trigger value="disabled" disabled>비활성화</x-tabs-trigger></x-tabs-list><x-tabs-content value="one">첫 번째 패널</x-tabs-content><x-tabs-content value="two">두 번째 패널</x-tabs-content><x-tabs-content value="disabled">비활성 패널</x-tabs-content></x-tabs>
            @endforeach
        </div>
        <x-tabs data-form-tabs name="period" value="day" full-width><x-tabs-list><x-tabs-trigger value="day">일간</x-tabs-trigger><x-tabs-trigger value="week">주간</x-tabs-trigger><x-tabs-trigger value="month">월간 보고서</x-tabs-trigger></x-tabs-list><x-tabs-content value="day">일간 콘텐츠</x-tabs-content><x-tabs-content value="week">주간 콘텐츠</x-tabs-content><x-tabs-content value="month">월간 콘텐츠</x-tabs-content></x-tabs>
        <x-tabs data-vertical-tabs placement="start" keyboard-activation="manual" value="profile"><x-tabs-list><x-tabs-trigger value="profile">프로필</x-tabs-trigger><x-tabs-trigger value="security">보안</x-tabs-trigger></x-tabs-list><x-tabs-content value="profile">프로필 콘텐츠</x-tabs-content><x-tabs-content value="security">보안 콘텐츠</x-tabs-content></x-tabs>
    </section>

    <section id="accordion-test" style="display:grid;gap:1rem"><h2>Accordion</h2>
        <div data-accordion-variants style="display:grid;gap:1rem">
            @foreach(['light','shadow','bordered','splitted'] as $variant)
                <x-accordion :variant="$variant" :default-selected-keys="['first']">
                    <x-accordion-item value="first" :title="ucfirst($variant).' 첫 번째'">첫 번째 내용입니다.</x-accordion-item>
                    <x-accordion-item value="second" title="두 번째">두 번째 내용입니다.</x-accordion-item>
                </x-accordion>
            @endforeach
        </div>
        <x-accordion data-multiple selection-mode="multiple" :default-selected-keys="['one']" :disabled-keys="['disabled']">
            <x-accordion-item value="one" title="첫 번째">첫 번째 내용</x-accordion-item>
            <x-accordion-item value="two" title="두 번째">두 번째 내용</x-accordion-item>
            <x-accordion-item value="disabled" title="비활성화">열리지 않습니다.</x-accordion-item>
        </x-accordion>
    </section>

    <section style="display:grid;gap:1rem"><h2>Modal 안 Select</h2>
        <x-modal id="nested-modal" backdrop="blur"><x-modal-trigger><x-button>모달 열기</x-button></x-modal-trigger><x-modal-content><x-modal-header><x-modal-title>담당자 선택</x-modal-title><x-modal-description>Overlay 중첩과 focus 복원을 확인합니다.</x-modal-description></x-modal-header><x-modal-body style="min-height:18rem"><x-select name="manager" label="담당자"><x-select-item value="kim">김담당</x-select-item><x-select-item value="lee">이담당</x-select-item></x-select></x-modal-body><x-modal-footer><x-button data-modal-close>닫기</x-button></x-modal-footer></x-modal-content></x-modal>
    </section>
</main>
</body>
</html>
