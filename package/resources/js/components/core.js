(function (window, document) {
    'use strict'

    const components = new Map()
    const pendingRoots = new Set()
    let observer = null
    let flushScheduled = false
    let started = false

    // 지정한 범위 안에서 컴포넌트 루트 자신과 하위 요소를 함께 찾음
    const findElements = function (root, selector) {
        const elements = []

        if (root instanceof Element && root.matches(selector)) {
            elements.push(root)
        }

        if (root.querySelectorAll) {
            elements.push(...root.querySelectorAll(selector))
        }

        return elements
    }

    // 같은 요소를 같은 컴포넌트로 두 번 초기화하지 않음
    const mount = function (component, root) {
        findElements(root, component.selector).forEach(function (element) {
            if (component.mounted.has(element)) {
                return
            }

            component.init(element)
            component.mounted.add(element)
            element.dataset.uiReady = 'true'
            element.dispatchEvent(new CustomEvent('app-ui:mounted', {
                bubbles: true,
                detail: {name: component.name}
            }))
        })
    }

    // 여러 DOM 변경을 한 번에 모아 불필요한 반복 탐색을 줄임
    const flush = function () {
        flushScheduled = false
        const queuedRoots = Array.from(pendingRoots)
        pendingRoots.clear()

        // 부모와 자식이 함께 추가된 경우 부모만 탐색해 중복 초기화를 피함
        const roots = queuedRoots.filter(function (root, index) {
            return !queuedRoots.some(function (candidate, candidateIndex) {
                return candidateIndex !== index && candidate.contains(root)
            })
        })

        roots.forEach(function (root) {
            components.forEach(function (component) {
                mount(component, root)
            })
        })
    }

    const schedule = function (root) {
        if (!root || root.nodeType !== Node.ELEMENT_NODE) {
            return
        }

        pendingRoots.add(root)
        if (!flushScheduled) {
            flushScheduled = true
            queueMicrotask(flush)
        }
    }

    // 최초 화면과 이후 추가되는 HTML을 같은 방식으로 초기화
    const start = function () {
        if (started) {
            return
        }

        started = true
        components.forEach(function (component) {
            mount(component, document)
        })

        observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(schedule)
            })
        })
        observer.observe(document.body, {childList: true, subtree: true})
    }

    const AppUI = {
        /*
         * 컴포넌트 등록
         *
         * name     중복되지 않는 컴포넌트 이름
         * selector 초기화할 루트 요소 선택자
         * init     루트 요소 한 개를 초기화하는 함수
         */
        register: function (name, selector, init) {
            if (!name || !selector || typeof init !== 'function') {
                throw new TypeError('AppUI.register(name, selector, init) 형식으로 등록해 주세요')
            }

            const component = {
                name: name,
                selector: selector,
                init: init,
                mounted: new WeakSet()
            }

            components.set(name, component)
            if (started) {
                mount(component, document)
            }
        },

        // 전체 또는 특정 컴포넌트를 원하는 범위에서 직접 초기화
        init: function (root, name) {
            const target = root || document

            if (name) {
                const component = components.get(name)
                if (component) {
                    mount(component, target)
                }
                return
            }

            components.forEach(function (component) {
                mount(component, target)
            })
        },

        // 컴포넌트에서 일관된 이름으로 사용자 이벤트를 전달
        emit: function (element, name, detail) {
            element.dispatchEvent(new CustomEvent('app-ui:' + name, {
                bubbles: true,
                detail: detail || {}
            }))
        },

        // 테스트와 디버깅에서 현재 등록된 컴포넌트를 확인
        registered: function () {
            return Array.from(components.keys())
        }
    }

    window.AppUI = AppUI

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start, {once: true})
    } else {
        start()
    }
})(window, document)
