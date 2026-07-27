(function (window) {
    'use strict'

    const AppUI = window.AppUI
    let nextTabsId = 1

    const buttons = function (tabs) {
        return Array.from(tabs.querySelectorAll('[data-slot="tabs-trigger"], [data-tab-value]')).filter(function (button) {
            return button.closest('[data-slot="tabs-list"]')?.closest('[data-slot="tabs"], [data-ui="tabs"]') === tabs
        })
    }

    const panels = function (tabs) {
        return Array.from(tabs.querySelectorAll('[data-slot="tabs-content"], [data-tab-panel-value]')).filter(function (panel) {
            return panel.closest('[data-slot="tabs"], [data-ui="tabs"]') === tabs
        })
    }

    // 활성 탭과 패널을 맞추고 키보드 이동에 필요한 ARIA 연결을 구성
    const refresh = function (tabs, preferredValue) {
        if (!tabs) {
            return
        }

        const tabButtons = buttons(tabs)
        const tabPanels = panels(tabs)
        const enabledButtons = tabButtons.filter(function (button) {
            return !button.disabled
        })

        if (!enabledButtons.length) {
            tabPanels.forEach(function (panel) {
                panel.hidden = true
                panel.dataset.state = 'inactive'
            })
            return
        }

        tabButtons.forEach(function (button, index) {
            button.id = button.id || tabs.id + '-tab-' + index
            const panel = tabPanels.find(function (item) {
                return item.dataset.tabPanelValue === button.dataset.tabValue
            })

            if (panel) {
                panel.id = panel.id || tabs.id + '-panel-' + index
                button.setAttribute('aria-controls', panel.id)
                panel.setAttribute('aria-labelledby', button.id)
            }
        })

        let activeButton = enabledButtons.find(function (button) {
            return button.dataset.tabValue === preferredValue
        })
        activeButton = activeButton || enabledButtons.find(function (button) {
            return button.dataset.tabValue === tabs.dataset.activeValue
        }) || enabledButtons[0]

        tabButtons.forEach(function (button) {
            const isActive = button === activeButton
            button.setAttribute('aria-selected', String(isActive))
            button.dataset.state = isActive ? 'active' : 'inactive'
            button.tabIndex = isActive ? 0 : -1
        })

        tabPanels.forEach(function (panel) {
            const isActive = panel.dataset.tabPanelValue === activeButton.dataset.tabValue
            panel.hidden = !isActive
            panel.dataset.state = isActive ? 'active' : 'inactive'
        })

        tabs.dataset.activeValue = activeButton.dataset.tabValue
        tabs.dataset.state = 'ready'
    }

    const activate = function (tabs, value, moveFocus) {
        refresh(tabs, value)
        const activeButton = buttons(tabs).find(function (button) {
            return button.getAttribute('aria-selected') === 'true'
        })
        const detail = {value: tabs.dataset.activeValue}

        tabs.dispatchEvent(new CustomEvent('tabs:change', {
            bubbles: true,
            detail: detail
        }))
        AppUI.emit(tabs, 'tabs-change', detail)

        if (moveFocus && activeButton) {
            activeButton.focus()
        }
    }

    // AppUI 코어가 탭 루트마다 한 번만 호출
    const init = function (tabs) {
        if (!tabs.id) {
            while (document.getElementById('app-tabs-' + nextTabsId)) {
                nextTabsId++
            }
            tabs.id = 'app-tabs-' + nextTabsId++
        }

        tabs.addEventListener('click', function (event) {
            const button = event.target.closest('[data-tab-value]')

            if (button && button.closest('[data-slot="tabs"], [data-ui="tabs"]') === tabs && !button.disabled) {
                activate(tabs, button.dataset.tabValue, false)
            }
        })

        tabs.addEventListener('keydown', function (event) {
            const button = event.target.closest('[data-tab-value]')

            if (!button || button.closest('[data-slot="tabs"], [data-ui="tabs"]') !== tabs) {
                return
            }

            const enabledButtons = buttons(tabs).filter(function (item) {
                return !item.disabled
            })
            const currentIndex = enabledButtons.indexOf(button)
            const isVertical = tabs.dataset.orientation === 'vertical'
            const previousKey = isVertical ? 'ArrowUp' : 'ArrowLeft'
            const nextKey = isVertical ? 'ArrowDown' : 'ArrowRight'
            let nextIndex = currentIndex

            if (event.key === previousKey) {
                nextIndex = (currentIndex - 1 + enabledButtons.length) % enabledButtons.length
            } else if (event.key === nextKey) {
                nextIndex = (currentIndex + 1) % enabledButtons.length
            } else if (event.key === 'Home') {
                nextIndex = 0
            } else if (event.key === 'End') {
                nextIndex = enabledButtons.length - 1
            } else {
                return
            }

            event.preventDefault()
            activate(tabs, enabledButtons[nextIndex].dataset.tabValue, true)
        })

        refresh(tabs, tabs.dataset.defaultValue)
    }

    AppUI.register('tabs', '[data-slot="tabs"], [data-ui="tabs"]', init)

    // 기존 코드에서도 같은 공개 API를 계속 사용할 수 있게 유지
    window.appTabs = {
        init: function (root) {
            AppUI.init(root || document, 'tabs')
        },
        refresh: refresh,
        activate: activate
    }
})(window)
