(function (window) {
    'use strict'

    // 비밀번호 입력의 보기 버튼과 두 아이콘 상태를 함께 변경
    window.AppUI.register(
        'input-password',
        '[data-slot="input-wrapper"][data-password-toggle="true"]',
        function (wrapper) {
            const input = wrapper.querySelector('[data-slot="input"]')
            const button = wrapper.querySelector('[data-slot="input-password-toggle"]')
            const hiddenIcon = button && button.querySelector('[data-password-hidden]')
            const visibleIcon = button && button.querySelector('[data-password-visible]')

            if (!input || !button) {
                return
            }

            button.addEventListener('click', function () {
                const show = input.type === 'password'

                input.type = show ? 'text' : 'password'
                button.setAttribute('aria-label', show ? '비밀번호 숨기기' : '비밀번호 보기')
                button.setAttribute('aria-pressed', show ? 'true' : 'false')

                if (hiddenIcon) {
                    hiddenIcon.hidden = show
                }

                if (visibleIcon) {
                    visibleIcon.hidden = !show
                }
            })
        }
    )
})(window)
