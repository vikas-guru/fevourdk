# Video Assets — Fevourd-K × UiPath "ImpactOps Maestro"

Branded, self-contained graphics for the AgentHack demo video. Cross-reference the
shot list and timings in [`../../agenthack-demo-video-script.md`](../../agenthack-demo-video-script.md).

All SVGs are **1920×1080**, use only web-safe fonts (Georgia / Helvetica / Arial), and have the
**real Fevourd-K logo embedded** as a base64 PNG data URI — so each file renders anywhere with no
external assets. A `1920×1080` PNG export sits next to every SVG (regenerate with `rsvg-convert`).

## The assets

| File | What it is | Cut in at |
|------|-----------|-----------|
| `title-card.svg` | Opening title: "ImpactOps Maestro" / "Agentic back-office for NGOs & CSR" / "UiPath AgentHack 2026", logo in a white chip. | **0:00** — hold ~3s, dissolve to `/ngo/dashboard`. |
| `lower-third.svg` | Reusable overlay template: left = blue section chip with an **orange number tile** ("④ Finance Claim" as shipped); right = persistent slate tag "Fevourd-K = system of record · UiPath = agentic layer". Transparent canvas — overlays on live footage. | Per workflow (**0:55 / 1:35 / 2:10 / 2:45 / 3:25**); persistent tag stays the whole video. Swap the number glyph + label per section. |
| `architecture-card.svg` | Full-frame diagram: Fevourd-K ↔ Maestro ↔ Orchestrator / Studio Agents / Action Center, with read (orange) + write-back (green) arrows and the six-workflow strip. | **4:10–4:25** — full cut; optionally animate the arrows. |
| `end-card.svg` | Closing/CTA: "The agentic back-office for social impact" + GitHub · Demo · Sandbox placeholders, logo, "UiPath AgentHack 2026". | **4:36–4:44** — fade out. Fill in the link text before export. |

## Palette (official Fevourd-K — from `tailwind.config.js`)

Blue is dominant (60–70%); orange is the single accent for UiPath / agentic / human-gate / section numbers; slate for neutrals.

| Role | Hex |
|------|-----|
| Blue 600 / 700 / 900 | `#2563eb` · `#1d4ed8` · `#1e3a8a` |
| Blue light | `#dbeafe` · `#eff6ff` |
| Orange 500 / 600 | `#f97316` · `#ea580c` |
| Orange light | `#ffedd5` · `#fff7ed` |
| Slate (dark→light) | `#0f172a` · `#1e293b` · `#334155` · `#64748b` · `#cbd5e1` · `#f1f5f9` |
| Paper white | `#ffffff` |
| Write-back "success" green (arrows/badges only) | `#34d399` |

## Logo

Source: `public/assets/images/fevourd-k/logo.png` (234×240). It is embedded in each SVG as a
base64 data URI. The mark sits on a near-white background, so on dark frames it is always placed
**inside a white rounded chip** — keep that pattern if you re-create any card.

## How the editor uses them

1. **0:00** — `title-card` over the cold open; dissolve to the live NGO dashboard.
2. **Per workflow** — drop `lower-third` (slide the section chip in from the left); duplicate it and swap the number/label for each of ①–⑤. Keep the right-hand persistent tag on screen for the whole runtime.
3. **4:10** — hard cut to `architecture-card`; animate read/write-back arrows if desired.
4. **4:36** — `end-card`; fill in the GitHub / Demo / Sandbox links, then fade.

See **§8 (cut-in map)** and **§9 (production & branding notes — fonts, lower-third style, the
"write-back ding" SFX, safe zones, captions, export settings)** in the video script.

## Regenerating PNGs

```sh
cd docs/agenthack-assets/video
for f in title-card end-card lower-third architecture-card; do
  rsvg-convert -w 1920 -h 1080 "$f.svg" -o "$f.png"
done
```

> Public-safe: sandbox tenant labels only, no secrets/PII. Nothing in these assets references a
> production host, token, or real person.
