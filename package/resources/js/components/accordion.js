(function (window) {
    'use strict'

    const AppUI = window.AppUI

    const init = function (root) {
        const items = Array.from(root.querySelectorAll(':scope > [data-slot="accordion-item"]'))
        const values = function () { return root.dataset.value ? root.dataset.value.split('|') : [] }
        const refresh = function (nextValues) {
            root.dataset.value = nextValues.join('|')
            items.forEach(function (item, index) {
                const open = nextValues.includes(item.dataset.value)
                const trigger = item.querySelector('[data-slot="accordion-trigger"]')
                const content = item.querySelector('[data-slot="accordion-content"]')
                const triggerId = root.id + '-trigger-' + index
                const contentId = root.id + '-content-' + index
                item.dataset.state = open ? 'open' : 'closed'
                trigger.id = trigger.id || triggerId
                content.id = content.id || contentId
                trigger.setAttribute('aria-expanded', String(open))
                trigger.setAttribute('aria-controls', content.id)
                content.setAttribute('aria-labelledby', trigger.id)
                content.dataset.state = open ? 'open' : 'closed'
                content.hidden = !open
            })
        }

        // 닫힌 패널도 자연 폭을 한 번 측정해, 본문을 열 때만 가로폭이 커지지 않게 한다.
        const reserveContentWidth = function () {
            const available = root.parentElement?.clientWidth || window.innerWidth
            let widest = root.getBoundingClientRect().width

            items.forEach(function (item) {
                const content = item.querySelector('[data-slot="accordion-content"]')
                if (!content) return
                const previous = {
                    display: content.style.display,
                    visibility: content.style.visibility,
                    position: content.style.position,
                    width: content.style.width,
                    hidden: content.hidden
                }
                content.hidden = false
                content.style.display = 'block'
                content.style.visibility = 'hidden'
                content.style.position = 'fixed'
                content.style.width = 'max-content'
                widest = Math.max(widest, content.scrollWidth)
                content.hidden = previous.hidden
                content.style.display = previous.display
                content.style.visibility = previous.visibility
                content.style.position = previous.position
                content.style.width = previous.width
            })

            root.style.minWidth = Math.min(widest, available) + 'px'
        }

        root.id = root.id || 'app-accordion-' + Math.random().toString(36).slice(2, 9)
        refresh(values())
        reserveContentWidth()
        root.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-slot="accordion-trigger"]')
            if (!trigger || trigger.disabled) return
            const item = trigger.closest('[data-slot="accordion-item"]')
            const current = values()
            const next = root.dataset.type === 'multiple'
                ? (current.includes(item.dataset.value) ? current.filter(function (value) { return value !== item.dataset.value }) : current.concat(item.dataset.value))
                : (current.includes(item.dataset.value) && root.dataset.collapsible === 'true' ? [] : [item.dataset.value])
            refresh(next)
            root.dispatchEvent(new CustomEvent('accordion:change', {bubbles: true, detail: {value: root.dataset.type === 'multiple' ? next : (next[0] || null)}}))
        })
        root.addEventListener('keydown', function (event) {
            const triggers = items.map(function (item) { return item.querySelector('[data-slot="accordion-trigger"]') }).filter(function (trigger) { return !trigger.disabled })
            const index = triggers.indexOf(event.target)
            let target = index
            if (event.key === 'ArrowDown') target = Math.min(index + 1, triggers.length - 1)
            else if (event.key === 'ArrowUp') target = Math.max(index - 1, 0)
            else if (event.key === 'Home') target = 0
            else if (event.key === 'End') target = triggers.length - 1
            else return
            event.preventDefault(); triggers[target]?.focus()
        })
    }

    AppUI.register('accordion', '[data-slot="accordion"]', init)
})(window)
