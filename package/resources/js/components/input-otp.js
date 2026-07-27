(function (window) {
    'use strict'

    window.AppUI.register('input-otp', '[data-slot="input-otp"]', function (root) {
        const input = root.querySelector('.app-input-otp-control')
        const slots = Array.from(root.querySelectorAll('[data-slot="input-otp-slot"]'))
        const maxLength = Number(root.dataset.maxlength || 6)
        const alphanumeric = root.dataset.alphanumeric === 'true'
        let focused = false

        if (!input) {
            return
        }

        // 실제 input 값과 화면에 보이는 각 OTP 칸을 동기화
        const render = function () {
            const value = input.value.slice(0, maxLength)
            const activeIndex = focused ? Math.min(value.length, maxLength - 1) : -1

            slots.forEach(function (slot) {
                const index = Number(slot.dataset.index || 0)
                const valueElement = slot.querySelector('[data-slot="input-otp-value"]')
                const caret = slot.querySelector('[data-slot="input-otp-caret"]')

                slot.dataset.active = index === activeIndex ? 'true' : 'false'
                if (valueElement) {
                    valueElement.textContent = value[index] || ''
                }
                if (caret) {
                    caret.hidden = !(index === activeIndex && value.length === index)
                }
            })
        }

        input.addEventListener('input', function () {
            const pattern = alphanumeric ? /[^a-z0-9]/gi : /[^0-9]/g
            input.value = input.value.replace(pattern, '').slice(0, maxLength)
            render()
        })

        input.addEventListener('focus', function () {
            focused = true
            render()
        })

        input.addEventListener('blur', function () {
            focused = false
            render()
        })

        render()
    })
})(window)
