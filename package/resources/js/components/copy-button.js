(function (window, document) {
    'use strict'

    const AppUI = window.AppUI

    const fallbackCopy = value => {
        const field = document.createElement('textarea')
        field.value = value
        field.style.position = 'fixed'
        field.style.opacity = '0'
        document.body.appendChild(field)
        field.select()
        document.execCommand('copy')
        field.remove()
    }

    AppUI.register('copy-button', '[data-slot="copy-button"]', button => {
        let timer
        button.addEventListener('click', async () => {
            const value = button.dataset.copyValue || ''
            try {
                if (navigator.clipboard?.writeText) await navigator.clipboard.writeText(value)
                else fallbackCopy(value)
            } catch {
                fallbackCopy(value)
            }

            button.dataset.copied = 'true'
            button.setAttribute('aria-label', 'Copied')
            button.querySelector('[data-copy-status]').textContent = 'Copied'
            clearTimeout(timer)
            timer = setTimeout(() => {
                button.dataset.copied = 'false'
                button.setAttribute('aria-label', button.dataset.copyLabel || 'Copy')
                button.querySelector('[data-copy-status]').textContent = ''
            }, 1500)
        })
    })
})(window, document)
