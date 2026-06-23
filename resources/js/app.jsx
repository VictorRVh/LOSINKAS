import { createRoot } from 'react-dom/client'
import '../css/app.css'

import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import { route } from 'ziggy-js'
import { Ziggy } from './ziggy'

window.Ziggy = Ziggy
window.route = route

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx')
        ),

    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})