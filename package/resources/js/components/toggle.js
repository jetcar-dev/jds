(function (window) {
    'use strict'

    const AppUI = window.AppUI

    const setPressed = function (button, pressed) {
        button.dataset.state = pressed ? 'on' : 'off'
        button.setAttribute('aria-pressed', String(pressed))
    }

    const initToggle = function (button) {
        if (button.closest('[data-selection]')) return
        button.addEventListener('click', function () {
            if (!button.disabled) setPressed(button, button.dataset.state !== 'on')
        })
    }

    const initGroup = function (group) {
        const items = function () {
            return Array.from(group.querySelectorAll('[data-slot="toggle"][data-value]'))
        }
        const values = function () {
            return group.dataset.value ? group.dataset.value.split('|') : []
        }
        const refresh = function (nextValues) {
            group.dataset.value = nextValues.join('|')
            const hidden = group.querySelector(':scope > [data-group-value]')
            if (hidden) hidden.value = (group.dataset.selection || group.dataset.type) === 'multiple' ? JSON.stringify(nextValues) : (nextValues[0] || '')
            const currentItems = items()
            const activeItem = currentItems.includes(document.activeElement) && !document.activeElement.disabled
                ? document.activeElement
                : null
            const focusable = activeItem || currentItems.find(function (item) {
                return !item.disabled && nextValues.includes(item.dataset.value)
            }) || currentItems.find(function (item) {
                return !item.disabled
            })
            currentItems.forEach(function (item) {
                const pressed = nextValues.includes(item.dataset.value)
                setPressed(item, pressed)
                item.tabIndex = item === focusable ? 0 : -1
            })
        }

        refresh(values())
        group.addEventListener('click', function (event) {
            const item = event.target.closest('[data-slot="toggle"][data-value]')
            if (!item || item.disabled) return
            const current = values()
            const multiple = (group.dataset.selection || group.dataset.type) === 'multiple'
            const next = multiple
                ? (current.includes(item.dataset.value) ? current.filter(function (value) { return value !== item.dataset.value }) : current.concat(item.dataset.value))
                : (current.includes(item.dataset.value) ? [] : [item.dataset.value])
            refresh(next)
            group.dispatchEvent(new CustomEvent('group:change', {bubbles: true, detail: {value: multiple ? next : (next[0] || null)}}))
        })
        group.addEventListener('keydown', function (event) {
            const currentItems = items().filter(function (item) { return !item.disabled })
            const currentIndex = currentItems.indexOf(event.target)
            const vertical = group.dataset.orientation === 'vertical'
            const previous = vertical ? 'ArrowUp' : 'ArrowLeft'
            const next = vertical ? 'ArrowDown' : 'ArrowRight'
            let targetIndex = currentIndex
            if (event.key === previous) targetIndex = (currentIndex - 1 + currentItems.length) % currentItems.length
            else if (event.key === next) targetIndex = (currentIndex + 1) % currentItems.length
            else if (event.key === 'Home') targetIndex = 0
            else if (event.key === 'End') targetIndex = currentItems.length - 1
            else return
            event.preventDefault()
            currentItems[targetIndex]?.focus()
        })
        group.addEventListener('focusin', function (event) {
            const item = event.target.closest('[data-slot="toggle"][data-value]')
            if (!item || item.disabled) return
            items().forEach(function (button) {
                button.tabIndex = button === item ? 0 : -1
            })
        })
    }

    AppUI.register('toggle', '[data-slot="toggle"]', initToggle)
    AppUI.register('selection-group', '[data-slot="group"][data-selection]', initGroup)
})(window)
