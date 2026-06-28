# ImpactOps Maestro — Judge Quickstart

**Fevourd-K × UiPath · AgentHack 2026**
*Agentic back-office for NGOs & CSR — read, act, escalate, write back.*

> **60-second orientation.** Fevourd-K is the **system of record** (the NGO/CSR platform). **UiPath ImpactOps Maestro** is the **agentic layer** on top of it: an orchestrator agent that coordinates five specialist agents across repeatable back-office workflows, defers every money/compliance call to a human, and writes every result back into Fevourd-K — with a full audit trail.

> 📄 **Polished judge PDF:** [`docs/ImpactOps-Maestro-Judge-Pack.pdf`](docs/ImpactOps-Maestro-Judge-Pack.pdf) — product + live UiPath-tenant screenshots, the agentic loop, and an honest map of what is live vs. designed next.

---

## What to look at first (in order)

| # | Link | Why it matters |
|---|------|----------------|
| 1 | **Demo video** *(YouTube, 4:54)* | The whole story end-to-end with narration. Start here. |
| 2 | **Live sandbox** → https://fevourdk.online/ | The real product judges can click, signed-out. |
| 3 | **Live agent API** → `https://fevourdk.online/api/health` | The token-guarded API UiPath calls. Returns **200 with token, 401 without** — it's really deployed. |
| 4 | [`docs/agenthack-orchestration.md`](docs/agenthack-orchestration.md) | Six worked examples + end-to-end diagram + live `curl` for each workflow. |
| 5 | [`uipath/orchestrator-manifest.md`](uipath/orchestrator-manifest.md) + [`uipath/sample-payloads.md`](uipath/sample-payloads.md) | The concrete UiPath config: folder, queues, assets, triggers, and request/response payloads. |
| 6 | [`docs/agenthack-e2e-verification.md`](docs/agenthack-e2e-verification.md) | Proof the live API + product run clean (status codes, no 500s). |

---

## UiPath components — what's published in our tenant

All of the following are **published in the live `cloud.uipath.com/fevourdk` tenant** (screenshots in the PDF, §03):

| Component | State | Role in ImpactOps Maestro |
|-----------|-------|---------------------------|
| **ImpactOps Maestro** (UiPath Studio agentic process) | ✅ Published | Orchestrator agent + Agentic Process (`Process.bpmn`). Coordinates a public-safe NGO operations case by choosing the next specialist agent or human task — and never approves NGOs, publishes campaigns, marks field work complete, pays claims, or publishes reports directly. |
| **5 specialist agents** (UiPath Studio) | ✅ Published | `Compliance Review Agent`, `Campaign Draft Agent`, `Field Proof Agent`, `Finance Claim Agent`, `CSR Report Agent`. Each reads Fevourd-K state and is prompt-constrained to defer money/compliance to a human (`human_review_required`, no direct action). |
| **Fevourd-K API Workflow** (UiPath API Workflow) | ✅ Published | HTTP Request (with retry-on-failure) — how agents read live Fevourd-K state over the token-guarded API. |
| **UiPath Action Center** human-task UI | 🟡 Designed next | Gate logic lives in every specialist's prompt + output contract today; wiring those into Action Center sign-off / approve-reject tasks is the next integration step. |
| **UiPath Orchestrator** queues + event/cron triggers | 🟡 Designed next | Specified in [`uipath/orchestrator-manifest.md`](uipath/orchestrator-manifest.md). |

**Agent Type:** **Low-code Agents** — UiPath Studio agentic processes + an API Workflow, coordinated by a Maestro orchestrator agent, with human-in-the-loop encoded in every specialist. *(No Coded Agents.)*

---

## The six workflows (the agentic loop)

Each workflow runs the same loop: **perceive → act → escalate (only when needed) → write back.**

1. **Compliance Review** — pulls NGO 12A/80G renewal docs, checks expiry/fields/status → routes a clean summary to a compliance officer for **sign-off** → writes status back.
2. **Campaign Draft** — reads latest campaign + field progress → assembles a donor-ready update as a **draft** (never auto-posted) in Feed Studio.
3. **Field Proof** — verifies a field session's GPS trail against the assigned site + completion → marks it **Verified** (checkable proof, not self-reported).
4. **Finance Claim** — checks an expense claim against budget line + policy → **escalates over-threshold claims** to Action Center → posts approved claims to the NGO ledger.
5. **CSR Report** — on a schedule, gathers verified proof + approved spend + compliance status into one **CSR-ready summary**, every number traceable to a verified source.
6. **ImpactOps Maestro process** — orchestrates the end-to-end handoffs and human approvals.

---

## The principle judges should take away

> **Fevourd-K never loses control of the data. UiPath never decides the money alone.**

Money (Finance Claim) and compliance sign-off are **mandatory human approvals** via Action Center. Lower-risk steps run unattended. "Agentic" here means *removing recurring operational drudgery while strengthening the audit trail* — not unsupervised autonomy where it matters.

---

## Reproducibility

- **Run it:** see the repo `README.md` → Setup. Local API base `http://127.0.0.1:8080/api`; live base `https://fevourdk.online/api` (Bearer token, masked in all docs as `****`).
- **Try the API:** copy any `curl` from [`docs/agenthack-orchestration.md`](docs/agenthack-orchestration.md).
- **Public-safety:** everything runs on a **sandbox tenant** — fake NGOs/amounts, masked tokens, redacted PII. Nothing sensitive ships.

---

## Team

| Member | Role |
|--------|------|
| **Vikas Chandra Guru** | Architect |
| **Kavan Kumar KR** | Senior Full-Stack Developer |
| **Nithin Annaiah** | Senior Full-Stack Developer |
| **Hemanth Gowda AP** | Tester / QA |

*Platform: Laravel 12 + Inertia + Vue 3 + MySQL. Agentic layer: UiPath Maestro · Studio · Orchestrator · Action Center.*
