import type {BaseLayoutProps} from 'fumadocs-ui/layouts/shared'

export function baseOptions(): BaseLayoutProps {
    return {
        nav: {
            title: (
                <span className="jds-brand">
                    <img src="/jds-logo.svg" alt="" aria-hidden="true" />
                    <span>JDS</span>
                </span>
            ),
        },
        githubUrl: 'https://github.com/jetcar-dev/jds',
    }
}
