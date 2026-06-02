# Design — Kannada (ಕನ್ನಡ) ⇄ English toggle for the public NGO microsite

**Date:** 2026-06-02
**Project:** FevourdK (Laravel 12 + Inertia + Vue 3 + MySQL)
**Goal:** Add a beautiful, efficient Kannada ⇄ English language toggle to each NGO's
public Vikasana-style microsite (server-rendered Blade). Karnataka-based platform →
Kannada is high-value for local communities.

---

## Confirmed scope (decided with user)

- **Translate everything we can, without machine translation or fake data:**
  - All fixed UI chrome + section headings + buttons + stat labels + contact form + footer.
  - All *template-default* content blocks (mission, approach, achievements, gallery
    captions, impact labels) — these are **our** strings, so we ship hand-written Kannada.
  - The few genuinely admin-typed fields (tagline, mission, about, story titles/bodies)
    become **optionally bilingual** via new Kannada inputs in the no-code editor.
- **Default language: auto-detect** from `navigator.language` (`kn*` → Kannada, else
  English), always overridable by the toggle and persisted.
- **Toggle UX:** subtle on-brand `EN | ಕನ್ನಡ` segmented pill in the navbar.

Machine translation (option c) is explicitly **out** — quality/cost risk for Kannada,
can mangle proper nouns. Not built.

---

## Architecture — client-side i18n over server-rendered Blade

The microsite is Blade + vanilla `script.js` with **no Vue reactivity**, so translation
is a **client-side DOM swap**. Two complementary mechanisms, one engine:

1. **Static dictionary** (fixed UI + our template-default content)
   - Each translatable element gets `data-i18n="some.key"`.
   - A new file `public/assets/Themes/i18n-kn.js` holds `{ "some.key": "ಕನ್ನಡ string" }`.
   - English remains the rendered Blade default = single source of truth. On first run
     the engine caches each element's original English (`el._enText`), swaps to Kannada
     on KN, restores original on EN. (Uses `textContent` for plain nodes; an explicit
     allow-list of keys may use `innerHTML` only where markup must be preserved.)

2. **Per-element inline Kannada** (admin-typed prose)
   - Blade renders `data-kn="{{ $kn }}"` on the element from the NGO's Kannada fields.
   - Engine swaps to `data-kn` when present and non-empty; otherwise leaves the English
     (graceful fallback → no blanks, no fake data).

The single toggle engine walks `[data-i18n]` and `[data-kn]` together on each switch.

---

## Font (the make-or-break detail)

Inter (and Fraunces) have **zero Kannada glyphs** → tofu (□) without a Kannada webfont.

- Add to `<head>`, mirroring the existing Inter `<link>`:
  `Noto Sans Kannada` (body) + `Noto Serif Kannada` (display), via Google Fonts
  `display=swap`.
- Apply only under a `body.lang-kn` class, scoped inside the **existing inline `<style>`
  block** in the blade — **no edits to the 198 KB `styles.css`.**
  ```css
  body.lang-kn { font-family: 'Noto Sans Kannada','Inter',system-ui,sans-serif; }
  body.lang-kn h1, body.lang-kn h2, body.lang-kn h3, body.lang-kn h4,
  body.lang-kn .section-title, body.lang-kn .hero-title, body.lang-kn .nav-link,
  body.lang-kn .btn { font-family: 'Noto Serif Kannada','Noto Sans Kannada',serif; }
  ```
  Noto Sans Kannada covers Latin too, so any residual English in KN mode stays consistent.

---

## Toggle UI

- A segmented **`EN | ಕನ್ನಡ`** pill, on-brand: gold `#f2b40c` active state, paper/ink,
  subtle — feels premium, not bolted-on.
- Lives **always-visible in the navbar bar** (NOT inside the collapsing hamburger menu),
  so it is one tap on mobile without opening the menu. Mobile-first.

## Default + persistence

Resolution order on load:
1. `?lang=en|kn` query param (explicit override / shareable links)
2. `localStorage.ngo_lang` or cookie `ngo_lang`
3. **auto-detect** `navigator.language` → `kn*` ? `kn` : `en`

- A tiny **inline head script** sets the `lang-kn` body class + `<html lang>` **before
  paint** → no flash of wrong language/font (FOUC).
- Toggle writes `localStorage.ngo_lang` **and** a `path=/` cookie `ngo_lang`
  (survives reloads + page nav; server-readable later if ever needed).
- Sets `<html lang="kn|en">` for accessibility.

---

## Editor (resources/js/Pages/NGO/Digitalization.vue)

- Add **optional Kannada inputs** next to the English ones in the Welcome / Mission /
  About / Stories accordion sections (clearly labelled "ಕನ್ನಡ (optional)").
- Saved through the **existing `microsite_json` contract** on `PUT /ngo/digitalization`
  — new keys: `tagline_kn`, `mission_kn`, `about_kn`, and per-story `title_kn` / `body_kn`.
- `App\Support\NgoMicrositeContent::for($ngo)` surfaces the `*_kn` values so the blade can
  emit `data-kn`.
- **Verify**: the digitalization save validation accepts the new keys (the prior
  `website_url` 422 gotcha is a reminder to keep the payload clean).

---

## Files touched

| File | Change |
|------|--------|
| `resources/views/ngo/site-template.blade.php` | font links, `body.lang-kn` CSS, toggle markup, `data-i18n` / `data-kn` attrs, early head script, load `i18n-kn.js` |
| `public/assets/Themes/i18n-kn.js` *(new)* | Kannada dictionary + toggle engine |
| `app/Support/NgoMicrositeContent.php` | surface `*_kn` fields |
| `resources/js/Pages/NGO/Digitalization.vue` | optional Kannada inputs |
| `NGOWebsiteController` / digitalization save | verify validation accepts new keys (likely no change) |

---

## Verification (Playwright — DOM eval, NOT screenshots)

Screenshots hang on a fonts-loaded wait in this env → use `browser_evaluate`.
Test `http://127.0.0.1:8080/vikasana` at **mobile 390×844** and **desktop 1440×900**:

- Toggle renders and flips **all** targeted strings KN ↔ EN and back.
- Kannada webfont actually applies: `getComputedStyle` on a Kannada node resolves to a
  Noto family **and** the glyph has non-zero width (no tofu).
- **0 console errors.**
- **No horizontal overflow** — Kannada strings run longer; watch nav + buttons.
- Choice **persists across reload** (localStorage + cookie).
- Auto-detect path sanity-checked.

---

## Out of scope / YAGNI

- Machine translation.
- Translating the NGO's proper name (stays as the brand renders it; if an admin wants a
  Kannada name they can use the editor field — but default leaves name as-is).
- Any other locale beyond Kannada/English.
