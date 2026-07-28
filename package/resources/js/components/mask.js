(function (window) {
    'use strict'

    window.AppUI.register('mask', '[data-slot="input"][data-mask]', function (input) {
        // 9는 숫자, a는 문자, A는 대문자, *는 영문과 숫자를 받고 나머지는 구분자로 사용
        const applyMask = function () {
            const mask = input.dataset.mask

            if (!mask) return

            const value = input.value
            let result = ''
            let valueIndex = 0

            for (let maskIndex = 0; maskIndex < mask.length; maskIndex++) {
                if (valueIndex >= value.length) break

                const maskCharacter = mask[maskIndex]
                const isRule = maskCharacter === '9' || maskCharacter === 'a' || maskCharacter === 'A' || maskCharacter === '*'

                if (!isRule) {
                    result += maskCharacter
                    if (value[valueIndex] === maskCharacter) valueIndex++
                    continue
                }

                while (valueIndex < value.length) {
                    const character = value[valueIndex++]
                    const accepted = maskCharacter === '9'
                        ? /[0-9]/.test(character)
                        : maskCharacter === 'a' || maskCharacter === 'A'
                            ? /[a-z]/i.test(character)
                            : /[a-z0-9]/i.test(character)

                    if (accepted) {
                        result += maskCharacter === 'A' ? character.toUpperCase() : character
                        break
                    }
                }
            }

            input.value = result
        }

        applyMask()
        input.addEventListener('input', applyMask)
    })
})(window)
