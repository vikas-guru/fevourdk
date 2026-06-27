# UiPath ↔ Fevourd-K — Sanitized Sample Payloads

Demo-tenant payloads showing how each UiPath workflow reads from and writes back to
Fevourd-K. **Public-safe:** fake IDs, fake amounts, redacted PII, sandbox token (masked).

**Base URL:** `https://fevourdk.online/api` (live, after the API deploy — see
`docs/agenthack-deploy-api-to-prod.md`) · local fallback `http://127.0.0.1:8080/api`.

Auth header on every call (value masked): `Authorization: Bearer ****`

> **These are live, built endpoints.** Fevourd-K exposes a read-only, token-guarded agent
> API (`routes/api.php`, guarded by `VerifyUipathToken`). UiPath agents **read** state from it;
> **writes** (claim decisions, ledger posts, doc reviews) go through the existing authenticated
> app routes so the human-in-the-loop and audit trail stay intact. Verified against the seeded
> `vikasana` demo NGO. Real paths:
>
> | Purpose | Live endpoint |
> |---|---|
> | Documents | `GET /api/ngo/vikasana/documents` |
> | Campaign | `GET /api/ngo/vikasana/campaigns/clean-drinking-water-for-mandya-villages` |
> | Claims | `GET /api/ngo/vikasana/finance/claims` |
> | CSR impact | `GET /api/ngo/vikasana/csr/impact` |
> | Field trail | `GET /api/field/sessions/{id}/trail` |
> | Health | `GET /api/health` |
>
> The JSON shapes below are illustrative; the live responses carry the same fields from the seeded sandbox data.

---

## ① Compliance Review

**Read** — `GET https://fevourdk.online/api/ngo/vikasana/documents`
```json
{ "documents": [
  { "id": "doc_12a", "type": "registration_certificate", "status": "verified", "expires_on": "2027-03-31" },
  { "id": "doc_80g", "type": "80g_certificate",          "status": "verified", "expires_on": "2026-09-30" },
  { "id": "doc_fcra","type": "fcra_certificate",         "status": "pending",  "expires_on": "2026-07-15" }
] }
```
**Agent finding** → Action Center → **Write back** `PATCH /api/ngo/documents/doc_fcra`
```json
{ "review_status": "Reviewed", "finding": "FCRA certificate expires in 17 days — renew before 2026-07-15.",
  "reviewed_by": "compliance.officer@demo", "source": "ImpactOps Maestro" }
```

## ② Campaign Draft

**Read** — `GET https://fevourdk.online/api/ngo/vikasana/campaigns/clean-drinking-water-for-mandya-villages`
```json
{ "title": "Clean Drinking Water for Mandya Villages", "raised": 512000, "target": 800000,
  "field_progress": { "villages_covered": 12, "borewells": 3 } }
```
**Write back (draft only)** — `POST /api/ngo/feed?status=draft`
```json
{ "status": "draft", "body": "63% funded! ₹5.12L raised toward clean water for Mandya — 12 villages reached, 3 borewells live. #CleanWater #Vikasana",
  "requires_human_review": true, "source": "ImpactOps Maestro" }
```

## ③ Field Proof

**Read** — `GET https://fevourdk.online/api/field/sessions/4471/trail`
```json
{ "session_id": "sess_4471", "assigned_site": "Kyathanahalli, Mandya",
  "active": true, "points": 38, "start": "09:12", "end": "11:40", "completed": true }
```
**Write back** — `PATCH /api/ngo/field/tasks/task_889`
```json
{ "proof_status": "Verified", "reason": "Active session; GPS trail (38 pts) matches assigned site; task complete.",
  "source": "ImpactOps Maestro" }
```

## ④ Finance Claim  ← the bidirectional proof point

**Read** — `GET https://fevourdk.online/api/ngo/vikasana/finance/claims` (then select `claim_2207`)
```json
{ "id": "claim_2207", "claimant": "M. C. Guru", "amount": 4250, "category": "travel",
  "budget_line": "field-operations", "receipt": "rcpt_2207.jpg", "status": "submitted" }
```
**Escalate** (amount ≥ `finance-approval-threshold` = 5000? No → still routed for this demo) → Action Center decision:
```json
{ "task": "finance-claim-approve", "decision": "approved", "approver": "finance.officer@demo" }
```
**Write back** — `PATCH /api/ngo/finance/claims/claim_2207` → posts ledger entry
```json
{ "decision": "approved",
  "ledger_entry": { "type": "debit", "category": "expense_claim", "amount": 4250,
    "description": "AgentHack demo: approved field travel claim posted by Finance Claim agent",
    "balance_after": 1475750 },
  "source": "ImpactOps Maestro" }
```

## ⑤ CSR Report

**Read** (aggregate) — `GET https://fevourdk.online/api/ngo/vikasana/csr/impact`
```json
{ "verified_field_sessions": 12, "approved_spend": 1475750, "compliance": "1 item due",
  "beneficiaries": 1240, "sdg": ["SDG 6"] }
```
**Write back** — `POST /api/csr/reports`
```json
{ "period": "2026-Q2", "beneficiaries": 1240, "sdg": ["SDG 6"], "funds_tracked": 1475750,
  "status": "ready", "traceable": true, "source": "ImpactOps Maestro" }
```

---

**Why this matters:** every agent action is a real read + a real write back into Fevourd-K,
with the two money/compliance decisions gated by a human. Fevourd-K stays the system of record
and the audit trail; UiPath is the agentic operations layer.

---

## How UiPath calls the live API (real curl)

Token shown masked. Set the real token in the Orchestrator credential asset / `UIPATH_AGENT_TOKEN`.

```bash
BASE="https://fevourdk.online/api"   # local fallback: http://127.0.0.1:8080/api
TOKEN="****"                          # never commit the real value

# 0) Health
curl -s -H "Authorization: Bearer $TOKEN" "$BASE/health"

# 1) Compliance Review — documents
curl -s -H "Authorization: Bearer $TOKEN" "$BASE/ngo/vikasana/documents"

# 2) Campaign Draft — campaign
curl -s -H "Authorization: Bearer $TOKEN" \
  "$BASE/ngo/vikasana/campaigns/clean-drinking-water-for-mandya-villages"

# 3) Finance Claim — claims
curl -s -H "Authorization: Bearer $TOKEN" "$BASE/ngo/vikasana/finance/claims"

# 4) CSR Report — aggregate impact
curl -s -H "Authorization: Bearer $TOKEN" "$BASE/ngo/vikasana/csr/impact"

# 5) Field Proof — GPS trail for a session
curl -s -H "Authorization: Bearer $TOKEN" "$BASE/field/sessions/4471/trail"
```

A missing/invalid token returns `401 {"error":"unauthorized"}`. Until the API is deployed to prod,
`https://fevourdk.online/api/health` returns `404` — use the local base until then.
