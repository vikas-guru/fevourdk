# UiPath AgentHack Artifacts

This folder is the public manifest for the UiPath AgentHack submission.

## Agentic Process

**ImpactOps Maestro Agentic Process** coordinates the Fevourd-K NGO/CSR operations workflows and keeps Fevourd-K as the system of record.

**API base URL:** `https://fevourdk.online/api` (live, after the API deploy — see
`docs/agenthack-deploy-api-to-prod.md`) · local fallback `http://127.0.0.1:8080/api`.
Bearer-token guarded (shown masked everywhere as `Bearer ****`; env `UIPATH_AGENT_TOKEN`).
Full worked walkthrough: `docs/agenthack-orchestration.md`.

## Solutions To Export

1. Compliance Review
2. Campaign Draft
3. Field Proof
4. Finance Claim
5. CSR Report
6. ImpactOps Maestro Agentic Process

## Expected UiPath Evidence

- UiPath Maestro process export or screenshots.
- Studio project/package exports for each automation.
- Orchestrator queue, asset, trigger, and folder names.
- Action Center or human approval screenshots for compliance and finance decisions.
- Sanitized sample payloads showing Fevourd-K API inputs and outputs.

## Public Safety

Only include demo tenant data, fake NGO/corporate records, sandbox tokens, and redacted screenshots. Do not include production `.env` files, FTP credentials, real account exports, real beneficiary information, or live social access tokens.
