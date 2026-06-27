# Enterprise & scale — Fevourd-K × ImpactOps Maestro

> How Fevourd-K and the ImpactOps Maestro agentic layer scale from a single NGO to a
> multi-tenant CSR program — and what that demands of identity, governance, and deployment.
>
> This document is honest about **what exists today** vs. **what is on the roadmap**. Items are
> tagged **`[Now]`** (in the product), **`[Near]`** (designed, next milestone), or
> **`[Roadmap]`** (planned, scoped).

Fevourd-K is the **system of record** — campaigns, field proof, finance claims, immutable ledgers,
compliance documents. ImpactOps Maestro (UiPath Maestro + Studio agents + Action Center) is the
agentic back-office that reads through a guarded read-only API and writes back only through
human-approved, audited app routes. Every enterprise capability below is framed against that boundary:
**the platform never holds money, and money/compliance decisions are always human-owned.**

---

## 1. Multi-NGO tenancy

- **`[Now]`** Each NGO is a first-class tenant with its own campaigns, ledger, documents, field
  sessions, and **its own payment gateway** — funds never pool through the platform.
- **`[Now]`** Per-NGO white-label microsites (see §5) and per-NGO demo/showcase data
  (`VikasanaShowcaseSeeder` is the reference tenant).
- **`[Near]`** Per-NGO **UiPath Orchestrator folders**: each tenant gets an isolated folder with its
  own queues, assets, triggers, and **approval routing**, so one NGO's agents and approvers never see
  another's work. Mapped 1:1 to the tenant in Fevourd-K.
- **`[Roadmap]`** Tenant-group hierarchy for state bodies / CSR programs that oversee many NGOs, with
  rolled-up dashboards and cross-tenant compliance views.

---

## 2. Identity & access

- **`[Now]`** **8-role RBAC** via Spatie Laravel Permission: Super Admin, State Admin, NGO Admin,
  NGO Staff, Corporate CSR Manager, Donor, Vendor, Volunteer — with permission-gated features.
- **`[Now]`** OTP-based demo login; standard authenticated app routes carry all write actions.
- **`[Near]`** **Scoped admins** — the role model extended so an admin's authority is bounded to a
  tenant (or tenant group), enabling delegated administration without Super Admin reach.
- **`[Roadmap]`** **SSO via SAML 2.0 / OIDC** for corporate CSR teams and large NGOs (Okta, Azure AD,
  Google Workspace), with enforced MFA at the IdP.
- **`[Roadmap]`** **SCIM 2.0 provisioning** — automated joiner/mover/leaver lifecycle so corporate
  directory changes flow into role assignments and revoke access on offboarding.

---

## 3. Governance & audit

- **`[Now]`** **Immutable ledger** — approved finance claims post to an append-only NGO ledger with a
  running balance; the read-only API exposes the latest balance but never mutates it.
- **`[Now]`** **Full audit trail** — every write (claim decision, ledger post, document review) goes
  through authenticated app routes, so each action is attributable to a real user.
- **`[Now]`** **Agent writes are not a bypass** — UiPath agents cannot write to the database. Their
  output is staged and committed only through the same audited routes a human staffer uses, so the
  trail records *who* approved, not just *that* an agent ran.
- **`[Near]`** **Compliance exports** aligned to Indian NGO/CSR regimes:
  - **FCRA** — foreign-contribution receipts and utilization summaries.
  - **80G** — donation receipts and donor statements for tax deduction.
  - **CSR-2 / MCA-21** — corporate CSR spend and impact reporting for the Ministry of Corporate Affairs.
- **`[Roadmap]`** Tamper-evidence (hash-chained ledger entries) and signed export bundles for auditors.

---

## 4. Human-in-the-loop at scale

The core discipline: **only money and compliance escalate to a human.** Everything else is staged for a
quick review. At scale, that needs structure:

- **`[Now]`** Escalation boundary is enforced in the workflow design — finance claims and compliance
  status changes always route to a person; drafting/reporting is staged.
- **`[Near]`** **Action Center** as the approval surface — approvers see a queue of agent-prepared
  decisions with the evidence the agent read, approve/reject in one place, and the decision writes back
  through Fevourd-K.
- **`[Near]`** **Agent confidence scoring** — each agent output carries a confidence score; a per-tenant
  **auto-approve threshold** lets low-risk, high-confidence drafting proceed while anything below the
  threshold (or any money/compliance action regardless of confidence) requires a human.
- **`[Roadmap]`** **Trust dashboard** — a single view of *every* agent action: what it read, what it
  proposed, its confidence, who approved or rejected it, and when. The accountability ledger for the
  agentic layer.

---

## 5. White-label & localization

- **`[Now]`** **White-label NGO microsites** using the canonical template, swapping in each NGO's
  branding and content (Vikasana is the live reference site).
- **`[Now]`** **Bilingual EN ⇄ ಕನ್ನಡ (Kannada)** on the public microsite, with a per-field translation
  model and Noto Kannada typography — the foundation for localization at scale.
- **`[Near]`** **Subdomain** per NGO (`ngo-name.fevourdk.online`) and **custom domain** support with
  managed TLS.
- **`[Roadmap]`** Additional Indian languages via the same i18n engine; locale-aware compliance exports.

---

## 6. Deployment options

| Option | Notes |
|---|---|
| **Managed cloud** `[Now]` | Live at [fevourdk.online](https://fevourdk.online). Standard LAMP-style hosting; Laravel 12 + MySQL. |
| **Private cloud** `[Roadmap]` | Customer VPC (AWS/Azure/GCP); Fevourd-K + MySQL + Redis + queue workers; UiPath in customer or UiPath Automation Cloud. |
| **On-premises** `[Roadmap]` | Full on-prem for data-sovereignty-sensitive NGOs/government bodies; on-prem UiPath Orchestrator. |
| **Data residency** `[Roadmap]` | India-region hosting for FCRA/CSR data; configurable per tenant. |

UiPath sits alongside any of these: agents call the Fevourd-K read-only API over the network and write
back through the app, so the agentic layer is deployment-topology agnostic.

---

## 7. Integrations

- **`[Roadmap]`** **Accounting** — push approved ledger entries to **Tally**, **Zoho Books**, or **SAP**
  so finance teams keep their books of record.
- **`[Now]`** **Per-NGO payment gateways** — each NGO connects its own gateway; the platform never pools
  funds. Additional gateway providers added per tenant need.
- **`[Roadmap]`** **Donor CRM** — sync donor and contribution data to CRMs (e.g. Salesforce NPSP,
  Zoho CRM) for stewardship and recurring-giving programs.

---

## 8. Reliability & security

- **`[Now]`** **Queue workers** for async/job processing; **bearer-token-guarded** read-only agent API.
- **`[Now]`** Standard web hardening — input validation, CSRF protection, parameterized queries,
  encryption in transit (TLS); secrets via environment config (e.g. `services.uipath.token`).
- **`[Near]`** **Rate limiting** and per-tenant API quotas on the agent API; encryption at rest for
  sensitive fields.
- **`[Roadmap]`** **Observability** — structured logging, metrics, and tracing across the app and the
  agent integration surface; alerting on failed write-backs and stuck approvals.
- **`[Roadmap]`** **SOC 2 readiness path** — controls mapping, access reviews, change management, and
  evidence collection, building on the existing audit trail.

---

## 9. SLAs & support tiers

| Tier | Audience | Highlights |
|---|---|---|
| **Community** | Single NGO, self-hosted | Open-source (MIT), community support, docs. |
| **Standard** `[Roadmap]` | Mid-size NGO | Managed hosting, business-hours support, target 99.5% uptime, email support. |
| **Enterprise** `[Roadmap]` | CSR programs / multi-NGO | SSO/SCIM, per-tenant Orchestrator folders, 99.9% uptime target, priority support, named CSM, security review support. |
| **Sovereign** `[Roadmap]` | Government / data-residency | On-prem or in-region private cloud, custom DPA, audit support. |

> SLA percentages and tier contents are targets for the commercial roadmap, not contractual
> commitments in this submission.

---

## Summary

Fevourd-K is a real, live system of record with a working read-only agent API and a clear
human-in-the-loop boundary. The enterprise story is a deliberate extension of that foundation:
multi-tenant isolation down to per-NGO Orchestrator folders, enterprise identity (SSO/SCIM) on top of
the existing RBAC, compliance-grade governance for Indian NGO/CSR regimes, and a trust dashboard that
makes every agent action accountable. The architecture — agents read, humans approve money and
compliance, the system of record stays the source of truth — is what lets it scale without ever
compromising on trust.

See also: [README](../README.md) · [Orchestration detail](agenthack-orchestration.md) ·
[UiPath manifest](../uipath/README.md) · [Submission index](agenthack-SUBMISSION-INDEX.md).
