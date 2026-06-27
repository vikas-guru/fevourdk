# 🚀 AgentHack — Run-the-Show Runbook (do this tomorrow)

Everything is built and committed. This is your click-through order. Budget ~30–40 min.

> **First:** the account was rate-limited until **2:20am IST** tonight — that's also why the
> Android remote app stopped responding. After the reset it all works again. Nothing below
> depends on Claude; you can do it all yourself from the laptop browser.

---

## 0. One-time: make the repo public (2 min)
- GitHub → repo → **Settings → General → Danger Zone → Change visibility → Public**.
- Open the repo URL in an **incognito window** to confirm it loads signed-out.
- (Branch `codex/agenthack-submission-prep` holds everything — either submit from it or merge to `main` first.)

## 1. The demo video (10–15 min) — already cinematic, just capture it
File: `docs/agenthack-video-animatic.html` (cinematic film w/ music + the "How we harness UiPath" scene + count-up impact).
- **Fastest:** open it in Chrome, press **F11 (fullscreen)**, click **play** (sound on), and screen-record the ~4:54 run (QuickTime: ⌘⇧5, or OBS). Stop at the end card.
- Trim dead head/tail, export **MP4 < 5:00**, upload to **YouTube/Vimeo as Unlisted (public link)**.
- Confirm it plays signed-out and audio is audible.
- Optional polish: read `docs/agenthack-voiceover.md` aloud over it, or run it through a TTS.

## 2. UiPath sandbox screenshots (8 min) — the one judging gap left
Log into `cloud.uipath.com/fevourdk/...` and capture (mask the token everywhere):
- **Maestro** — the "ImpactOps Maestro" process canvas.
- **Studio** — project tree showing the 5 packages (Compliance Review, Campaign Draft, Field Proof, Finance Claim, CSR Report).
- **Orchestrator** — Queues, Assets (token MASKED), Triggers (folder `Fevourd-K-Demo`).
- **Action Center** — the compliance sign-off + finance approve/reject tasks.
Drop the PNGs into the `uipath/` folder and commit. (Exact list is in `uipath/orchestrator-manifest.md`.)

> Bonus credibility: the integration is **real** — `routes/api.php` exposes live read-only
> endpoints (`/api/ngo/vikasana/documents`, `/finance/claims`, `/csr/impact`, etc.) returning
> the seeded Vikasana data. If you can, screen-record one UiPath agent calling `/api/health`
> or `/api/ngo/vikasana/finance/claims` with the bearer token — that's a wow moment.

## 3. Devpost (10 min) — paste, don't write
Open `docs/agenthack-devpost-copy.md` — it has every field ready. Paste in this order:
1. Project name, Tagline, Elevator pitch.
2. Inspiration · What it does · How we built it · Challenges · Accomplishments · What we learned · What's next.
3. **Built With** tags (UiPath Maestro/Studio/Orchestrator/Action Center · Laravel · Inertia · Vue · MySQL · Tailwind).
4. Upload the deck: `docs/agenthack-deck.pptx` (or a public link).
5. **Four links:** GitHub · Demo video · Deck · Live sandbox.
6. Add the video link in the video field.
- **Save as draft, review once, THEN submit.** (Don't auto-submit blind.)

## 4. Final 3-minute safety pass before you hit Submit
- [ ] Secret scan after adding UiPath screenshots: no token/PII visible on any image.
- [ ] Every link opens in **incognito** (repo, video, deck, sandbox).
- [ ] Video **< 5:00**, audio audible, sandbox/demo data only.
- [ ] Deck opens and renders (slides 1→13).
- [ ] Devpost preview looks right, then **Submit**.

---

## What's already done (committed — don't redo)
| ✅ | File / commit |
|----|----|
| Cinematic demo film + voiceover | `docs/agenthack-video-animatic.html`, `docs/agenthack-voiceover.md` (ea82285) |
| Real working UiPath agent API | `routes/api.php` + controller/middleware (a3f0c88) |
| Branded 13-slide deck | `docs/agenthack-deck.pptx` |
| Devpost copy (all fields) | `docs/agenthack-devpost-copy.md` |
| UiPath manifest + payloads | `uipath/orchestrator-manifest.md`, `uipath/sample-payloads.md` |
| 12 product screenshots | `docs/agenthack-assets/screenshots/` |
| Demo script + checklists | `docs/agenthack-demo-video-script.md`, `docs/agenthack-readiness-checklist.md` |

**The story to tell judges:** Fevourd-K is the NGO/CSR system of record; UiPath ImpactOps
Maestro is the agentic layer that runs six back-office workflows, escalates only money &
compliance to a human, and writes results back — proven by a *real* read-only API and a real
seeded campaign (Clean Water for Mandya, ₹5.12L/₹8L). Less paperwork, more clean water delivered.

You've got this. 🌊
