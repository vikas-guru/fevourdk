# AgentHack 2026 — Submission Index (master map)

One-page map of every submission artifact, where it lives, and its status.
Status legend: ✅ ready · 🟡 needs you tomorrow.

**Project:** Fevourd-K (Laravel 12 + Inertia + Vue 3, MySQL) — NGO/CSR platform.
**Agentic layer:** UiPath "ImpactOps Maestro" (Maestro orchestrates · Studio automates · Action Center = human-in-the-loop).
**Branch:** `codex/agenthack-submission-prep`.

---

## Pitch & narrative
| Status | Artifact | Path | What it is |
|--------|----------|------|------------|
| ✅ | Deck (13 slides, branded) | `docs/agenthack-deck.pptx` | Real Vikasana use case; upload to Devpost. |
| ✅ | Deck outline | `docs/agenthack-deck-outline.md` | Slide-by-slide text source for the deck. |
| ✅ | Devpost copy (all fields) | `docs/agenthack-devpost-copy.md` | Title, tagline, inspiration, what/how, Built-With — paste-ready. |
| ✅ | Demo video script | `docs/agenthack-demo-video-script.md` | Timed shot list + narration. |
| ✅ | Video animatic (cinematic, music) | `docs/agenthack-video-animatic.html` | Open fullscreen in Chrome, play, screen-record (~4:54). |
| ✅ | Voiceover script | `docs/agenthack-voiceover.md` | Optional narration to read/TTS over the animatic. |

## UiPath evidence
| Status | Artifact | Path | What it is |
|--------|----------|------|------------|
| ✅ | Orchestrator/Maestro manifest | `uipath/orchestrator-manifest.md` | Maestro nodes, Studio packages, queues, assets, triggers, Action Center tasks. |
| ✅ | Sample payloads (sanitized) | `uipath/sample-payloads.md` | Read/write request/response bodies; token masked. |
| ✅ | UiPath artifacts README | `uipath/README.md` | What UiPath evidence to include + public-safety rules. |
| ✅ | Screenshot capture checklist | `uipath/screenshots/README.md` | Exact filenames + what each sandbox screenshot must show. |
| 🟡 | Real UiPath screenshots | `uipath/screenshots/*.png` | **You capture tomorrow** from the sandbox (token masked). |

## Working integration (proof it's real)
| Status | Artifact | Path | What it is |
|--------|----------|------|------------|
| ✅ | Agent API (read-only) | `routes/api.php` | Live token-guarded `/api/*` endpoints UiPath reads from. |
| ✅ | Token middleware | `app/Http/Middleware/VerifyUipathToken.php` | Bearer-token guard; token from `UIPATH_AGENT_TOKEN` env (sandbox default). |
| ✅ | API controller | `app/Http/Controllers/Api/ImpactOpsApiController.php` | Returns seeded Vikasana data (health, documents, claims, csr impact, field trail). |

## Product screenshots
| Status | Artifact | Path | What it is |
|--------|----------|------|------------|
| ✅ (10/12) | Product screenshots | `output/playwright/agenthack-screenshots/01..12.png` | Public site, login, NGO dashboard, compliance, feed studio, field proof, finance, ledger, mobile, CSR. |
| ✅ | Mirror for deck/Devpost | `docs/agenthack-assets/screenshots/01..12.png` | Byte-identical copy of the above (consistent). |
| 🟡 | Re-capture `01` + `02` | (same paths) | `01-public-home` is the intro splash; `02-vikasana-microsite` is a faded mid-animation render. Recapture once the app is up (see below). |

## Process & checklists
| Status | Artifact | Path | What it is |
|--------|----------|------|------------|
| ✅ | GO-LIVE runbook | `docs/agenthack-GO-LIVE-tomorrow.md` | Click-through submission order for the morning. |
| ✅ | Readiness checklist | `docs/agenthack-readiness-checklist.md` | Done vs. needs-you breakdown. |
| ✅ | This index | `docs/agenthack-SUBMISSION-INDEX.md` | Master map of all artifacts. |

## Repo hygiene
| Status | Artifact | Path | What it is |
|--------|----------|------|------------|
| ✅ | License | `LICENSE` | MIT. |
| ✅ | README (AgentHack section) | `README.md` | Outside-judge intro + links to runbook/index/deck/UiPath. |
| ✅ | Secret scan | — | PASS: no `.env` tracked, no live tokens, Razorpay = `rzp_test_YourKeyHere`, UiPath token = sandbox placeholder. |

---

## Still requiring the human tomorrow (🟡)
1. **Make the GitHub repo public** and confirm it loads signed-out (incognito).
2. **Record + upload the demo video** (animatic fullscreen → screen-record → YouTube/Vimeo unlisted-public, < 5:00).
3. **Capture the UiPath sandbox screenshots** into `uipath/screenshots/` per its README (token masked).
4. **Re-capture product screenshots `01` and `02`** — needs the local app running (XAMPP MySQL + `php artisan serve` on :8080, OTP 1234); the DB could not be started here without sudo.
5. **Fill the Devpost form** from `docs/agenthack-devpost-copy.md`, upload the deck, add the 4 links (GitHub · video · deck · sandbox).
6. **Final pass:** re-run the secret scan after adding UiPath screenshots; verify every link in incognito; video < 5:00.
