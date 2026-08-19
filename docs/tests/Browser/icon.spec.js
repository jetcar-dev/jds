import {expect, test} from '@playwright/test'
import icons from '../../../package/resources/js/icons/iconify-bundle.js'

test('the local Iconify allowlist renders every bundled icon', async ({page}) => {
    const runtimeErrors = []
    page.on('console', message => {
        if (message.type() === 'error') runtimeErrors.push(message.text())
    })
    page.on('pageerror', error => runtimeErrors.push(error.message))

    const names = Object.keys(icons)

    expect(names).toHaveLength(553)
    expect(names).not.toContain('solar:map-arrow-up-linea')
    expect(names).not.toContain('solar:map-arrow-right-linearr')
    expect(names).toContain('solar:map-arrow-up-linear')
    expect(names).toContain('solar:map-arrow-right-linear')

    await page.goto('/icons')

    const rendered = page.locator('[data-icon-card] > [data-icon]')
    await expect(rendered).toHaveCount(553)
    await page.waitForTimeout(2_000)
    expect(runtimeErrors).toEqual([])
    expect(await page.locator('[data-icon-card] > [data-icon-mounted="true"] > svg').count()).toBe(553)

    const invalidViewBoxes = await rendered.locator('svg').evaluateAll(elements =>
        elements.filter(svg => !/^0 0 \d+(?:\.\d+)? \d+(?:\.\d+)?$/.test(svg.getAttribute('viewBox') || '')).length,
    )

    expect(invalidViewBoxes).toBe(0)

    const search = page.locator('[data-icon-search]')
    await search.fill('copy-linear')
    await expect(page.locator('[data-icon-card]:visible')).toHaveCount(1)
    await expect(page.locator('[data-icon-count]')).toHaveText('1개')
    await expect(page.locator('[data-icon-card]:visible code')).toHaveText('solar:copy-linear')
})
