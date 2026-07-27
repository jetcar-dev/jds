(function (window) {
    'use strict'

    window.AppUI.register('radio-group', '[data-slot="radio-group"]', function (group) {
        const hiddenInput = group.querySelector('[data-radio-group-input]')
        let value = hiddenInput ? hiddenInput.value : group.dataset.value
        let hasValue = group.dataset.hasValue === 'true'
        let rovingValue = hasValue ? value : null

        const getItems = function () {
            return Array.from(group.querySelectorAll('[data-slot="radio-group-item"]'))
        }

        // 선택 상태와 키보드 진입 위치를 그룹 안의 모든 항목에 반영
        const render = function () {
            const items = getItems()
            const enabledItems = items.filter(function (item) {
                return !item.disabled
            })

            if (!enabledItems.some(function (item) {
                return item.dataset.value === rovingValue
            })) {
                rovingValue = enabledItems.length ? enabledItems[0].dataset.value : null
            }

            items.forEach(function (item) {
                const checked = hasValue && item.dataset.value === value
                const indicator = item.querySelector('[data-slot="radio-group-indicator"]')

                item.dataset.state = checked ? 'checked' : 'unchecked'
                item.setAttribute('aria-checked', checked ? 'true' : 'false')
                item.tabIndex = !item.disabled && item.dataset.value === rovingValue ? 0 : -1

                if (indicator) {
                    indicator.hidden = !checked
                }
            })

            group.dataset.value = hasValue ? value : ''
            group.dataset.hasValue = hasValue ? 'true' : 'false'

            if (hiddenInput) {
                hiddenInput.value = hasValue ? value : ''
            }
        }

        // 사용자 선택은 hidden input의 표준 input과 change 이벤트로 외부에 전달
        const selectValue = function (nextValue, notify) {
            const changed = !hasValue || value !== nextValue

            value = nextValue
            hasValue = true
            rovingValue = nextValue
            render()

            if (changed && notify && hiddenInput) {
                hiddenInput.dispatchEvent(new Event('input', {bubbles: true}))
                hiddenInput.dispatchEvent(new Event('change', {bubbles: true}))
            }

            if (changed && notify) {
                window.AppUI.emit(group, 'radio-group-change', {value: value})
            }
        }

        group.addEventListener('click', function (event) {
            const item = event.target.closest('[data-slot="radio-group-item"]')

            if (!item || !group.contains(item) || item.disabled) {
                return
            }

            selectValue(item.dataset.value, true)
            item.focus()
        })

        group.addEventListener('focusin', function (event) {
            const item = event.target.closest('[data-slot="radio-group-item"]')

            if (item && !item.disabled) {
                rovingValue = item.dataset.value
                render()
            }
        })

        group.addEventListener('keydown', function (event) {
            const item = event.target.closest('[data-slot="radio-group-item"]')
            const keys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Home', 'End']

            if (!item || !keys.includes(event.key)) {
                return
            }

            const items = getItems().filter(function (radioItem) {
                return !radioItem.disabled
            })

            if (item.disabled || !items.length) {
                return
            }

            const currentIndex = items.indexOf(item)
            let nextIndex = currentIndex

            if (event.key === 'Home') {
                nextIndex = 0
            } else if (event.key === 'End') {
                nextIndex = items.length - 1
            } else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
                nextIndex = (currentIndex - 1 + items.length) % items.length
            } else {
                nextIndex = (currentIndex + 1) % items.length
            }

            if (nextIndex < 0) {
                return
            }

            event.preventDefault()
            selectValue(items[nextIndex].dataset.value, true)
            items[nextIndex].focus()
        })

        // 외부 코드가 hidden input 값을 바꾼 경우 버튼 표시도 다시 맞춤
        if (hiddenInput) {
            hiddenInput.addEventListener('change', function () {
                value = hiddenInput.value
                hasValue = true
                rovingValue = value
                render()
            })
        }

        group.addEventListener('app-ui:radio-group-refresh', render)
        render()
    })

    // 동적으로 추가된 항목도 가장 가까운 그룹의 선택 상태를 다시 계산
    window.AppUI.register('radio-group-item', '[data-slot="radio-group-item"]', function (item) {
        item.dispatchEvent(new CustomEvent('app-ui:radio-group-refresh', {bubbles: true}))
    })
})(window)
