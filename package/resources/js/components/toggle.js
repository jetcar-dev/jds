(function (window) {
    'use strict'

    const AppUI = window.AppUI

    const setPressed = function (button, pressed) {
        button.dataset.state = pressed ? 'on' : 'off'
        button.setAttribute('aria-pressed', String(pressed))
    }

    const initToggle = function (button) {
        button.addEventListener('click', function () {
            if (!button.disabled) setPressed(button, button.dataset.state !== 'on')
        })
    }

    const initGroup = function (group) {
        const items = function () {
            return Array.from(group.querySelectorAll('[data-slot="toggle-group-item"]'))
        }
        const values = function () {
            return group.dataset.value ? group.dataset.value.split('|') : []
        }
        const refresh = function (nextValues) {
            group.dataset.value = nextValues.join('|')
            items().forEach(function (item) {
                const pressed = nextValues.includes(item.dataset.value)
                setPressed(item, pressed)
                item.tabIndex = pressed || !items().some(function (button) { return button.tabIndex === 0 }) ? 0 : -1
            })
        }

        refresh(values())
        group.addEventListener('click', function (event) {
            const item = event.target.closest('[data-slot="toggle-group-item"]')
            if (!item || item.disabled) return
            const current = values()
            const next = group.dataset.type === 'multiple'
                ? (current.includes(item.dataset.value) ? current.filter(function (value) { return value !== item.dataset.value }) : current.concat(item.dataset.value))
                : (current.includes(item.dataset.value) ? [] : [item.dataset.value])
            refresh(next)
            group.dispatchEvent(new CustomEvent('toggle-group:change', {bubbles: true, detail: {value: group.dataset.type === 'multiple' ? next : (next[0] || null)}}))
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
    }

    AppUI.register('toggle', '[data-slot="toggle"]:not([data-slot="toggle-group-item"])', initToggle)
    AppUI.register('toggle-group', '[data-slot="toggle-group"]', initGroup)
})(window)
