# AgentHack — Submission-Day Readiness Checklist

**Submit by:** June 29, 2026 11:45 PM EDT / June 30 9:15 AM IST.
Legend: ✅ done · 🟡 needs you (human/UiPath/account) · ⏳ optional polish.

---

## A. Product & repo (✅ mostly done by Claude)
- ✅ Fevourd-K product code (Laravel 12 + Vue 3) on branch `codex/agenthack-submission-prep`.
- ✅ MIT `LICENSE`, public-safe `deploy-hostinger.sh` (all creds env-driven).
- ✅ Secret scan: no `.env` in history, no live keys; Razorpay key is the `YourKeyHere` placeholder; backups removed.
- 🟡 **Make the GitHub repo public** and open it in a **signed-out browser** to confirm it loads.
- 🟡 Merge `codex/agenthack-submission-prep` → `main` (or submit from this branch).

## B. Pitch assets (✅ done)
- ✅ Deck: `docs/agenthack-deck.pptx` (13 slides, real Vikasana use case, branded).
- ✅ Deck outline: `docs/agenthack-deck-outline.md`.
- ✅ Devpost copy (all fields): `docs/agenthack-devpost-copy.md`.
- ✅ Demo video script: `docs/agenthack-demo-video-script.md`.
- ✅ Branded video animatic (cinematic, music): `docs/agenthack-video-animatic.html`.

## C. The demo video (🟡 your action tomorrow)
- ✅ Storyboard + animatic ready to record.
- 🟡 **Produce the final video** — pick one:
  - **Fast path:** open `agenthack-video-animatic.html` fullscreen, click play, screen-record it (built-in music) → upload. ~10 min.
  - **Strong path:** screen-record the live Fevourd-K app following the script, intercut the UiPath sandbox screens, add the animatic intro/outro. ~1–2 hrs.
- 🟡 Add a voiceover (script is in the demo-video-script; or use the on-screen captions).
- 🟡 Upload to YouTube/Vimeo **unlisted-public** and confirm it plays signed-out, < 5:00.

## D. UiPath evidence (✅ artifacts ready · 🟡 screenshots from you)
- ✅ `uipath/orchestrator-manifest.md` — Maestro nodes, queues, assets, triggers, Action Center tasks.
- ✅ `uipath/sample-payloads.md` — sanitized read/write payloads per workflow.
- 🟡 **Add real UiPath screenshots/exports** from your sandbox into `uipath/`:
  - Maestro "ImpactOps Maestro" process canvas.
  - Studio project tree (the 5 packages).
  - Orchestrator queues + assets (token masked) + triggers.
  - Action Center: the compliance sign-off and finance approve/reject tasks.
  - (If you have them) exported `.nupkg` / process export — else screenshots are fine.

## E. Devpost form (🟡 you, ~15 min)
- 🟡 Paste fields from `docs/agenthack-devpost-copy.md` (title, tagline, inspiration, what-it-does, how-built, challenges, accomplishments, learned, next, Built-With tags).
- 🟡 Add the 4 links: **GitHub · Demo video · Deck · Live sandbox**.
- 🟡 Upload deck (`agenthack-deck.pptx`) or a public deck link.
- 🟡 Confirm the agent-type framing (hybrid: Maestro orchestrates + Studio automates + human approval).

## F. Final pre-submit pass (🟡 you)
- 🟡 Re-run a quick secret scan after adding UiPath screenshots (no tokens visible).
- 🟡 Verify every link opens from a signed-out/incognito window.
- 🟡 Double-check video < 5:00 and audio is audible.

---

### Owner summary
| Done by Claude (✅) | Needs you tomorrow (🟡) |
|---|---|
| Deck, animatic, scripts, Devpost copy, UiPath manifest + payloads, secret scrub | Make repo public · record/upload video · add UiPath screenshots · fill Devpost · paste 4 links · final link check |

**Single most important gap:** the **demo video file** and the **UiPath sandbox screenshots** — everything else is ready to paste.
