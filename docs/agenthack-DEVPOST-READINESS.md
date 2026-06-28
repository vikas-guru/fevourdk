# AgentHack — Devpost Submission-Readiness Report

**Generated:** 2026-06-28 · **Deadline:** June 29, 2026 11:45 PM EDT
**Entry:** ImpactOps Maestro — Fevourd-K (NGO/CSR) × UiPath AgentHack 2026
**Verified against the real filesystem at** `/Users/vikasguru/GURU/FevourdK`

> Live-check headline: the **GitHub repo is already PUBLIC** and the **prod agent API is already live** (`/api/health` → 200 with token, 401 without). Both were listed as "needs you" in older docs — they are now DONE. The only true human gaps left are the **video upload** and the **real UiPath screenshots**.

---

## A. Devpost form — copy/paste values

Source of truth: `docs/agenthack-devpost-FINAL.md`. Paste verbatim.

### Project name / title
```
ImpactOps Maestro — Agentic Back-Office for NGOs & CSR, on UiPath
```

### Tagline / Short description (one line)
```
Fevourd-K is the NGO/CSR system of record; UiPath ImpactOps Maestro is the agentic layer that runs six back-office workflows and escalates only the decisions that need a human.
```

### Elevator pitch (≈ 50 words)
```
NGOs lose days to manual compliance checks, campaign drafting, field verification, expense claims, and CSR reporting. ImpactOps Maestro is a UiPath agentic process that reads live data from the Fevourd-K platform, runs all six workflows, routes money and compliance calls to a human in Action Center, and writes results back — with a full audit trail.
```

### Inspiration
```
We build Fevourd-K, a Laravel/Vue platform that connects NGOs, corporate CSR teams, donors, and field volunteers. Talking to NGOs, the pattern was always the same: the system was modern, but the operations between screens were still manual — chasing renewal documents, hand-writing donor updates, eyeballing field photos, re-keying expense receipts into ledgers, and assembling CSR reports through week-long email threads. These are repeatable, rules-based chores with occasional judgment calls — the perfect shape for agentic automation. AgentHack was the push to build the layer that does this work without ever taking control of the data away from the NGO.
```

### What it does
```
ImpactOps Maestro is a UiPath Maestro agentic process that coordinates six workflows on top of Fevourd-K:

1. Compliance Review — pulls NGO registration/renewal documents, checks expiry, mandatory fields, and status; routes findings to a compliance officer for sign-off.
2. Campaign Draft — reads the latest campaign + field progress and assembles a donor/CSR-ready update as a draft (never auto-posted) in Fevourd-K's Feed Studio.
3. Field Proof — verifies field-task evidence: active session, GPS trail vs. assigned site, completion status; marks tasks Verified.
4. Finance Claim — checks expense claims against budget line and policy limits, escalates anything over threshold to Action Center, and posts approved claims to the NGO ledger.
5. CSR Report — on a schedule, gathers verified field proof, approved spend, and compliance status into one CSR impact + compliance summary.
6. ImpactOps Maestro Agentic Process — orchestrates the end-to-end handoffs and human approvals across the five workflows.

Throughout, Fevourd-K stays the system of record and the human portal, and UiPath is the agentic operations layer — read, act, escalate, write back.
```

### How we built it
```
- Product / system of record: Fevourd-K — Laravel 12 + Inertia + Vue 3, MySQL — exposes the campaigns, field sessions/GPS trails, finance claims, ledger, compliance documents, and CSR dashboard that the agents read from and write back to.
- Orchestration: UiPath Maestro runs the "ImpactOps Maestro" agentic process (the six-node flow with human-approval nodes).
- Automations: UiPath Studio packages — one per workflow (Compliance Review, Campaign Draft, Field Proof, Finance Claim, CSR Report).
- Runtime: UiPath Orchestrator (folder Fevourd-K-Demo) with per-workflow queues, a credential asset for the sandbox Fevourd-K API token, a config asset for the finance approval threshold, and event + time (cron) triggers.
- Human-in-the-loop: UiPath Action Center holds the compliance sign-off and the finance Approve/Reject decisions.
- Integration: Studio agents call Fevourd-K's JSON APIs to read state and write results back (status flips, scheduled drafts, verified badges, ledger entries, assembled reports).
```

### Challenges we ran into
```
- Drawing the human/agent line. We deliberately kept money (Finance Claim) and compliance sign-off as mandatory human approvals via Action Center, and let lower-risk steps run unattended — so "agentic" never means "unsupervised where it matters."
- Keeping Fevourd-K as the single source of truth. The agents had to coordinate work without owning data; every write goes back into Fevourd-K so the audit trail stays intact.
- Making evidence verifiable, not narrated. For Field Proof we leaned on real GPS-trail data so verification is checkable, not a vibe.
- Public-safety for a public demo. We rebuilt the entire flow on a sandbox tenant with fake NGOs/amounts, masked tokens, and redacted PII so nothing sensitive ships in the submission.
```

### Accomplishments that we're proud of
```
- A genuine closed perceive → act → escalate → write-back loop across six real workflows, not a scripted happy-path.
- Real bidirectional integration on the finance flow: claim → human decision → posted ledger entry, end to end.
- A clean architecture story judges can trust: Fevourd-K never loses control of the data; UiPath never makes the money decision alone.
```

### What we learned
```
Agentic value in the social sector isn't a flashy chatbot — it's reliably removing recurring operational drudgery while strengthening the audit trail. The orchestration + human-approval pattern (Maestro + Action Center) maps almost perfectly onto NGO/CSR governance needs.
```

### What's next
```
- Expand to more workflows (donor reconciliation, grant milestone tracking, vendor verification).
- Multi-NGO tenancy with per-NGO Orchestrator folders and approval routing.
- Confidence scoring so agents auto-approve only the clearest cases and escalate the rest.
- A "trust dashboard" in Fevourd-K showing every agent action and who approved it.
```

### "What are you hoping to get out of this hackathon?" (extra Devpost question) — Option A (recommended)
```
I'm a solo builder shipping a real NGO/CSR product, and I wanted AgentHack to stress-test one specific bet: that the right way to bring agents into the social sector is human-in-the-loop, not full autonomy. I'm hoping to validate that pattern in front of people who actually build agentic systems, get honest feedback from the UiPath community on where my Maestro + Action Center design holds up or breaks, and use that to move Fevourd-K toward its first real NGO pilots. If even one judge or builder tells me what they'd trust an agent to do unsupervised in an NGO back-office, that's a win.
```
(Options B and C are in `docs/agenthack-devpost-FINAL.md` lines 99–103 if a different tone is wanted.)

### Built With (Devpost tags — one per line, no commas)
```
UiPath Maestro
UiPath Studio
UiPath Orchestrator
UiPath Action Center
Laravel
Inertia.js
Vue 3
MySQL
Tailwind CSS
```

### The 4 links (paste into Devpost "Try it out" / video fields)

| # | Field | Value to paste | Status |
|---|-------|----------------|--------|
| 1 | **GitHub repo** | `https://github.com/vikas-guru/fevourdk` | ✅ Confirmed **PUBLIC** (see C) |
| 2 | **Demo video URL** | ⬜ `<paste YouTube unlisted/public-link URL>` — **NOT YET UPLOADED.** Upload `docs/agenthack-assets/ImpactOps-Maestro-demo-elevenlabs-v2.mp4` (the newest preferred final) first. | ⬜ Human step |
| 3 | **Deck** | Upload `docs/agenthack-deck.pptx` directly on Devpost (4.0 MB) | ✅ File present |
| 4 | **Live demo / sandbox** | `https://fevourdk.online/` | ✅ Live (product portal, HTTP 200) |

- Agent API base (what UiPath calls): `https://fevourdk.online/api` — **now live** (200 with bearer token). Optional to mention; the "Live demo" judges click should stay `https://fevourdk.online/`.
- Note: the FINAL doc still says the repo is "currently PRIVATE" and the API is "404 until deployed." **Both statements are now stale** — live checks below confirm the repo is public and `/api/health` returns 200. No action needed on those two items.

---

## B. Asset verification table

| Asset | Path | Present? | Size / details |
|-------|------|----------|----------------|
| **Final video (preferred, v2)** | `docs/agenthack-assets/ImpactOps-Maestro-demo-elevenlabs-v2.mp4` | ✅ Yes | 62.9 MB · **duration 294.0s (4:54)** · video **h264** + audio **aac** (both streams present) · under 5:00 ✅ |
| Older video (elevenlabs v1) | `docs/agenthack-assets/ImpactOps-Maestro-demo-elevenlabs.mp4` | ✅ Yes | 59.9 MB · duration 294.0s |
| Base demo video | `docs/agenthack-assets/ImpactOps-Maestro-demo.mp4` | ✅ Yes | 59.9 MB · duration 294.0s |
| (other variants) | `…-compat.mp4`, `…-prev.mp4`, `…-samantha.mp4`, `animatic-silent.mp4` | ✅ Yes | 49–60 MB each — ignore; use **v2** |
| **Deck** | `docs/agenthack-deck.pptx` | ✅ Yes | 4.19 MB (4.0 MB) · 13 slides per docs |
| **Product screenshots** | `docs/agenthack-assets/screenshots/` | ✅ Yes | **12/12 PNGs present** (`01-public-home` … `12-csr-dashboard-report`). (A stray `ruvector.db` also sits in this folder.) |
| UiPath manifest | `uipath/orchestrator-manifest.md` | ✅ Yes | 4.3 KB |
| UiPath sample payloads | `uipath/sample-payloads.md` | ✅ Yes | 6.1 KB |
| UiPath README | `uipath/README.md` | ✅ Yes | 1.3 KB |
| **UiPath screenshots** | `uipath/screenshots/` | ⬜ **MISSING / placeholder** | Folder contains **only `README.md`** (capture checklist) — **zero PNGs**. This is the one remaining evidence gap. |

---

## C. Live checks (run 2026-06-28)

### GitHub repo
`gh repo view vikas-guru/fevourdk --json visibility,url,pushedAt` →
```json
{"visibility":"PUBLIC","url":"https://github.com/vikas-guru/fevourdk","pushedAt":"2026-06-27T22:49:47Z"}
```
- **Visibility: PUBLIC ✅** (no `gh repo edit … --visibility public` needed; the FINAL doc's "make it public" step is already done).
- Last push: **2026-06-27 22:49 UTC**.
- Still worth a 10-second incognito open to confirm it loads signed-out.

### Live API health
| Call | Result | Expected | Pass? |
|------|--------|----------|-------|
| `GET https://fevourdk.online/api/health` **with** `Authorization: Bearer sandbox-impactops-demo-token` | **200** | 200 | ✅ |
| `GET https://fevourdk.online/api/health` **without** header | **401** | 401 | ✅ |

The token-guarded agent API is **deployed and live in prod** — both the auth path and the rejection path behave correctly. (This supersedes the older "404 until deployed" notes in FINAL/INDEX.)

---

## D. Readiness checklist

✅ = verified done · ⬜ = remaining (human)

- ✅ Devpost copy (title, tagline, pitch, all long-form sections, Built-With tags) — ready in `agenthack-devpost-FINAL.md`
- ✅ GitHub repo public at `https://github.com/vikas-guru/fevourdk`
- ✅ Live sandbox portal up — `https://fevourdk.online/` (HTTP 200)
- ✅ Live agent API up — `/api/health` 200 with token, 401 without
- ✅ Final demo video file exists, < 5:00, has video + audio streams (`…-elevenlabs-v2.mp4`)
- ✅ Deck present — `docs/agenthack-deck.pptx` (13 slides)
- ✅ 12/12 product screenshots present
- ✅ UiPath manifest + sample payloads (sanitized) present
- ✅ Secret-safety: docs report PASS (sandbox token placeholder, no `.env` tracked)
- ⬜ **Demo video uploaded** to YouTube/Vimeo (unlisted-public) and link pasted into Devpost
- ⬜ **Real UiPath sandbox screenshots** captured into `uipath/screenshots/` (currently empty)
- ⬜ Devpost form filled + deck uploaded + 4 links pasted
- ⬜ Final incognito link check + secret re-scan of any new UiPath images
- ⬜ Devpost preview reviewed → **Submit**

---

## E. Human-only remaining steps (ranked)

1. **Upload the demo video → get the public link.**
   Upload `docs/agenthack-assets/ImpactOps-Maestro-demo-elevenlabs-v2.mp4` (62.9 MB, 4:54, ElevenLabs voiceover + redesigned visuals — the preferred final) to YouTube/Vimeo as **Unlisted (public link)**. Confirm it plays signed-out with audible audio. Paste the URL into Devpost's video field and as link #2. **This is the single biggest gap.**

2. **Capture the real UiPath sandbox screenshots.**
   `uipath/screenshots/` currently holds **only a README** — no PNGs. Capture the 7 required (+2 optional) per `uipath/screenshots/README.md`: Maestro canvas, Studio 5-package tree, Orchestrator queues/assets(token MASKED)/triggers, Action Center compliance sign-off + finance approve/reject. Drop PNGs in with the exact filenames; **mask the token in every image**; commit. Then re-run a secret eyeball pass.

3. **Fill + submit the Devpost form.**
   Paste every field from section A above, upload `docs/agenthack-deck.pptx`, and add the 4 links (GitHub ✅ · video from step 1 · deck ✅ · sandbox `https://fevourdk.online/` ✅). Save as **draft**, review once, then **Submit** before **June 29, 2026 11:45 PM EDT**.

4. **Final safety pass (3 min).**
   Open repo, video, deck, sandbox each in **incognito**; confirm video < 5:00 and audio audible; confirm no token/PII on any UiPath screenshot; Devpost preview looks right → Submit.

### Already done — do NOT redo (older docs say otherwise, but live checks disagree)
- ❌ "Make the GitHub repo public" → **already PUBLIC.**
- ❌ "Deploy the API to prod / `/api/health` returns 404" → **already live, returns 200/401 correctly.**

**Bottom line:** everything paste-able and live is ready. Two genuine human actions remain — **upload the video** and **capture the UiPath screenshots** — then fill and submit the Devpost form.
