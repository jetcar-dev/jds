(function (window) {
    'use strict'

    window.AppUI.register('switch', '[data-slot="switch"]', function (switchButton) {
        const thumb = switchButton.querySelector('[data-slot="switch-thumb"]')
        const hiddenInput = switchButton.querySelector('[data-switch-input]')
        let checked = switchButton.dataset.checked === 'true'

        // 버튼 표시와 폼 전송용 hidden input을 같은 상태로 유지
        const render = function () {
            const state = checked ? 'checked' : 'unchecked'

            switchButton.dataset.state = state
            switchButton.dataset.checked = checked ? 'true' : 'false'
            switchButton.setAttribute('aria-checked', checked ? 'true' : 'false')

            if (thumb) {
                thumb.dataset.state = state
            }

            if (hiddenInput) {
                if (checked) {
                    hiddenInput.name = hiddenInput.dataset.name
                } else {
                    hiddenInput.removeAttribute('name')
                }
            }
        }

        // 사용자 조작과 외부 코드의 상태 변경을 같은 이벤트 형식으로 전달
        const setChecked = function (nextChecked, notify) {
            const changed = checked !== nextChecked

            checked = nextChecked
            render()

            if (changed && notify && hiddenInput) {
                hiddenInput.dispatchEvent(new Event('input', {bubbles: true}))
                hiddenInput.dispatchEvent(new Event('change', {bubbles: true}))
            }

            if (changed && notify) {
                window.AppUI.emit(switchButton, 'switch-change', {checked: checked})
            }
        }

        switchButton.addEventListener('click', function () {
            setChecked(!checked, true)
        })

        switchButton.addEventListener('app-ui:switch-set', function (event) {
            setChecked(Boolean(event.detail && event.detail.checked), event.detail?.notify !== false)
        })

        render()
    })
})(window)
