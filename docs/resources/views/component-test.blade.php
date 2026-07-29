<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>컴포넌트 테스트 · JDS</title>
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; padding: 2rem;">
    <x-badge>Default</x-badge>
    <x-badge color="secondary">Secondary</x-badge>
    <x-badge color="danger">Danger</x-badge>
    <x-badge variant="outline">Outline</x-badge>
    <x-badge color="success">완료</x-badge>
    <x-badge color="warning" variant="faded">대기</x-badge>
    <x-badge color="danger" variant="outline">오류</x-badge>
    <x-badge color="primary"><x-icon name="info-circle-linear" /> 정보</x-badge>
</div>

<div style="display: grid; width: min(calc(100% - 4rem), 48rem); gap: 1rem; margin: 0 auto; padding: 2rem 0;">
    <section style="position: relative; left: 50%; display: grid; gap: 1rem; width: min(calc(100vw - 4rem), 76rem); transform: translateX(-50%); overflow-x: auto;">
        <div>
            <h1 style="margin: 0; font-size: 1.25rem;">Control size comparison</h1>
            <p style="margin: 0.375rem 0 0; color: var(--text-secondary); font-size: 0.875rem;">
                같은 size의 Input, Button, Select, Combobox, Group 외곽 높이를 비교합니다.
            </p>
        </div>

        @foreach([
            'xs' => '28px',
            'sm' => '32px',
            'md' => '40px',
            'lg' => '48px',
            'xl' => '56px',
        ] as $controlSize => $expectedHeight)
            <div style="display: grid; min-width: 52rem; grid-template-columns: 4rem repeat(5, minmax(8rem, 1fr)); align-items: start; gap: 0.75rem; padding-block: 0.5rem; border-bottom: 1px solid var(--separator);">
                <div style="display: grid; gap: 0.125rem; padding-top: 0.25rem;">
                    <strong style="font-family: var(--font-mono); font-size: 0.8125rem;">{{ $controlSize }}</strong>
                    <span style="color: var(--text-secondary); font-size: 0.75rem;">{{ $expectedHeight }}</span>
                </div>

                <div data-control-measure="Input" style="display: grid; gap: 0.25rem;">
                    <x-input :size="$controlSize" placeholder="Input" full-width />
                    <small data-control-height style="color: var(--text-secondary);"></small>
                </div>

                <div data-control-measure="Button" style="display: grid; gap: 0.25rem;">
                    <x-button :size="$controlSize" full-width>Button</x-button>
                    <small data-control-height style="color: var(--text-secondary);"></small>
                </div>

                <div data-control-measure="Select" style="display: grid; gap: 0.25rem;">
                    <x-select :size="$controlSize" :options="['one' => 'Select']" value="one" full-width />
                    <small data-control-height style="color: var(--text-secondary);"></small>
                </div>

                <div data-control-measure="Combobox" style="display: grid; gap: 0.25rem;">
                    <x-combobox :size="$controlSize" :options="['one' => 'Combobox']" value="one" full-width />
                    <small data-control-height style="color: var(--text-secondary);"></small>
                </div>

                <div data-control-measure="Group" style="display: grid; gap: 0.25rem;">
                    <x-group :size="$controlSize" full-width>
                        <x-input placeholder="Group" full-width />
                        <x-button>확인</x-button>
                    </x-group>
                    <small data-control-height style="color: var(--text-secondary);"></small>
                </div>
            </div>
        @endforeach
    </section>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr)); gap: 1rem;">
        @foreach(['default', 'secondary', 'tertiary', 'outline', 'transparent'] as $variant)
            <x-card :variant="$variant">
                <x-card-header>
                    <x-card-title>{{ ucfirst($variant) }}</x-card-title>
                    <x-card-description>Semantic surface variant</x-card-description>
                </x-card-header>
                <x-card-content>Card content</x-card-content>
                <x-card-footer><x-button size="sm">Action</x-button></x-card-footer>
            </x-card>
        @endforeach
    </div>

    <x-group full-width>
        <x-input placeholder="차량번호 또는 고객명" full-width />
        <x-button>검색</x-button>
    </x-group>

    <x-group full-width>
        <x-combobox
            name="workspace"
            :options="[1 => '제트카 본사', 2 => '서울지점', 3 => '부산지점']"
            placeholder="사업장 선택"
            full-width
        />
        <x-button>이동</x-button>
    </x-group>

    <x-group full-width>
        <x-select :options="['all' => '전체 상태', 'ready' => '대기', 'done' => '완료']" value="all" full-width />
        <x-date-picker name="inspection_date" full-width />
        <x-button color="primary">조회</x-button>
    </x-group>

    <x-group full-width>
        <x-datetime-picker name="scheduled_at" full-width />
        <x-time-field name="reminder_at" />
        <x-button color="secondary">예약</x-button>
    </x-group>

    <x-group full-width>
        <span>https://</span>
        <x-input placeholder="example.com" full-width />
        <x-button color="secondary">확인</x-button>
    </x-group>

    <x-group variant="bordered">
        <x-button variant="bordered">이전</x-button>
        <x-button>오늘</x-button>
        <x-button variant="faded">다음</x-button>
    </x-group>
    <x-input id="test-email" type="email" placeholder="name@example.com" full-width />
    <x-field>
        <x-label for="test-email" required>이메일</x-label>
        <x-input id="test-email" type="email" placeholder="name@example.com" full-width />
        <x-field-description>업무용 이메일을 입력하세요.</x-field-description>
    </x-field>
    <x-button variant="bordered">다음</x-button>
    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
        <x-checkbox name="terms" value="1" label="약관 동의" checked />
        <x-switch name="notifications" value="1" label="알림 받기" checked />
        <x-input-otp name="verification_code" :maxlength="6" :separator-at="3" />
    </div>

    <x-radio-group name="plan" value="standard" orientation="horizontal">
        <x-radio-group-item value="basic">기본</x-radio-group-item>
        <x-radio-group-item value="standard">스탠다드</x-radio-group-item>
        <x-radio-group-item value="team" disabled>팀</x-radio-group-item>
    </x-radio-group>

    <x-accordion value="shipping" collapsible>
        <x-accordion-item value="shipping">
            <x-accordion-trigger>배송 정보</x-accordion-trigger>
            <x-accordion-content>영업일 기준 2~3일 내 출고됩니다.</x-accordion-content>
        </x-accordion-item>
        <x-accordion-item value="returns">
            <x-accordion-trigger>반품 안내</x-accordion-trigger>
            <x-accordion-content>수령 후 7일 이내 신청할 수 있습니다.</x-accordion-content>
        </x-accordion-item>
    </x-accordion>

    <x-tabs full-width>
        <x-tabs-list>
            <x-tabs-trigger value="account">계정</x-tabs-trigger>
            <x-tabs-trigger value="security">보안</x-tabs-trigger>
        </x-tabs-list>
        <x-tabs-content value="account">계정 설정 내용</x-tabs-content>
        <x-tabs-content value="security">보안 설정 내용</x-tabs-content>
    </x-tabs>

    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
        <x-dropdown-menu>
            <x-dropdown-menu-trigger><x-button variant="bordered">메뉴</x-button></x-dropdown-menu-trigger>
            <x-dropdown-menu-content>
                <x-dropdown-menu-item href="/profile">프로필</x-dropdown-menu-item>
                <x-dropdown-menu-item href="/settings">설정</x-dropdown-menu-item>
                <x-dropdown-menu-separator />
                <x-dropdown-menu-item color="danger">삭제</x-dropdown-menu-item>
            </x-dropdown-menu-content>
        </x-dropdown-menu>

        <x-modal backdrop-variant="blur">
            <x-modal-trigger><x-button>회원 추가</x-button></x-modal-trigger>
            <x-modal-content>
                <x-modal-header>
                    <x-modal-title>회원 추가</x-modal-title>
                    <x-modal-description>새 회원 정보를 입력하세요.</x-modal-description>
                </x-modal-header>
                <div class="app-modal-body"><x-input name="member_name" placeholder="회원명" full-width /></div>
                <x-modal-footer>
                    <x-button variant="bordered" data-modal-close>취소</x-button>
                    <x-button>저장</x-button>
                </x-modal-footer>
            </x-modal-content>
        </x-modal>


        <x-button color="primary" variant="ghost">test</x-button>
        <x-input color="secondary" size="sm" placeholder="차량번호 또는 고객명" full-width />
        <x-input color="secondary" size="md" placeholder="차량번호 또는 고객명" full-width />
        <x-input color="secondary" size="lg" placeholder="차량번호 또는 고객명" full-width />
        <x-input color="secondary" size="xl" placeholder="차량번호 또는 고객명" full-width />

        <x-input mask="010-9999-9999"/>
    </div>
</div>
<script>
    const updateControlHeights = () => {
        document.querySelectorAll('[data-control-measure]').forEach((wrapper) => {
            const control = wrapper.firstElementChild;
            const output = wrapper.querySelector('[data-control-height]');
            if (!control || !output) return;
            output.textContent = `${wrapper.dataset.controlMeasure} · ${Math.round(control.getBoundingClientRect().height)}px`;
        });
    };

    requestAnimationFrame(() => requestAnimationFrame(updateControlHeights));
    document.fonts?.ready.then(updateControlHeights);
    window.addEventListener('resize', updateControlHeights);
</script>
</body>
</html>
