(function (window) {
    'use strict'
    const AppUI = window.AppUI

    AppUI.collection = (root, options = {}) => {
        const selector = options.selector || '[role="option"]'
        const selectionMode = options.selectionMode || root.dataset.selectionMode || 'single'
        let typeahead = ''
        let timer = null
        const allItems = () => [...root.querySelectorAll(selector)]
        const items = () => allItems().filter(item => item.dataset.disabled !== 'true' && !item.hidden)
        const selected = () => items().filter(item => item.dataset.selected === 'true')
        const focus = item => { if (!item) return; items().forEach(value => value.tabIndex = value === item ? 0 : -1); item.focus() }
        const select = (item, notify = true) => {
            if (!item || selectionMode === 'none') return
            const next = item.dataset.selected !== 'true'
            if (selectionMode === 'single') items().forEach(value => { value.dataset.selected = value === item ? 'true' : 'false'; value.setAttribute('aria-selected', value === item ? 'true' : 'false') })
            else { item.dataset.selected = next ? 'true' : 'false'; item.setAttribute('aria-selected', next ? 'true' : 'false') }
            if (notify) options.onSelectionChange?.(selected())
        }
        const prepare = () => {
            const current = selected()
            items().forEach((item, index) => {
                item.tabIndex = item.dataset.selected === 'true' || (!current.length && index === 0) ? 0 : -1
                if (root.dataset.virtualize === 'true') {
                    item.style.contentVisibility = 'auto'
                    item.style.containIntrinsicSize = '0 2.5rem'
                }
                AppUI.interaction(item)
            })
        }
        root.addEventListener('keydown', event => {
            const enabled = items(); if (!enabled.length) return
            const current = enabled.indexOf(document.activeElement)
            if (event.key === 'ArrowDown' || event.key === 'ArrowRight') { event.preventDefault(); focus(enabled[(current + 1 + enabled.length) % enabled.length]) }
            else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') { event.preventDefault(); focus(enabled[(current - 1 + enabled.length) % enabled.length]) }
            else if (event.key === 'Home') { event.preventDefault(); focus(enabled[0]) }
            else if (event.key === 'End') { event.preventDefault(); focus(enabled[enabled.length - 1]) }
            else if (event.key === 'Enter' || event.key === ' ') { const item = document.activeElement.closest(selector); if (item) { event.preventDefault(); select(item) } }
            else if (event.key.length === 1 && !event.metaKey && !event.ctrlKey && !event.altKey) {
                clearTimeout(timer); typeahead += event.key.toLocaleLowerCase(); timer = setTimeout(() => typeahead = '', 500)
                const match = enabled.find(item => (item.dataset.textValue || item.textContent).trim().toLocaleLowerCase().startsWith(typeahead)); if (match) focus(match)
            }
        })
        root.addEventListener('click', event => { const item = event.target.closest(selector); if (item && root.contains(item)) { focus(item); select(item) } })
        prepare()
        const observer = new MutationObserver(prepare)
        observer.observe(root, {childList: true, subtree: true})
        return {allItems, items, selected, select, focus, getValue: () => selected().map(item => item.dataset.value), setValue: (values, notify = false) => { const set = new Set(Array.isArray(values) ? values.map(String) : [String(values)]); allItems().forEach(item => { item.dataset.selected = set.has(String(item.dataset.value)) ? 'true' : 'false'; item.setAttribute('aria-selected', item.dataset.selected) }); prepare(); if (notify) options.onSelectionChange?.(selected()) }, destroy: () => observer.disconnect()}
    }
})(window)
