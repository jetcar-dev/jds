import {expect, test} from '@playwright/test'

test('Alert variants and close events work', async ({page}) => {
    await page.goto('/component-test#alert-test')
    const alerts = page.locator('#alert-test [data-ui-component="alert"]')
    await expect(alerts).toHaveCount(6)
    await expect(alerts.nth(1)).toHaveAttribute('data-variant', 'solid')
    await expect(alerts.nth(2)).toHaveAttribute('data-variant', 'bordered')
    await expect(alerts.nth(4)).toHaveAttribute('data-variant', 'faded')

    await page.evaluate(() => {
        window.alertEvents = []
        const alert = document.querySelector('#alert-test [data-closeable="true"]')
        alert.addEventListener('app-ui:alert:close', () => window.alertEvents.push('close'))
        alert.addEventListener('app-ui:alert:visible-change', event => window.alertEvents.push(event.detail.visible))
    })

    const closable = page.locator('#alert-test [data-closeable="true"]')
    await closable.getByRole('button', {name: '닫기'}).click()
    await expect(closable).toHaveCount(0)
    await expect.poll(() => page.evaluate(() => window.alertEvents)).toEqual([false, 'close'])
})
