# Fevourd-K × UiPath "ImpactOps Maestro" — AgentHack Demo Video Plan

**Target length:** 4:45 (hard ceiling < 5:00)
**Format:** Screen recording (1080p/60fps) + voiceover. No live faces required.
**One-line promise:** *Fevourd-K is the NGO/CSR system of record; UiPath ImpactOps Maestro is the agentic operations layer that runs six back-office workflows and hands humans only the decisions that matter.*

**Branded video assets (cut these in — see §8):** `docs/agenthack-assets/video/` — `title-card.svg` (0:00), `architecture-card.svg` (4:10), `end-card.svg` (4:36), `lower-third.svg` (reusable section/persistent overlay). All carry the real Fevourd-K logo and the official blue/orange/slate palette.

**Public-safety rules baked into this script**
- Demo/sandbox tenant only. NGO = "Vikasana (Demo Tenant)", Corporate = "Acme CSR (Sandbox)".
- Fake names, fake amounts, redacted bank/last-4 fields, blurred any real-looking PII.
- No `.env`, no FTP/DB/SMS/payment/social tokens, no production host secrets on screen.
- OTP shown as `1234` demo value only. Browser address bar shows a sandbox URL or `localhost:8080`.
- UiPath tenant labeled "AgentHack Sandbox". Orchestrator folder = `Fevourd-K-Demo`.

---

## 1) Opening Hook & Closing Pitch

**Hook (spoken at 0:00):**
> "Every NGO runs on the same six chores — compliance, campaigns, field proof, finance, and reporting. Today they're done by hand, across spreadsheets and WhatsApp. Watch six staff-days of work happen in six minutes of agent time — with humans approving only what matters."

**Closing pitch (spoken at 4:25):**
> "Fevourd-K stays the single source of truth. UiPath ImpactOps Maestro is the agentic layer on top — it reads from Fevourd-K, runs the busywork, escalates the judgment calls, and writes the result back. Not a chatbot — an operations team that never sleeps, with a full audit trail. ImpactOps Maestro — the agentic back-office for social impact."

---

## 2) Second-by-Second Narration Script

> Voiceover is conversational, ~120 wpm. Bracketed `[ACTION]` = what's on screen. **Bold** = on-screen text overlay the editor adds.

### 0:00–0:25 — Problem + Product Intro
- **0:00** [Cold open: Fevourd-K NGO dashboard `/ngo/dashboard`, sandbox tenant.] "Meet Fevourd-K — the operating system for NGOs and corporate CSR teams." **Overlay: Fevourd-K — NGO/CSR System of Record**
- **0:08** [Slow pan across dashboard tiles: campaigns, field tasks, finance, compliance.] "Campaigns, field operations, finance claims, ledgers, compliance reports — all in one place."
- **0:16** [Cut to a tired spreadsheet / WhatsApp mock for half a second, then back.] "But the work *between* these screens is manual. That's where agents come in." **Overlay: The busywork is still manual →**

### 0:25–0:55 — ImpactOps Maestro Overview
- **0:25** [Switch to UiPath Maestro process canvas: "ImpactOps Maestro Agentic Process".] "This is UiPath ImpactOps Maestro — our agentic process." **Overlay: UiPath Maestro — Agentic Orchestration**
- **0:33** [Highlight the BPMN-style nodes in sequence.] "It coordinates six workflows: Compliance Review, Campaign Draft, Field Proof, Finance Claim, CSR Report — and the Maestro process that ties them together."
- **0:44** [Point to a human-approval node (Action Center task) glowing in the diagram.] "Each agent reads from Fevourd-K, runs the repeatable work, and escalates real decisions to a person." **Overlay: Read → Act → Escalate → Write back**
- **0:52** [Zoom to the start trigger.] "Let's trigger a real operations run."

### 0:55–1:35 — Workflow 1: Compliance Review
- **0:55** [Fevourd-K `/ngo/documents` — compliance/registration docs list, sandbox docs.] "It starts in Fevourd-K. Our demo NGO just uploaded renewal documents." **Overlay: ① Compliance Review**
- **1:04** [Cut to UiPath Orchestrator → Queues → `compliance-review-queue` with a new item.] "Maestro drops a job in the Orchestrator queue."
- **1:12** [Studio package "Compliance Review" — show the workflow briefly running / Orchestrator job "Running".] "The Compliance Review agent pulls the documents via the Fevourd-K API, checks expiry dates, mandatory fields, and registration status."
- **1:22** [UiPath Action Center task: "Compliance finding — needs human sign-off" with summary fields.] "It flags one document expiring soon and routes a summary to a compliance officer." **Overlay: Human-in-the-loop ✓**
- **1:30** [Click Approve in Action Center → cut to Fevourd-K compliance status flips to "Reviewed".] "One click. The status writes straight back into Fevourd-K."

### 1:35–2:10 — Workflow 2: Campaign Draft
- **1:35** [Fevourd-K `/ngo/feed-studio` (Feed/Campaign Studio), empty draft.] "Next: a fundraising update needs to go out." **Overlay: ② Campaign Draft**
- **1:44** [Orchestrator trigger fires → Studio "Campaign Draft" package.] "The Campaign Draft agent reads the latest campaign and field progress from Fevourd-K."
- **1:54** [Show generated draft text + suggested image slots populating the Feed Studio composer.] "It assembles a donor-ready, CSR-safe update — copy, impact numbers, hashtags — as a *draft*, never auto-posted."
- **2:04** [Human clicks "Review & schedule" in Feed Studio.] "A human reviews and schedules. Agent does the writing; the NGO keeps editorial control." **Overlay: Draft, not auto-post — control stays human**

### 2:10–2:45 — Workflow 3: Field Proof
- **2:10** [Fevourd-K `/ngo/field` hub → open a field task, then `/ngo/field/app`.] "Out in the field, staff log tasks on the Fevourd-K field app." **Overlay: ③ Field Proof**
- **2:19** [Show GPS trail view `/ngo/field/sessions/{id}/trail` — map with plotted points.] "Each session captures a live GPS trail and completion evidence."
- **2:28** [Orchestrator queue `field-proof-queue` → Studio "Field Proof" running.] "The Field Proof agent verifies the session: was it active, does the GPS trail match the assigned site, is the task actually complete?"
- **2:38** [Fevourd-K field task badge flips to "Verified ✓".] "Verified evidence — written back to Fevourd-K as proof for funders." **Overlay: Verifiable proof, not self-reported**

### 2:45–3:25 — Workflow 4: Finance Claim
- **2:45** [Fevourd-K `/ngo/finance/claims` — a new expense claim with receipt, redacted amounts.] "A field expense claim comes in with a receipt." **Overlay: ④ Finance Claim**
- **2:55** [Orchestrator queue `finance-claim-queue` → Studio "Finance Claim".] "The Finance Claim agent checks the receipt, the budget line, and policy limits."
- **3:05** [UiPath Action Center: approval task with claim summary + Approve/Reject.] "Anything over the threshold is escalated — a finance officer approves or rejects in Action Center." **Overlay: Money decisions = always human**
- **3:15** [Approve → cut to Fevourd-K `/ngo/ledger` showing the new posted entry.] "On approval, the agent posts the entry to the NGO ledger automatically — claim to ledger, no manual re-keying." **Overlay: Approved → posted to ledger**

### 3:25–4:10 — Workflow 5: CSR Report
- **3:25** [Fevourd-K `/csr/dashboard` (Corporate CSR view, Acme CSR sandbox).] "Now the corporate funder. Acme CSR wants this quarter's impact report." **Overlay: ⑤ CSR Report**
- **3:35** [Orchestrator trigger (scheduled) → Studio "CSR Report" package; show Assets list with `fevourdk-api-base`, `fevourdk-api-token (sandbox)`.] "On a schedule, the CSR Report agent gathers verified field proof, approved spend, and compliance status from Fevourd-K."
- **3:48** [Show assembled report artifact / CSR dashboard tiles populating: SDG mapping, funds utilized, beneficiaries reached.] "It assembles one CSR-ready impact and compliance summary — every number traceable to a verified source."
- **4:00** [CSR dashboard "Report ready" state.] "What used to take a week of chasing teams is ready on demand." **Overlay: From a week of chasing → on demand**

### 4:10–4:45 — Architecture + Impact + Closing
- **4:10** [Simple architecture diagram slide (see overlay spec below).] "Here's the whole picture. Fevourd-K is the system of record and the human portal."
- **4:18** [Animate arrows: Maestro ↔ Orchestrator ↔ Studio agents ↔ Fevourd-K API.] "UiPath Maestro orchestrates. Orchestrator runs the queues and triggers. Studio agents do the work. Action Center holds the human approvals."
- **4:26** [Back to Fevourd-K dashboard, all six items now green/updated.] "Six workflows. Real read-and-write to the system of record. Humans only where judgment is required." **Overlay: Agentic = reads, decides, escalates, writes back — autonomously**
- **4:36** [End card.] "Fevourd-K plus UiPath ImpactOps Maestro — the agentic back-office for social impact." **Overlay: ImpactOps Maestro · UiPath AgentHack 2026**
- **4:44** [Logo lockup, fade.]

---

## 3) Screen Recording Shot List

| # | Time | Source | Capture | Notes |
|---|------|--------|---------|-------|
| S1 | 0:00–0:24 | Fevourd-K | `/ngo/dashboard` pan, half-second spreadsheet mock | Sandbox tenant; clean browser, no bookmarks bar |
| S2 | 0:25–0:54 | UiPath Maestro | ImpactOps Maestro process canvas; zoom nodes + human node + start trigger | Pre-arrange canvas so all 6 nodes are visible |
| S3 | 0:55–1:03 | Fevourd-K | `/ngo/documents` compliance docs | Use fake renewal doc with near-future expiry |
| S4 | 1:04–1:21 | UiPath Orchestrator + Studio | `compliance-review-queue` new item; Studio "Compliance Review" run; job Running→Successful | Pre-seed queue item |
| S5 | 1:22–1:34 | UiPath Action Center → Fevourd-K | Approval task → Approve → status "Reviewed" in Fevourd-K | Show write-back clearly |
| S6 | 1:35–1:53 | Fevourd-K + Studio | `/ngo/feed-studio` empty → Studio "Campaign Draft" → trigger | |
| S7 | 1:54–2:09 | Fevourd-K | Feed Studio composer fills with draft + "Review & schedule" click | Emphasize "DRAFT" badge |
| S8 | 2:10–2:27 | Fevourd-K | `/ngo/field` → `/ngo/field/app` → `/ngo/field/sessions/{id}/trail` map | Use seeded session with GPS points |
| S9 | 2:28–2:44 | UiPath + Fevourd-K | `field-proof-queue` → Studio "Field Proof" → task badge "Verified ✓" | |
| S10 | 2:45–2:54 | Fevourd-K | `/ngo/finance/claims` new claim + receipt thumbnail | Redact amount/last-4 |
| S11 | 2:55–3:14 | UiPath | `finance-claim-queue` → Studio "Finance Claim" → Action Center Approve/Reject | This is the marquee human-in-loop moment |
| S12 | 3:15–3:24 | Fevourd-K | `/ngo/ledger` new posted entry highlighted | Show claim→ledger linkage |
| S13 | 3:25–3:47 | Fevourd-K + UiPath | `/csr/dashboard` → scheduled trigger → Studio "CSR Report" → Assets list (sandbox token) | |
| S14 | 3:48–4:09 | Fevourd-K | CSR dashboard tiles populate (SDG, funds, reach) → "Report ready" | |
| S15 | 4:10–4:44 | Slide/editor | Architecture diagram animate + return to green dashboard + end card | Built in editor, not a live screen |

**Recording tips**
- Record each app at the same window size; hide OS clock/notifications.
- Pre-seed all queue items and Action Center tasks *before* recording so "Running → Successful" is fast (trim dead time in edit).
- Capture 2–3 takes of each Action Center approval click — it's the most important visual.
- Keep mouse movements slow and deliberate; the editor will speed up any waiting.

---

## 4) Exact Fevourd-K Screens To Show (real routes)

| Workflow | Screen | Route |
|----------|--------|-------|
| Intro | NGO dashboard | `/ngo/dashboard` |
| ① Compliance | Compliance/registration documents | `/ngo/documents` |
| ② Campaign | Feed / Campaign Studio | `/ngo/feed-studio` |
| ② Campaign (context) | Campaigns list | `/ngo/campaigns` |
| ③ Field Proof | Field ops hub | `/ngo/field` |
| ③ Field Proof | Field app (PWA) | `/ngo/field/app` |
| ③ Field Proof | GPS session trail | `/ngo/field/sessions/{session}/trail` |
| ④ Finance | Finance claims (approval seam) | `/ngo/finance/claims` |
| ④ Finance | Finance hub | `/ngo/finance` |
| ④ Finance | Ledger (write-back target) | `/ngo/ledger` |
| ⑤ CSR Report | Corporate CSR dashboard | `/csr/dashboard` |
| Public (optional B-roll) | Community feed / NGO microsite | `/feeds`, `/{ngo-slug}` |

> The finance flow is the cleanest agentic seam: claim created (`POST /ngo/finance/claims`) → decision (`PATCH /ngo/finance/claims/{claim}` = the `decide` action, i.e. the Action Center approval) → posted to `/ngo/ledger`. Show that chain end-to-end.

---

## 5) Exact UiPath Screens / Artifacts To Show

**Maestro**
- "ImpactOps Maestro Agentic Process" canvas — BPMN-style with the 6 workflow nodes + at least one human-approval (Action Center) node + start trigger.

**Studio**
- One package per workflow, named exactly:
  - `Compliance Review`
  - `Campaign Draft`
  - `Field Proof`
  - `Finance Claim`
  - `CSR Report`
- Show the project tree + a workflow diagram for at least one (Finance Claim recommended).

**Orchestrator** (folder `Fevourd-K-Demo`, tenant "AgentHack Sandbox")
- **Queues:** `compliance-review-queue`, `campaign-draft-queue`, `field-proof-queue`, `finance-claim-queue`, `csr-report-queue` — show items moving New → InProgress → Successful.
- **Assets:** `fevourdk-api-base` (text), `fevourdk-api-token` (credential, masked, labeled *sandbox*), `finance-approval-threshold` (e.g. ₹5,000).
- **Triggers:** event trigger for Compliance/Field/Finance; **time trigger** (cron) for CSR Report.

**Action Center (human-in-the-loop)**
- Compliance finding sign-off task.
- Finance Claim Approve/Reject task (the hero moment) — show form fields + Approve click.

**Sanitized payloads (optional lower-third / quick cut)**
- A redacted JSON request the Studio agent sends to Fevourd-K and the response it writes back — demonstrates true integration. Mask tokens and any IDs.

---

## 6) Callouts / Text Overlays For The Editor

> Build all overlays from `docs/agenthack-assets/video/lower-third.svg` so they stay on-brand. Colors are the official Fevourd-K palette (see §9): blue **#2563eb / #1d4ed8 / #1e3a8a** dominant, orange **#f97316** as the single accent for UiPath/agentic + section-number tiles, slate **#0f172a / #1e293b / #cbd5e1** for neutrals.

**Persistent tag (whole video, bottom-right):** dark slate pill (#0f172a @ 82%, blue hairline border), small text — `Fevourd-K = system of record · UiPath = agentic layer`, orange dot leading. Pulled straight from `lower-third.svg` (right element).

**Section chips (bottom-left, appear per workflow):** blue gradient pill (#1d4ed8→#1e3a8a) with an **orange #f97316 number tile**, "WORKFLOW" eyebrow over the section name. Swap the number glyph (①–⑤) + label per section in `lower-third.svg` (left element):
`① Compliance Review` · `② Campaign Draft` · `③ Field Proof` · `④ Finance Claim` · `⑤ CSR Report` · `Maestro orchestrates all`

**Key moment overlays (timed):**
- 0:16 — *The busywork is still manual →*
- 0:44 — *Read → Act → Escalate → Write back*
- 1:22 — *Human-in-the-loop ✓*
- 2:04 — *Draft, not auto-post — control stays human*
- 2:38 — *Verifiable proof, not self-reported*
- 3:05 — *Money decisions = always human*
- 3:15 — *Approved → posted to ledger*
- 4:26 — *Agentic = reads, decides, escalates, writes back — autonomously*
- 4:36 — *ImpactOps Maestro · UiPath AgentHack 2026*

**Architecture diagram (4:10 slide):** Cut to the pre-built full-frame graphic `docs/agenthack-assets/video/architecture-card.svg` (Fevourd-K ↔ Maestro ↔ Orchestrator / Studio Agents / Action Center, with read/write-back arrows and the six-workflow strip). The editor can animate the arrows on top. Structure for reference:
```
        ┌─────────────────────────────────────────────┐
        │   Fevourd-K (Laravel + Vue / Inertia)        │
        │   System of Record + Human Portal + API      │
        └───────────────▲──────────────────┬───────────┘
                        │ write back        │ read
                        │                   ▼
        ┌───────────────┴───────────────────────────────┐
        │   UiPath ImpactOps Maestro (Agentic Process)   │
        │   orchestrates the 6 workflows                 │
        └───┬───────────────┬───────────────────┬────────┘
            ▼               ▼                   ▼
     Orchestrator     Studio Agents      Action Center
     queues/triggers  (do the work)      (human approval)
     /assets
```
Caption under diagram: *Fevourd-K never loses control of the data. UiPath never makes the money decision alone.*

**Editor pacing notes**
- Trim every "loading"/"running" wait to < 2s.
- Use a soft "ding" SFX on each write-back to Fevourd-K (status flip) — reinforces the closed loop.
- Hold the Action Center Approve clicks ~1s longer than other clicks.
- Keep total under 4:50 in the timeline to leave buffer under the 5:00 cap.

---

## 7) Why This Is Agentic & Useful (judge-facing summary)

- **Agentic, not scripted:** each workflow *reads* live state from Fevourd-K, *decides* (policy checks, evidence verification), *escalates* only genuine judgment calls to a human, then *writes results back* — a closed perceive→act loop, coordinated by Maestro.
- **Human-in-the-loop where it counts:** money (Finance Claim) and compliance sign-off always route to a person via Action Center; everything else runs unattended.
- **System-of-record discipline:** Fevourd-K stays the single source of truth and the audit trail; UiPath orchestrates but never owns the data — important for NGO/CSR trust and compliance.
- **Real, measurable value:** collapses six recurring staff-days of cross-tool busywork (compliance chasing, campaign drafting, field verification, claim processing, report assembly) into supervised agent runs with full traceability.

---

## 8) Branded Asset Cut-In Map (editor)

All assets live in `docs/agenthack-assets/video/` (1920×1080, self-contained, real logo embedded). PNG exports sit next to each SVG.

| Time | Asset | Use |
|------|-------|-----|
| 0:00–0:05 | `title-card.svg` | Hold ~3s under the opening hook, then dissolve to the live `/ngo/dashboard`. |
| Per workflow (0:55, 1:35, 2:10, 2:45, 3:25) | `lower-third.svg` | Section chip in (slide from left), swap number + label; persistent tag stays bottom-right the whole video. |
| 4:10–4:25 | `architecture-card.svg` | Full-frame cut; optionally animate the read / write-back arrows. |
| 4:36–4:44 | `end-card.svg` | Closing CTA; hold to fade. Fill in the GitHub / Demo / Sandbox link text before export. |

---

## 9) Production & Branding Notes

**Palette (official Fevourd-K — use these exact hex, do NOT use teal):**
- Blue (dominant, 60–70%): `#2563eb` · `#1d4ed8` · `#1e3a8a`; light `#dbeafe` / `#eff6ff`
- Orange (single accent — UiPath / agentic / human-gate / section numbers): `#f97316` · `#ea580c`; light `#ffedd5`
- Slate (neutrals / dark bg): `#0f172a` · `#1e293b` · `#334155` · `#64748b` · `#cbd5e1` · `#f1f5f9`; paper `#ffffff`
- Write-back "success" green (arrows/badges only): `#34d399`

**Logo:** `public/assets/images/fevourd-k/logo.png` (234×240). The mark sits on a near-white background, so on any dark frame place it inside a **white rounded chip** (as in the title/end/architecture cards). Never put the raw PNG straight on slate.

**Type:** SVG cards use web-safe fonts so they render anywhere — **Georgia** (serif) for the big "ImpactOps Maestro" / headline lockups, **Helvetica/Arial** for everything else. If the editor re-typesets in After Effects/Premiere, match with **Fraunces** (or Georgia) for display and **Inter** (or Helvetica/Arial) for UI/captions.

**Lower-third style:** blue gradient pill + orange number tile for section chips; dark slate translucent pill for the persistent tag; 6px drop shadow (`#0f172a` @ 45%) so overlays read over busy app screens. Keep the persistent tag on-screen for the whole runtime.

**SFX / audio:** soft synth **"write-back ding"** on every status flip back into Fevourd-K (compliance Reviewed, draft scheduled, field Verified, ledger posted) — it audibly closes the loop. One slightly heavier **"approve" thunk** on each Action Center Approve click (compliance + finance). Light, neutral underscore bed at ~ -22 LUFS; voiceover ducks the bed by ~6 dB. Target integrated loudness **-14 LUFS** for web.

**Safe zones (1920×1080):** keep all text inside a 5% margin (96px each side → 1728×972 title-safe). Lower-thirds already sit inside this; the cards include a faint safe-zone frame guide. Captions should never overlap the bottom 130px where the lower-third lives — place burned-in subtitles just above it.

**Captions / subtitles:** burn-in or sidecar `.srt` for accessibility and silent autoplay (judges often scrub muted). White text, slate `#0f172a` @ 70% rounded background, Helvetica/Arial ~32px, bottom-center but **above** the persistent tag. Mirror the voiceover verbatim; keep lines ≤ 2 rows.

**Export:** 1920×1080, 60fps (or 30fps if file size matters), H.264 high profile, ~12–16 Mbps, AAC 320kbps audio. Keep the final timeline **≤ 4:50** to stay safely under the 5:00 cap.

**Public-safety pass before export:** confirm sandbox labels only ("Vikasana (Demo Tenant)", "Acme CSR (Sandbox)"), OTP `1234`, masked API-token asset, redacted amounts/last-4, address bar shows `localhost:8080` or a sandbox URL — no `.env`, FTP/DB/payment/social tokens, or production host on any frame.
