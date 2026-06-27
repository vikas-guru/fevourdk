# UiPath Sandbox Screenshots — Capture Checklist

Drop the PNGs you capture from your UiPath Cloud sandbox into **this folder**
(`uipath/screenshots/`), using the exact filenames below. The list is derived from
[`../orchestrator-manifest.md`](../orchestrator-manifest.md).

> **Before you capture:** mask the credential value everywhere. The
> `fevourdk-api-token` asset must be shown **masked** in every screenshot. Use only the
> demo tenant (`AgentHack Sandbox`, folder `Fevourd-K-Demo`) and sandbox data.

## Capture list

| # | Save as | What to show |
|---|---------|--------------|
| 1 | `01-maestro-impactops-canvas.png` | Maestro — the **"ImpactOps Maestro"** agentic process canvas (the full node graph: Start → Compliance Review → Campaign Draft → Field Proof → Finance Claim → CSR Report → Human approval → Write back). |
| 2 | `02-studio-project-tree.png` | Studio — the project/solution tree showing the **5 packages**: Compliance Review, Campaign Draft, Field Proof, Finance Claim, CSR Report. |
| 3 | `03-orchestrator-queues.png` | Orchestrator — **Queues** list: `compliance-review-queue`, `campaign-draft-queue`, `field-proof-queue`, `finance-claim-queue`, `csr-report-queue`. |
| 4 | `04-orchestrator-assets.png` | Orchestrator — **Assets**: `fevourdk-api-base`, `fevourdk-api-token` (**MASKED**), `finance-approval-threshold`, `csr-report-schedule`. |
| 5 | `05-orchestrator-triggers.png` | Orchestrator — **Triggers**: `on-document-uploaded`, `on-session-ended`, `on-claim-submitted`, `csr-report-quarterly` (folder `Fevourd-K-Demo`). |
| 6 | `06-action-center-compliance-signoff.png` | Action Center — the **Compliance finding — sign-off** task (Approve / Request fix). |
| 7 | `07-action-center-finance-approval.png` | Action Center — the **Finance claim — approve/reject** task (Approve / Reject). |

## Optional (bonus credibility)

| # | Save as | What to show |
|---|---------|--------------|
| 8 | `08-agent-api-call.png` | A UiPath activity / run calling the live Fevourd-K read-only API (e.g. `GET /api/health` or `/api/ngo/vikasana/finance/claims`) with the bearer token **masked**. |
| 9 | `09-process-export.png` | (If available) the exported `.nupkg` / process export view. |

## Final check

After dropping the PNGs in, re-run the secret scan and eyeball every image: **no token,
no real PII, no live endpoint visible.** Masked values only.
