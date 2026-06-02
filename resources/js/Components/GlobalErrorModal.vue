<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()
const open = ref(false)
const title = ref('Something went wrong')
const items = ref([])
const message = ref('')

const haptic = () => { try { navigator.vibrate?.([20, 60, 20]) } catch (e) { /* unsupported */ } }

function show(t, list, msg) {
    title.value = t
    items.value = list || []
    message.value = msg || ''
    open.value = true
    haptic()
}

let offError, offException, offInvalid, offNavigate

onMounted(() => {
    // Validation errors returned from any Inertia form submit (login presents its own UI).
    offError = router.on('error', (e) => {
        if (page.component === 'Auth/Login') return
        const errs = e.detail?.errors || {}
        const list = Object.values(errs).flat().filter(Boolean).map(String)
        if (list.length) show('Please fix these', list, '')
    })
    // Request exceptions (network / unexpected failures).
    offException = router.on('exception', () => {
        show('Something went wrong', [], 'We hit an unexpected error. Please try again in a moment.')
    })
    // Non-Inertia / invalid responses (e.g. a 500 HTML page) — override the default debug modal.
    offInvalid = router.on('invalid', (e) => {
        e.preventDefault?.()
        show('Something went wrong', [], 'The server returned an unexpected response. Please try again.')
    })
    // Dismiss automatically when the user navigates to another page.
    offNavigate = router.on('navigate', () => { open.value = false })
})

onUnmounted(() => {
    offError?.(); offException?.(); offInvalid?.(); offNavigate?.()
})
</script>

<template>
    <Teleport to="body">
        <Transition name="gem">
            <div v-if="open" class="gem" role="alertdialog" aria-modal="true" @click.self="open = false">
                <div class="gem__panel">
                    <div class="gem__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.48 14.7A1.5 1.5 0 003.11 21h17.78a1.5 1.5 0 001.3-2.44l-8.48-14.7a1.5 1.5 0 00-2.6 0z" /></svg>
                    </div>
                    <h3 class="gem__title">{{ title }}</h3>
                    <ul v-if="items.length" class="gem__list">
                        <li v-for="(e, i) in items" :key="i">{{ e }}</li>
                    </ul>
                    <p v-else class="gem__msg">{{ message }}</p>
                    <button type="button" class="gem__btn" @click="open = false">Got it</button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.gem { position: fixed; inset: 0; z-index: 100000; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(8, 16, 40, .55); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
.gem__panel { width: 100%; max-width: 400px; background: #fff; border-radius: 24px; padding: 30px 24px 24px; text-align: center; box-shadow: 0 30px 70px -20px rgba(0, 0, 0, .55); font-family: 'Hanken Grotesk', ui-sans-serif, system-ui, 'Segoe UI', sans-serif; }
.gem__icon { width: 60px; height: 60px; margin: 0 auto 1rem; display: grid; place-items: center; border-radius: 50%; background: linear-gradient(135deg, #fff1f4, #ffe0e8); border: 1px solid #f7c4d2; color: #e0285f; animation: gemShake .5s cubic-bezier(.36, .07, .19, .97); }
.gem__icon svg { width: 32px; height: 32px; }
.gem__title { font-size: 1.35rem; font-weight: 700; color: #0f172a; margin: 0 0 .35rem; letter-spacing: -.01em; }
.gem__msg { color: #5a5e6a; font-size: .95rem; line-height: 1.5; margin: 0 0 1.3rem; }
.gem__list { list-style: none; margin: .5rem 0 1.3rem; padding: 0; text-align: left; display: flex; flex-direction: column; gap: .5rem; max-height: 42vh; overflow-y: auto; }
.gem__list li { display: flex; align-items: flex-start; gap: .55rem; background: #fdf3f6; border: 1px solid #f6dde4; border-radius: 12px; padding: .7rem .85rem; color: #8a1c43; font-size: .9rem; line-height: 1.4; }
.gem__list li::before { content: '!'; flex-shrink: 0; display: grid; place-items: center; width: 18px; height: 18px; margin-top: 1px; border-radius: 50%; background: #e0285f; color: #fff; font-size: .72rem; font-weight: 700; }
.gem__btn { width: 100%; padding: .95em; border: none; border-radius: 999px; background: #0d1f5c; color: #fff; font: inherit; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform .2s ease; }
.gem__btn:hover { transform: translateY(-2px); }
@keyframes gemShake { 10%, 90% { transform: translateX(-1px); } 20%, 80% { transform: translateX(2px); } 30%, 50%, 70% { transform: translateX(-4px); } 40%, 60% { transform: translateX(4px); } }
.gem-enter-active, .gem-leave-active { transition: opacity .25s ease; }
.gem-enter-active .gem__panel, .gem-leave-active .gem__panel { transition: transform .32s cubic-bezier(.2, .9, .3, 1.25), opacity .25s ease; }
.gem-enter-from, .gem-leave-to { opacity: 0; }
.gem-enter-from .gem__panel, .gem-leave-to .gem__panel { transform: scale(.9) translateY(12px); opacity: .5; }
@media (prefers-reduced-motion: reduce) { .gem__icon { animation: none !important; } }
</style>
