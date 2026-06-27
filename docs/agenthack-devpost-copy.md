# Devpost Submission Copy — Fevourd-K × UiPath "ImpactOps Maestro"

> Paste-ready. Public-safe (sandbox tenant, no secrets/PII). Edit the bracketed links before submitting.

---

## Project Title
**ImpactOps Maestro — Agentic Back-Office for NGOs & CSR, on UiPath**

## Tagline (one line)
Fevourd-K is the NGO/CSR system of record; UiPath ImpactOps Maestro is the agentic layer that runs six back-office workflows and escalates only the decisions that need a human.

## Elevator pitch (≈ 50 words)
NGOs lose days to manual compliance checks, campaign drafting, field verification, expense claims, and CSR reporting. ImpactOps Maestro is a UiPath agentic process that reads live data from the Fevourd-K platform, runs all six workflows, routes money and compliance calls to a human in Action Center, and writes results back — with a full audit trail.

---

## Inspiration
We build Fevourd-K, a Laravel/Vue platform that connects NGOs, corporate CSR teams, donors, and field volunteers. Talking to NGOs, the pattern was always the same: the *system* was modern, but the *operations between screens* were still manual — chasing renewal documents, hand-writing donor updates, eyeballing field photos, re-keying expense receipts into ledgers, and assembling CSR reports by week-long email threads. These are repeatable, rules-based chores with occasional judgment calls — the perfect shape for agentic automation. AgentHack was the push to build the layer that does this work without ever taking control of the data away from the NGO.

## What it does
ImpactOps Maestro is a UiPath Maestro agentic process that coordinates six workflows on top of Fevourd-K:

1. **Compliance Review** — pulls NGO registration/renewal documents, checks expiry, mandatory fields, and status; routes findings to a compliance officer for sign-off.
2. **Campaign Draft** — reads the latest campaign + field progress and assembles a donor/CSR-ready update as a *draft* (never auto-posted) in Fevourd-K's Feed Studio.
3. **Field Proof** — verifies field-task evidence: active session, GPS trail vs. assigned site, completion status; marks tasks Verified.
4. **Finance Claim** — checks expense claims against budget line and policy limits, escalates anything over threshold to Action Center, and posts approved claims to the NGO ledger.
5. **CSR Report** — on a schedule, gathers verified field proof, approved spend, and compliance status into one CSR impact + compliance summary.
6. **ImpactOps Maestro Agentic Process** — orchestrates the end-to-end handoffs and human approvals across the five workflows.

Throughout, **Fevourd-K stays the system of record and the human portal**, and **UiPath is the agentic operations layer** — read, act, escalate, write back.

## How we built it
- **Product / system of record:** Fevourd-K — Laravel 12 + Inertia + Vue 3, MySQL — exposes the campaigns, field sessions/GPS trails, finance claims, ledger, compliance documents, and CSR dashboard that the agents read from and write back to.
- **Orchestration:** UiPath **Maestro** runs the "ImpactOps Maestro" agentic process (the six-node flow with human-approval nodes).
- **Automations:** UiPath **Studio** packages — one per workflow (`Compliance Review`, `Campaign Draft`, `Field Proof`, `Finance Claim`, `CSR Report`).
- **Runtime:** UiPath **Orchestrator** (folder `Fevourd-K-Demo`) with per-workflow queues, a credential **asset** for the sandbox Fevourd-K API token, a config asset for the finance approval threshold, and event + time (cron) **triggers**.
- **Human-in-the-loop:** UiPath **Action Center** holds the compliance sign-off and the finance Approve/Reject decisions.
- **Integration:** Studio agents call Fevourd-K's JSON APIs to read state and write results back (status flips, scheduled drafts, verified badges, ledger entries, assembled reports).

## Challenges we ran into
- **Drawing the human/agent line.** We deliberately kept money (Finance Claim) and compliance sign-off as *mandatory* human approvals via Action Center, and let lower-risk steps run unattended — so "agentic" never means "unsupervised where it matters."
- **Keeping Fevourd-K as the single source of truth.** The agents had to coordinate work without owning data; every write goes back into Fevourd-K so the audit trail stays intact.
- **Making evidence verifiable, not narrated.** For Field Proof we leaned on real GPS-trail data so verification is checkable, not a vibe.
- **Public-safety for a public demo.** We rebuilt the entire flow on a sandbox tenant with fake NGOs/amounts, masked tokens, and redacted PII so nothing sensitive ships in the submission.

## Accomplishments we're proud of
- A genuine closed **perceive → act → escalate → write-back** loop across six real workflows, not a scripted happy-path.
- Real bidirectional integration on the finance flow: claim → human decision → posted ledger entry, end to end.
- A clean architecture story judges can trust: **Fevourd-K never loses control of the data; UiPath never makes the money decision alone.**

## What we learned
Agentic value in the social sector isn't a flashy chatbot — it's reliably removing recurring operational drudgery while *strengthening* the audit trail. The orchestration + human-approval pattern (Maestro + Action Center) maps almost perfectly onto NGO/CSR governance needs.

## What's next
- Expand to more workflows (donor reconciliation, grant milestone tracking, vendor verification).
- Multi-NGO tenancy with per-NGO Orchestrator folders and approval routing.
- Confidence scoring so agents auto-approve only the clearest cases and escalate the rest.
- A "trust dashboard" in Fevourd-K showing every agent action and who approved it.

## Built with
`UiPath Maestro` · `UiPath Studio` · `UiPath Orchestrator` · `UiPath Action Center` · `Laravel` · `Inertia.js` · `Vue 3` · `MySQL` · `Tailwind CSS`

## Try it / Links
- **GitHub:** [TODO — public repo URL]
- **Demo video:** [TODO — public video URL]
- **Deck:** [TODO — public deck URL]
- **Live demo (sandbox):** [TODO — sandbox URL or "available on request"]

---

### Devpost field cheat-sheet (where each block goes)
| Devpost field | Use section above |
|---|---|
| Project name | Project Title |
| Tagline / "Short description" | Tagline |
| "Elevator pitch" | Elevator pitch |
| Inspiration | Inspiration |
| What it does | What it does |
| How we built it | How we built it |
| Challenges | Challenges we ran into |
| Accomplishments | Accomplishments |
| What we learned | What we learned |
| What's next | What's next |
| Built With (tags) | Built with |
| Links | Try it / Links |
