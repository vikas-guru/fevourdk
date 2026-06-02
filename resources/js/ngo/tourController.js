/**
 * tourController — a tiny dependency-free singleton that bridges the
 * shell-mounted Help bot to the page-mounted DashboardTour across Inertia's
 * slot boundary. The active page registers its start() fn on mount; the bot
 * (or anything else) calls start() to replay the current page's tour.
 */
import { ref } from 'vue'

let startFn = null

// Reactive so the bot can show/hide its "Take a tour" action per page.
export const hasTour = ref(false)

export function register(fn) {
    startFn = fn
    hasTour.value = typeof fn === 'function'
}

export function clear() {
    startFn = null
    hasTour.value = false
}

export function start() {
    if (typeof startFn === 'function') {
        startFn()
        return true
    }
    return false
}
