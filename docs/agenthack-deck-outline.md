# Presentation Deck Outline — Fevourd-K × UiPath "ImpactOps Maestro"

**Use:** AgentHack judging deck (the "Deck" link in the submission checklist).
**Length:** 12 slides, ~3–4 min if presented. Mirrors the demo video so they reinforce each other.
**Design:** dark canvas, one accent color, big numbers, minimal text per slide. Public-safe (sandbox only).

---

### Slide 1 — Title
- **ImpactOps Maestro**
- Subtitle: *Agentic back-office for NGOs & CSR — built on UiPath, powered by Fevourd-K*
- Footer: UiPath AgentHack 2026 · [Team name]
- Visual: Fevourd-K + UiPath logo lockup.

### Slide 2 — The Problem
- Headline: **The system is modern. The operations are still manual.**
- 5 pain bullets (icons): compliance chasing · campaign drafting · field verification · expense claims · CSR reporting.
- Stat callout: *~6 recurring staff-days/month of cross-tool busywork.*

### Slide 3 — The Insight
- Headline: **These are rules-based chores with occasional judgment calls.**
- One line: *That's the exact shape of agentic automation — automate the repeatable, escalate the rest.*

### Slide 4 — The Solution (one-liner)
- Headline: **Fevourd-K = system of record. UiPath ImpactOps Maestro = agentic layer.**
- The loop, big: **Read → Act → Escalate → Write back.**

### Slide 5 — Architecture (the trust story)
- Diagram: Fevourd-K (record + portal + API) ↔ Maestro (orchestrates) → Orchestrator (queues/triggers/assets) + Studio agents (do work) + Action Center (human approval).
- Caption: *Fevourd-K never loses control of the data. UiPath never makes the money decision alone.*

### Slide 6 — The Six Workflows (overview grid)
- 2×3 grid, one chip each: ① Compliance Review · ② Campaign Draft · ③ Field Proof · ④ Finance Claim · ⑤ CSR Report · ⑥ Maestro orchestrates all.
- Note: "Maestro coordinates handoffs + human approvals across all six."

### Slide 7 — Spotlight: Finance Claim (the hero flow)
- Headline: **Money decisions are always human.**
- 3-step strip: Claim + receipt (Fevourd-K) → Approve/Reject (UiPath Action Center) → Posted to ledger (Fevourd-K).
- Caption: *Real bidirectional integration, end to end — no manual re-keying.*

### Slide 8 — Spotlight: Field Proof (verifiable, not narrated)
- Headline: **Proof, not self-reporting.**
- Visual: GPS trail map → "Verified ✓" badge.
- Caption: *Agent checks active session, GPS trail vs. site, completion — then marks it verified for funders.*

### Slide 9 — Human-in-the-loop
- Headline: **Agentic ≠ unsupervised.**
- Two mandatory approval gates: Compliance sign-off · Finance Approve/Reject (Action Center).
- Everything else runs unattended on queues + triggers.

### Slide 10 — Built With
- UiPath: Maestro · Studio · Orchestrator · Action Center.
- Fevourd-K: Laravel 12 · Inertia · Vue 3 · MySQL · Tailwind.
- Note: per-workflow queues, sandbox API-token asset, finance-threshold asset, event + cron triggers.

### Slide 11 — Impact & What's Next
- Impact: *6 staff-days of busywork → supervised agent runs with a full audit trail.*
- Next: more workflows (donor reconciliation, grant milestones, vendor verification) · multi-NGO tenancy · confidence scoring · in-app trust dashboard.

### Slide 12 — Close
- **ImpactOps Maestro — the agentic back-office for social impact.**
- Links: GitHub · Demo video · Live sandbox.
- Footer: UiPath AgentHack 2026.

---

## Speaker notes (condensed)
- Open on the problem, not the tech — judges feel the 6 staff-days.
- Say "system of record" and "human never out of the money loop" explicitly; that's the differentiator.
- Spend the most time on Slides 7–9 (Finance, Field Proof, human-in-loop) — that's where "agentic + trustworthy" is proven.
- End on impact + audit trail, not feature count.

## Public-safety reminders for slide screenshots
- Sandbox tenant labels only ("Vikasana (Demo Tenant)", "Acme CSR (Sandbox)").
- Redact amounts/last-4; mask the API-token asset; blur any real-looking PII.
- No `.env`, FTP/DB/payment/social tokens, or production host details on any slide.
