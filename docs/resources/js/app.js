// JDS 원본을 직접 가져와 문서 화면에서 Vite HMR로 확인
import '../../../package/resources/js/jds.js'

const initializeDocsSidebar = () => {
    const links = document.querySelector('.jds-docs-sidebar-links')

    if (!links) {
        return
    }

    let scrollTimer = null

    links.addEventListener('scroll', () => {
        links.classList.add('is-scrolling')
        window.clearTimeout(scrollTimer)
        scrollTimer = window.setTimeout(() => {
            links.classList.remove('is-scrolling')
        }, 650)
    }, {passive: true})

    const activeLink = links.querySelector('[aria-current="page"]')

    if (!activeLink) {
        return
    }

    window.requestAnimationFrame(() => {
        const linksRect = links.getBoundingClientRect()
        const activeRect = activeLink.getBoundingClientRect()
        const isOutsideView = activeRect.top < linksRect.top || activeRect.bottom > linksRect.bottom

        if (isOutsideView) {
            links.scrollTop = activeLink.offsetTop
                - links.clientHeight / 2
                + activeLink.offsetHeight / 2
        }
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDocsSidebar, {once: true})
} else {
    initializeDocsSidebar()
}
