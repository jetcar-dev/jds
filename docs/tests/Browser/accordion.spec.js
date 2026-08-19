import {expect, test} from '@playwright/test'

test('Accordion variants, selection and keyboard behavior work', async ({page}) => {
    await page.goto('/component-test#accordion-test')

    const variants = page.locator('#accordion-test [data-accordion-variants] > [data-ui-component="accordion"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(0)).toHaveAttribute('data-variant', 'light')
    await expect(variants.nth(3)).toHaveAttribute('data-variant', 'splitted')

    const light = variants.first()
    const lightItems = light.locator(':scope > [data-ui-part="accordion-item"]')
    await expect(lightItems.first()).toHaveAttribute('data-open', 'true')
    await expect(lightItems.nth(1)).toHaveAttribute('data-open', 'false')
    await lightItems.nth(1).getByRole('button').click()
    await expect(lightItems.first()).toHaveAttribute('data-open', 'false')
    await expect(lightItems.nth(1)).toHaveAttribute('data-open', 'true')

    const multiple = page.locator('#accordion-test [data-multiple]')
    const multipleItems = multiple.locator(':scope > [data-ui-part="accordion-item"]')
    await multipleItems.nth(1).getByRole('button').click()
    await expect(multipleItems.nth(0)).toHaveAttribute('data-open', 'true')
    await expect(multipleItems.nth(1)).toHaveAttribute('data-open', 'true')
    await expect(multipleItems.nth(2)).toHaveAttribute('data-disabled', 'true')
    await expect(multipleItems.nth(2).getByRole('button')).toBeDisabled()

    await multipleItems.nth(0).getByRole('button').focus()
    await page.keyboard.press('End')
    await expect(multipleItems.nth(1).getByRole('button')).toBeFocused()

    await page.evaluate(() => {
        const accordion = document.querySelector('#accordion-test [data-multiple]')
        AppUI.get(accordion).setValue(['two'])
        AppUI.get(accordion).open('one')
    })
    await expect(multipleItems.nth(0)).toHaveAttribute('data-open', 'true')
    await expect(multipleItems.nth(1)).toHaveAttribute('data-open', 'true')
})

test('Accordion content animates without layout jumps', async ({page}) => {
    await page.goto('/component-test#accordion-test')

    const accordion = page.locator('#accordion-test [data-accordion-variants] > [data-ui-component="accordion"]').first()
    const second = accordion.locator(':scope > [data-ui-part="accordion-item"]').nth(1)
    const panel = second.locator('[data-slot="content-motion"]')

    await second.getByRole('button').click()
    await expect(panel).not.toHaveAttribute('hidden', '')
    await expect.poll(() => panel.evaluate(element => element.getBoundingClientRect().height)).toBeGreaterThan(0)
    await expect(second).toHaveAttribute('data-open', 'true')

    await second.getByRole('button').click()
    await expect(second).toHaveAttribute('data-open', 'false')
    await expect(panel).toBeHidden()
})
