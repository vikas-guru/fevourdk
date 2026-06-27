# UiPath ↔ Fevourd-K — Sanitized Sample Payloads

Demo-tenant payloads showing how each UiPath workflow reads from and writes back to
Fevourd-K. **Public-safe:** fake IDs, fake amounts, redacted PII, sandbox token (masked).

Auth header on every call (value masked): `Authorization: Bearer ****sandbox****`

---

## ① Compliance Review

**Read** — `GET /api/ngo/documents?ngo_id=VK-DEMO-001`
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

**Read** — `GET /api/ngo/campaigns/clean-water-mandya`
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

**Read** — `GET /api/ngo/field/sessions/sess_4471/trail`
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

**Read** — `GET /api/ngo/finance/claims/claim_2207`
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

**Read** (aggregate) — `GET /api/csr/impact?corporate_id=ACME-CSR&period=2026-Q2`
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
