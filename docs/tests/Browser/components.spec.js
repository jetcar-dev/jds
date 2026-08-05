import {expect, test} from '@playwright/test'

test('50개 카탈로그와 light/dark 화면이 안정적으로 렌더링된다', async ({page}, testInfo) => {
    await page.goto('/installation')
    await expect(page.locator('.jds-docs-sidebar-links a[href*="/components/"]')).toHaveCount(50)

    await page.goto('/component-test')
    await expect(page.getByRole('heading', {name: 'JDS v2 컴포넌트 테스트'})).toBeVisible()
    if (testInfo.project.name === 'chrome') {
        await expect(page.locator('main')).toHaveScreenshot('component-test-light.png', {maxDiffPixelRatio: 0.03})
        await page.evaluate(() => document.documentElement.dataset.theme = 'dark')
        await expect(page.locator('main')).toHaveScreenshot('component-test-dark.png', {maxDiffPixelRatio: 0.03})
    }
})

test('폼 상태와 키보드 selection이 native 값에 반영된다', async ({page}) => {
    await page.goto('/component-test')
    await page.locator('[data-slot="checkbox"]', {hasText: '이메일'}).click()
    await page.locator('[data-slot="switch"]', {hasText: '알림 받기'}).click()
    await page.getByRole('radio').first().focus()
    await page.getByRole('radio').first().press('ArrowRight')
    await expect(page.locator('input[name="channels[]"][value="email"]')).toBeChecked()
    await expect(page.locator('[data-slot="switch"] input')).toBeChecked()
    await expect(page.locator('[data-radio-input]')).toHaveValue('pro')

    const second = page.getByRole('tab', {name: '두 번째'})
    await second.click()
    await second.press('ArrowLeft')
    await expect(page.getByRole('tab', {name: '첫 번째'})).toHaveAttribute('aria-selected', 'true')
    await expect(page.getByRole('tabpanel')).toHaveText('첫 번째 패널')
})

test('Modal 안 Select가 열리고 닫힌 뒤 트리거로 focus가 복원된다', async ({page}) => {
    await page.goto('/component-test')
    const trigger = page.getByRole('button', {name: '모달 열기'})
    await trigger.click()
    const dialog = page.getByRole('dialog')
    await dialog.getByRole('button', {name: '선택하세요'}).click()
    await page.getByRole('option', {name: '이담당'}).click()
    await expect(dialog.getByRole('button', {name: '이담당'})).toHaveAttribute('aria-expanded', 'false')
    await dialog.getByRole('button', {name: '닫기'}).click()
    await expect(dialog).toBeHidden()
    await expect(trigger).toBeFocused()
})

test('날짜 범위는 시작일에서 닫히지 않고 종료일에서 제출값을 완성한다', async ({page}) => {
    await page.goto('/component-test')
    await page.getByRole('button', {name: '2026-08-05 ~ 2026-08-12'}).click()
    await page.getByRole('button', {name: /2026년 8월 20일/}).click()
    await expect(page.locator('[data-slot="date-range-picker"]')).toHaveAttribute('data-open', 'true')
    await page.getByRole('button', {name: /2026년 8월 22일/}).click()
    await expect(page.locator('input[name="period[start]"]')).toHaveValue('2026-08-20')
    await expect(page.locator('input[name="period[end]"]')).toHaveValue('2026-08-22')
    await expect(page.locator('[data-slot="date-range-picker"]')).toHaveAttribute('data-open', 'false')
})
