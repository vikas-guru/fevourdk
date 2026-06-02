# Help Assistant ("Disha") + Per-Page Guided Tours — Design

**Date:** 2026-06-02
**Status:** Approved
**Project:** FevourdK NGO workspace

## Goal
1. Give every NGO workspace page (and donor + volunteer dashboards) a first-run guided tour, reusing the existing `DashboardTour.vue` spotlight component, driven by a central registry so copy lives in one place.
2. Ship an on-brand, searchable help center surfaced as a friendly floating **"Disha"** help-bot present on every workspace page, plus a full `/ngo/help` browse page. Content from a PHP array (no DB).

## Architecture (4 isolated units)

### 1. Tour registry — `resources/js/ngo/tours.js`
Plain data export `TOURS = { [pageKey]: { storageKey, steps: [{selector, title, body}] } }` + `getTour(pageKey)`. All tour copy lives here. Selectors target `[data-tour="…"]` anchors placed on each page.

### 2. Tour controller — `resources/js/ngo/tourController.js`
Module singleton (dependency-free pub/sub). `register(fn)` / `clear()` / `start()` / `hasTour` ref. Bridges the shell-mounted bot to the page-mounted tour across Inertia's slot boundary.

### 3. Composable — `resources/js/ngo/useNgoTour.js`
`useNgoTour(pageKey)` → `{ tourRef, steps, storageKey, hasTour, startTour }`. Looks up the registry, registers `startTour` into `tourController` on mount, clears on unmount. Page wiring = import + 1 call + `<DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />` + anchors.

### 4. Help bot — `resources/js/Components/NGO/HelpAssistant.vue`
Floating button (bottom-right, above mobile tab bar), mounted once in `NgoWorkspaceShell.vue`. Opens a paper/ink panel (bottom sheet on mobile, anchored on desktop): greeting → "Take a tour of this page" (calls `tourController.start()`) → search box + category chips → article list → inline article reader → link to `/ngo/help`. Lazy-fetches articles from `GET /ngo/help/articles` on first open. Impact Dossier styling (Fraunces, gold accent), reduced-motion aware, scroll-lock when open.

### Content + routes
- `app/Support/HelpCenter.php` — `categories()` + `articles()` returning real-platform-fact articles (light HTML body, icon, category, related links). Single source of truth.
- `app/Http/Controllers/NGO/HelpController.php` — `index()` (Inertia `NGO/Help`) + `articles()` (JSON).
- Routes inside the `ngo.` group: `GET /ngo/help` → `ngo.help`, `GET /ngo/help/articles` → `ngo.help.articles`.
- `resources/js/Pages/NGO/Help.vue` — full browse page using the same data; nav link "Help & guides" in the shell.

### Donor + volunteer
Registry entries `donor-dashboard`, `volunteer-feed` + `DashboardTour` wired into `Donor/Dashboard.vue` and `Feeds/Index.vue` with their own storageKeys. Full Disha bot stays NGO-workspace (those pages don't use the NGO shell); they get first-run tours + a small "Take a tour" button.

## Data flow
First-run on a page → composable auto-starts that page's tour once (localStorage gate). Bot open → lazy-fetch articles → client-side search/filter → inline reader. Bot "Take a tour" → `tourController.start()` → current page `DashboardTour.start()`.

## Verify
Playwright at 390×844 + 1440×900: renders, 0 console errors, no horizontal overflow, tour fires once + steps, bot opens/searches/filters, KB page works. DOM-eval over screenshots (fonts-loaded hang in this env).
