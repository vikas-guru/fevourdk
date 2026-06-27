# Deploy the ImpactOps agent API to fevourdk.online — go/no-go plan

**Goal:** make the read-only UiPath agent API live at `https://fevourdk.online/api` so UiPath can call
it in production. **Result today:** `https://fevourdk.online/api/health` returns **404** (the API code
is only on branch `codex/agenthack-submission-prep`, not yet on the Hostinger host).

> **SAFETY — this doc is a ready-to-run plan, NOT an executed deploy.**
> Nothing here has been run against the live site. This is a live production host with a known
> **password-drift gotcha** (the prod DB/FTP password can differ from local notes — re-confirm in
> hPanel before connecting). Do the steps below yourself, in the morning, with a clear head.
> The API is **read-only and token-guarded**, so the blast radius is small — but treat prod with care.

---

## What changes (and what does NOT)

- ✅ Adds 6 read-only GET endpoints under `/api`, all behind a bearer-token middleware.
- ✅ No database schema change, no migration, no seeder required — the endpoints read existing tables.
- ✅ No write endpoints exposed (writes stay on the authenticated app routes).
- ❌ Do **not** change any existing route, controller, or `.env` value other than adding one new key.

---

## Files to upload (exactly 5 + 1 routing file)

Relative to repo root → into the prod app dir (`laravel_app/` under the web root on Hostinger):

| # | File | Why |
|---|------|-----|
| 1 | `routes/api.php` | Declares the 6 agent endpoints |
| 2 | `app/Http/Controllers/Api/ImpactOpsApiController.php` | The read-only controller |
| 3 | `app/Http/Middleware/VerifyUipathToken.php` | Bearer-token guard |
| 4 | `config/services.php` | Adds `services.uipath.token` (reads `UIPATH_AGENT_TOKEN`) |
| 5 | `bootstrap/app.php` | Registers `routes/api.php` with `apiPrefix: 'api'` |

> Item 5 is the one easy-to-miss file: if `bootstrap/app.php` on prod does not already register the API
> routes, `/api/*` will 404 even with the other four files present. Compare it against the branch version
> before/after upload.

**Upload method:** FTP via hPanel File Manager or your FTP client (same host this site already uses).
Place each file at the **same relative path** inside `laravel_app/`. Do not upload `.env`, `.git`,
`vendor/`, or `node_modules/`. Do **not** run `deploy-hostinger.sh` for this — that does a full
rebuild/redeploy and rewrites the prod `.env`; you only need these 5 files.

---

## Set the token in the prod `.env` (one new line)

Add to `laravel_app/.env` on the server (via hPanel File Manager — do not overwrite the file, just
append/edit this one key):

```
UIPATH_AGENT_TOKEN=<choose-a-strong-random-token>
```

- If you skip this, the API still works using the sandbox default `sandbox-impactops-demo-token`
  (fine for the demo, but prefer a real random token in prod).
- Whatever you set here is the value UiPath puts in its Orchestrator credential asset. **Never commit
  or publish it; show it masked (`Bearer ****`) in every doc/screenshot.**
- Do not touch any other `.env` line (DB, APP_KEY, mail, OTP, etc.).

---

## Post-deploy commands (clear stale caches, then cache routes)

Run these in the prod app dir (`laravel_app/`). If you have SSH, run directly; otherwise use the same
protected-PHP-runner pattern Hostinger deploys use, or hPanel's terminal.

```bash
php artisan route:clear
php artisan config:clear
php artisan route:cache
```

> Order matters: `config:clear` makes Laravel re-read the new `UIPATH_AGENT_TOKEN`; `route:cache`
> rebuilds the route cache so `/api/*` is recognized. If `route:cache` errors, run `php artisan
> route:clear` and leave it uncached — the routes still resolve, just slightly slower.

---

## Verification (go/no-go check)

From your laptop, after the steps above:

```bash
# Should now return 200 JSON (was 404 before deploy)
curl -s -o /dev/null -w "%{http_code}\n" https://fevourdk.online/api/health
# -> 200

# Health body
curl -s -H "Authorization: Bearer <prod-token>" https://fevourdk.online/api/health
# -> {"ok":true,"service":"ImpactOps Maestro agent API","mode":"read-only"}

# Real data (proves it reads seeded Vikasana records)
curl -s -H "Authorization: Bearer <prod-token>" https://fevourdk.online/api/ngo/vikasana/documents

# Negative test: no/wrong token must be rejected
curl -s -o /dev/null -w "%{http_code}\n" https://fevourdk.online/api/ngo/vikasana/documents
# -> 401
```

**GO** if: `/api/health` is `200`, an authed read returns JSON, and an unauthed read returns `401`.
**NO-GO / rollback** if health stays `404` or returns a 500.

---

## Rollback (if anything looks wrong)

The change is additive, so rollback is clean:

1. Delete the 5 uploaded files (or restore the previous `bootstrap/app.php` / `config/services.php`).
2. Remove the `UIPATH_AGENT_TOKEN` line from prod `.env` (optional — it is harmless if left).
3. Re-run `php artisan route:clear && php artisan config:clear && php artisan route:cache`.
4. Confirm the main site still serves: `curl -s -o /dev/null -w "%{http_code}\n" https://fevourdk.online/`
   should be `200`.

The public site and portal are unaffected by this change either way — it only adds the `/api` surface.

---

## One-glance checklist for the morning

- [ ] Re-confirm prod FTP/SSH credentials in hPanel (password-drift gotcha).
- [ ] Upload the 5 files to `laravel_app/` at matching paths (incl. `bootstrap/app.php`).
- [ ] Add `UIPATH_AGENT_TOKEN=<strong-random>` to prod `.env` (one line only).
- [ ] `php artisan route:clear && php artisan config:clear && php artisan route:cache`.
- [ ] Verify: `/api/health` = 200, authed read = JSON, unauthed read = 401.
- [ ] Put the token in the UiPath Orchestrator credential asset (masked everywhere else).
- [ ] (Optional) Update the Devpost "Live demo" note — API base is now live at `https://fevourdk.online/api`.
