(function (window) {
    'use strict'

    window.AppUI.register('checkbox', '[data-slot="checkbox"][role="checkbox"]', function (checkbox) {
        const indicator = checkbox.querySelector('[data-slot="checkbox-indicator"]')
        const minusIcon = checkbox.querySelector('[data-checkbox-minus]')
        const checkIcon = checkbox.querySelector('[data-checkbox-check]')
        const hiddenInput = checkbox.querySelector('[data-checkbox-input]')
        let checked = checkbox.dataset.checked === 'true'
        let indeterminate = checkbox.dataset.indeterminate === 'true'

        // 버튼 상태와 폼 전송용 hidden input을 같은 값으로 유지
        const render = function () {
            const state = indeterminate ? 'indeterminate' : (checked ? 'checked' : 'unchecked')

            checkbox.dataset.state = state
            checkbox.dataset.checked = checked ? 'true' : 'false'
            checkbox.dataset.indeterminate = indeterminate ? 'true' : 'false'
            checkbox.setAttribute('aria-checked', indeterminate ? 'mixed' : String(checked))

            if (indicator) {
                indicator.hidden = false
            }
            if (minusIcon) {
                minusIcon.hidden = !indeterminate
            }
            if (checkIcon) {
                checkIcon.hidden = indeterminate
            }
            if (hiddenInput) {
                if (checked && !indeterminate) {
                    hiddenInput.name = hiddenInput.dataset.name
                } else {
                    hiddenInput.removeAttribute('name')
                }
            }
        }

        checkbox.addEventListener('click', function () {
            if (indeterminate) {
                indeterminate = false
                checked = true
            } else {
                checked = !checked
            }

            render()
            window.AppUI.emit(checkbox, 'checkbox-change', {
                checked: checked,
                indeterminate: indeterminate
            })
        })

        render()
    })
})(window)
