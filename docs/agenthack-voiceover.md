# ImpactOps Maestro — Voiceover Narration (timed)

**For:** `docs/agenthack-video-animatic.html` (the demo film) · **Total runtime:** 4:54 (hard ceiling < 5:00)
**Voice:** warm, crisp, enterprise. ~120 wpm. Let each line breathe; pause on scene cuts.
**How to use:** read aloud, or feed each block into a TTS engine (e.g. ElevenLabs / Azure Neural — a calm mid-pitch narrator). The `[mm:ss]` cues line up with the animatic's scene timings (the `data-t` / `SHOTS` `t` values). Ducking: keep the synth music bed ~6 dB under the voice.

**Public-safety:** sandbox only. NGO = "Vikasana (Demo Tenant)". Corporate = "Acme CSR (Sandbox)". No real PII, no secrets, sandbox/`localhost:8080` URLs, OTP `1234`, masked API token. Amounts are illustrative.

---

## Cold open — the cause  ·  [0:00] → [0:11]
> In rural Mandya, Karnataka, clean drinking water is still a daily walk.
> Vikasana — a grassroots NGO — is changing that, one borewell and water filter at a time.

*(Pause on the headline. Let the water backdrop drift.)*

## The tension — the hidden cost of doing good  ·  [0:11] → [0:25]
> But behind every rupee of impact, hours vanish into back-office paperwork.
> Compliance renewals. Campaign updates. Field proof. Finance claims. CSR reports.
> Six staff-days a month, lost across five disconnected tools.

## The solution loop — what ImpactOps Maestro is  ·  [0:25] → [0:40]
> So we built ImpactOps Maestro: a UiPath agentic layer that sits on top of the Fevourd-K platform.
> It reads the live state, runs the repeatable work, escalates only the judgment calls — then writes the result straight back.
> Read. Act. Escalate. Write back.

## HOW WE HARNESS UiPath — the architecture  ·  [0:40] → [1:02]
> Here's exactly how it fits together.
> Fevourd-K is the system of record and the human portal — Laravel and Vue, with a secure API. It stays the single source of truth.
> Above it, UiPath Maestro orchestrates one agentic process across all six workflows.
> Orchestrator runs the queues, the triggers, and the assets — including the sandbox token and the finance approval threshold.
> Studio agents do the work — five packages: Compliance, Campaign, Field Proof, Finance Claim, and CSR Report. Each one reads, checks, and writes back.
> And Action Center holds the two human gates — compliance sign-off, and finance approve-or-reject.
> The rule never bends: Fevourd-K never loses control of the data, and UiPath never decides the money alone.

*(This is the explainer beat — pace it a touch slower; let each pillar land as it animates in.)*

## System of record  ·  [1:02] → [1:14]
> Inside Fevourd-K, everything lives in one place — campaigns, field work, finance, and compliance. This is the truth the agents read from, and write back to.

## ① Compliance Review  ·  [1:14] → [1:30]
> First workflow. Vikasana uploads its renewal documents — 12A and 80G.
> The Compliance agent pulls them through the Fevourd-K API and checks expiry dates, required fields, and registration status.

## ① Compliance — human-in-the-loop  ·  [1:30] → [1:44]
> It flags one document expiring soon and routes a clean summary to a compliance officer in Action Center.
> One click signs off — and the status writes straight back into Fevourd-K.

## ② Campaign Draft  ·  [1:44] → [2:00]
> Next, a fundraising update needs to go out. The Clean Water campaign — five-point-one-two lakh raised, of eight.
> The Campaign agent reads the latest progress and assembles a donor-ready update — copy, impact numbers, and hashtags.

## ② Campaign — control stays human  ·  [2:00] → [2:14]
> But it stops at a draft. Never auto-posted. The NGO reviews and schedules. The agent does the writing; the team keeps editorial control.

## ③ Field Proof  ·  [2:14] → [2:32]
> Out in Mandya, staff log a borewell visit on the Fevourd-K field app — captured as a live GPS trail.

## ③ Field Proof — verifiable, not self-reported  ·  [2:32] → [2:48]
> The Field Proof agent verifies the session against the assigned site, confirms the work is complete, and marks it Verified — proof a funder can trust.

## ④ Finance Claim  ·  [2:48] → [3:04]
> A field-travel claim arrives — four thousand two hundred fifty rupees.
> The Finance agent checks the receipt, the budget line, and the policy limits.

## ④ Finance — money decisions are always human  ·  [3:04] → [3:18]
> Over the threshold, it escalates. A finance officer approves or rejects in Action Center. This is the line we never cross automatically — money is always a human decision.

## ④ Finance — write-back to the ledger  ·  [3:18] → [3:32]
> On approval, the entry posts straight to the NGO ledger. Claim to ledger — with no manual re-keying.

## ⑤ CSR Report  ·  [3:32] → [3:48]
> Now the corporate funder. Acme CSR wants this quarter's impact report — verified proof, approved spend, and compliance status.

## ⑤ CSR — on demand  ·  [3:48] → [4:02]
> On a schedule, the CSR agent gathers it all from Fevourd-K into one CSR-ready summary — every number traceable to a verified source.
> What used to take a week of chasing teams is now ready on demand.

## The impact  ·  [4:02] → [4:32]
> Less time on paperwork. More clean water delivered.
> This quarter, in the sandbox: twelve hundred forty beneficiaries reached, twelve villages GPS-verified, mapped to SDG 6 — clean water and sanitation — with fourteen-point-seven-five lakh tracked on a full audit trail.

## Close  ·  [4:32] → [4:54]
> Fevourd-K stays the single source of truth. UiPath ImpactOps Maestro is the agentic layer on top — it reads, runs the busywork, escalates the judgment calls, and writes the result back.
> Not a chatbot — an operations team that never sleeps, with a full audit trail.
> ImpactOps Maestro — the agentic back-office for social impact.

---

### Timing cheat-sheet (matches the animatic scenes)

| Scene | Cue | Animatic `data-t` / `t` |
|-------|-----|--------------------------|
| The cause | 0:00 | 0 |
| The tension | 0:11 | 11 |
| The solution loop | 0:25 | 25 |
| **How we harness UiPath** | **0:40** | **40** |
| System of record | 1:02 | 62 |
| ① Compliance | 1:14 / 1:30 | 74 / 90 |
| ② Campaign | 1:44 / 2:00 | 104 / 120 |
| ③ Field Proof | 2:14 / 2:32 | 134 / 152 |
| ④ Finance | 2:48 / 3:04 / 3:18 | 168 / 184 / 198 |
| ⑤ CSR Report | 3:32 / 3:48 | 212 / 228 |
| The impact | 4:02 | 242 |
| Close (end card) | 4:32 | 272 |
| Fade out | 4:54 | TOTAL = 294 |

> Word budget: the whole script reads in ~4:40 at 120 wpm, leaving headroom under the 4:54 runtime. If TTS runs long, trim the parenthetical asides and the second sentence of the impact stats first.
