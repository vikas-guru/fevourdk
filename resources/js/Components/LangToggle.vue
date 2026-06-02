<script setup>
import { lang, setLang } from '@/i18n'
</script>

<template>
    <div class="fk-lang" role="group" aria-label="Language / ಭಾಷೆ">
        <button
            type="button"
            class="fk-lang__opt"
            :class="{ 'is-active': lang === 'en' }"
            :aria-pressed="lang === 'en'"
            @click="setLang('en')"
        >EN</button>
        <button
            type="button"
            class="fk-lang__opt fk-lang__opt--kn"
            :class="{ 'is-active': lang === 'kn' }"
            :aria-pressed="lang === 'kn'"
            @click="setLang('kn')"
        >ಕನ್ನಡ</button>
    </div>
</template>

<style>
.fk-lang {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    padding: 3px;
    border-radius: 999px;
    background: rgba(13, 31, 92, 0.06);
    border: 1px solid rgba(13, 31, 92, 0.12);
    line-height: 1;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    flex: 0 0 auto;
}
.fk-lang__opt {
    appearance: none;
    border: 0;
    background: transparent;
    cursor: pointer;
    font: 700 12.5px/1 'Inter', system-ui, sans-serif;
    color: #475569;
    padding: 6px 11px;
    border-radius: 999px;
    transition: color .18s ease, background .18s ease, box-shadow .18s ease;
    white-space: nowrap;
}
.fk-lang__opt--kn { font-family: 'Noto Sans Kannada', 'Inter', sans-serif; font-weight: 600; }
.fk-lang__opt:hover { color: #0d1f5c; }
.fk-lang__opt.is-active {
    color: #1a1300;
    background: linear-gradient(135deg, #ffd24a, #f2b40c);
    box-shadow: 0 4px 12px -3px rgba(242, 180, 12, .55), inset 0 0 0 1px rgba(255,255,255,.35);
}
.fk-lang__opt:focus-visible { outline: 2px solid #f2b40c; outline-offset: 2px; }

/* On dark/blue nav: light translucent track + light inactive text (gold active unchanged) */
.fk-lang--on-dark { background: rgba(255, 255, 255, 0.14); border-color: rgba(255, 255, 255, 0.28); }
.fk-lang--on-dark .fk-lang__opt { color: rgba(255, 255, 255, 0.85); }
.fk-lang--on-dark .fk-lang__opt:hover { color: #fff; }

@media (max-width: 480px) {
    .fk-lang__opt { padding: 5px 9px; font-size: 12px; }
}
</style>
