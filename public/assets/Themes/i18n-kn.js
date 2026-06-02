/* ============================================================================
 * FEVOURD-K microsite — Kannada (ಕನ್ನಡ) ⇄ English toggle engine
 * ----------------------------------------------------------------------------
 * Pure vanilla JS, no deps. Translation is a client-side DOM swap:
 *   - Every translatable leaf element carries  data-kn="<ಕನ್ನಡ string>".
 *   - English stays the rendered (SSR) default = single source of truth.
 *   - On first run we cache each element's English text, then swap to the
 *     data-kn value when Kannada is active, and restore English otherwise.
 *   - Empty / missing data-kn  => element keeps English (graceful fallback,
 *     so admin-typed prose without a Kannada version never goes blank).
 *
 * Language resolution order: ?lang= query  ->  localStorage / cookie  ->
 * navigator.language auto-detect (kn* => Kannada, else English).
 * Choice persists in localStorage AND a path=/ cookie (survives reload + nav,
 * server-readable later if ever needed).
 * ========================================================================== */
(function () {
    'use strict';

    var KEY = 'ngo_lang';
    var ONE_YEAR = 60 * 60 * 24 * 365;

    function readCookie(name) {
        var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]+)'));
        return m ? decodeURIComponent(m[1]) : null;
    }

    function writeCookie(name, value) {
        document.cookie = name + '=' + encodeURIComponent(value) + ';path=/;max-age=' + ONE_YEAR + ';samesite=lax';
    }

    function valid(lang) {
        return lang === 'kn' || lang === 'en';
    }

    function resolveInitial() {
        try {
            var q = new URLSearchParams(window.location.search).get('lang');
            if (valid(q)) return q;
        } catch (e) { /* URLSearchParams unsupported — ignore */ }

        var stored = null;
        try { stored = localStorage.getItem(KEY); } catch (e) { /* private mode */ }
        if (!valid(stored)) stored = readCookie(KEY);
        if (valid(stored)) return stored;

        var nav = (navigator.language || navigator.userLanguage || '').toLowerCase();
        return nav.indexOf('kn') === 0 ? 'kn' : 'en';
    }

    function apply(lang) {
        var kn = lang === 'kn';
        var root = document.documentElement;
        root.lang = kn ? 'kn' : 'en';
        root.classList.toggle('lang-kn', kn);

        var nodes = document.querySelectorAll('[data-kn]');
        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            if (el.__enText == null) {
                el.__enText = el.textContent;
            }
            var t = el.getAttribute('data-kn');
            el.textContent = (kn && t && t.trim() !== '') ? t : el.__enText;
        }

        // Form placeholders (attribute, not text content)
        var phNodes = document.querySelectorAll('[data-kn-placeholder]');
        for (var p = 0; p < phNodes.length; p++) {
            var pel = phNodes[p];
            if (pel.__enPlaceholder == null) {
                pel.__enPlaceholder = pel.getAttribute('placeholder') || '';
            }
            var pt = pel.getAttribute('data-kn-placeholder');
            pel.setAttribute('placeholder', (kn && pt && pt.trim() !== '') ? pt : pel.__enPlaceholder);
        }

        var toggle = document.getElementById('langToggle');
        if (toggle) {
            toggle.setAttribute('data-active', lang);
            var opts = toggle.querySelectorAll('[data-lang-opt]');
            for (var j = 0; j < opts.length; j++) {
                var on = opts[j].getAttribute('data-lang-opt') === lang;
                opts[j].classList.toggle('is-active', on);
                opts[j].setAttribute('aria-pressed', on ? 'true' : 'false');
            }
        }
    }

    function set(lang) {
        if (!valid(lang)) return;
        try { localStorage.setItem(KEY, lang); } catch (e) { /* ignore */ }
        writeCookie(KEY, lang);
        apply(lang);
    }

    // Public API (handy for the toggle and for debugging)
    window.NgoI18n = { set: set, apply: apply, resolve: resolveInitial };

    function init() {
        apply(resolveInitial());
        var toggle = document.getElementById('langToggle');
        if (toggle) {
            toggle.addEventListener('click', function (e) {
                var opt = e.target.closest ? e.target.closest('[data-lang-opt]') : null;
                if (!opt) return;
                e.preventDefault();
                set(opt.getAttribute('data-lang-opt'));
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
