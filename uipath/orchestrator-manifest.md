# UiPath ImpactOps Maestro — Orchestrator & Maestro Manifest

Public-safe reference for the UiPath side of the Fevourd-K AgentHack submission.
All names below are the demo-tenant configuration. **No real tokens or endpoints** — the
credential asset holds a sandbox value and is shown masked in every screenshot.

- **Tenant:** `AgentHack Sandbox`
- **Folder:** `Fevourd-K-Demo`
- **System of record:** Fevourd-K (Laravel/Vue) — UiPath reads from and writes back to its JSON API.

---

## 1. Maestro — "ImpactOps Maestro" Agentic Process

A long-running Maestro process that coordinates the six workflows and the human approvals.

| Node | Type | Does |
|------|------|------|
| `Start / Ops request` | Trigger | Fires on a Fevourd-K event or schedule |
| `Compliance Review` | Agent task | Validates NGO documents → Action Center sign-off |
| `Campaign Draft` | Agent task | Drafts a donor/CSR update (never auto-posts) |
| `Field Proof` | Agent task | Verifies GPS trail + completion |
| `Finance Claim` | Agent task | Policy check → Action Center approval → ledger post |
| `CSR Report` | Agent task | Assembles the CSR impact + compliance summary |
| `Human approval` | Action Center | Compliance sign-off & finance approve/reject gates |
| `Write back` | Activity | Posts results into Fevourd-K (status, draft, badge, ledger, report) |

> Maestro **orchestrates**; it never owns the data. Every result is written back into
> Fevourd-K so the audit trail stays in one system.

---

## 2. Studio packages (one per workflow)

Base URL (live): `https://fevourdk.online/api` · local fallback `http://127.0.0.1:8080/api`.
All reads are the real, token-guarded endpoints below; writes go through the authenticated app routes.

| Package | Reads from Fevourd-K (live endpoint) | Writes back |
|---------|----------------------|-------------|
| `Compliance Review` | `GET /api/ngo/vikasana/documents` | doc status → `Reviewed` (app route) |
| `Campaign Draft` | `GET /api/ngo/vikasana/campaigns/{slug}` | draft post in Feed Studio (app route) |
| `Field Proof` | `GET /api/field/sessions/{id}/trail` | task badge → `Verified` (app route) |
| `Finance Claim` | `GET /api/ngo/vikasana/finance/claims` | claim decide → ledger entry (app route) |
| `CSR Report` | `GET /api/ngo/vikasana/csr/impact` (aggregate) | CSR report artifact (app route) |
| `Health probe` | `GET /api/health` | — |

---

## 3. Orchestrator — Queues

| Queue | Trigger source | Typical item |
|-------|----------------|--------------|
| `compliance-review-queue` | document uploaded | `{ ngo_id, document_ids[] }` |
| `campaign-draft-queue` | campaign milestone | `{ campaign_id }` |
| `field-proof-queue` | field session ended | `{ session_id }` |
| `finance-claim-queue` | claim submitted | `{ claim_id, amount }` |
| `csr-report-queue` | scheduled (quarterly) | `{ corporate_id, period }` |

## 4. Orchestrator — Assets

| Asset | Type | Value (sandbox) |
|-------|------|-----------------|
| `fevourdk-api-base` | Text | `https://fevourdk.online/api` (live) — local fallback `http://127.0.0.1:8080/api` |
| `fevourdk-api-token` | Credential | **masked** — `Bearer ****` (env `UIPATH_AGENT_TOKEN`) |
| `finance-approval-threshold` | Integer | `5000` (₹) |
| `csr-report-schedule` | Text | `cron(0 6 1 */3 *)` |

## 5. Orchestrator — Triggers

| Trigger | Kind | Bound to |
|---------|------|----------|
| `on-document-uploaded` | Queue/event | `compliance-review-queue` |
| `on-session-ended` | Queue/event | `field-proof-queue` |
| `on-claim-submitted` | Queue/event | `finance-claim-queue` |
| `csr-report-quarterly` | Time (cron) | `csr-report-queue` |

## 6. Action Center — Human-in-the-loop tasks

| Task | Gate | Form fields | Outcomes |
|------|------|-------------|----------|
| `Compliance finding — sign-off` | Compliance | NGO, document, finding, recommendation | Approve / Request fix |
| `Finance claim — approve/reject` | Finance > threshold | Claimant, amount, budget line, receipt, policy check | Approve / Reject |

These two gates are **mandatory**: money and compliance decisions always route to a person.
Everything else runs unattended on queues + triggers.

See [sample-payloads.md](sample-payloads.md) for the sanitized request/response bodies.
