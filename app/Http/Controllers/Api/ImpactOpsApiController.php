<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\NGO;
use App\Models\NGODocument;
use App\Models\NGOLedgerEntry;
use App\Models\NgoExpenseClaim;
use App\Models\NgoFieldSession;
use App\Models\NgoFieldTrackPoint;
use Illuminate\Http\JsonResponse;

/**
 * Read-only "ImpactOps Maestro" agent API.
 *
 * This is the integration surface UiPath Studio agents read from. It is
 * intentionally read-only: writes (claim decisions, ledger posts, doc reviews)
 * go through the existing authenticated app routes so the human-in-the-loop and
 * audit trail stay intact. Demo/sandbox data only; guarded by a bearer token.
 */
class ImpactOpsApiController extends Controller
{
    private function ngo(string $slug): NGO
    {
        return NGO::where('slug', $slug)->firstOrFail();
    }

    public function health(): JsonResponse
    {
        return response()->json(['ok' => true, 'service' => 'ImpactOps Maestro agent API', 'mode' => 'read-only']);
    }

    public function documents(string $ngo): JsonResponse
    {
        $n = $this->ngo($ngo);
        $docs = NGODocument::where('ngo_id', $n->id)->get()->map(fn ($d) => [
            'id' => $d->id,
            'type' => $d->document_type,
            'status' => $d->status,
            'verified_at' => optional($d->verified_at)->toDateString(),
        ]);

        return response()->json(['ngo' => $n->slug, 'documents' => $docs]);
    }

    public function campaign(string $ngo, string $campaign): JsonResponse
    {
        $n = $this->ngo($ngo);
        $c = Campaign::where('ngo_id', $n->id)->where('slug', $campaign)->firstOrFail();

        return response()->json([
            'title' => $c->title,
            'slug' => $c->slug,
            'raised' => (int) $c->raised_amount,
            'target' => (int) $c->target_amount,
            'donor_count' => (int) $c->donor_count,
            'status' => $c->status,
        ]);
    }

    public function claims(string $ngo): JsonResponse
    {
        $n = $this->ngo($ngo);
        $claims = NgoExpenseClaim::where('ngo_id', $n->id)
            ->with('user:id,name')
            ->orderByDesc('created_at')->limit(50)->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'claimant' => optional($c->user)->name,
                'amount' => (int) $c->amount,
                'currency' => $c->currency,
                'category' => $c->category,
                'status' => $c->status,
            ]);

        return response()->json(['ngo' => $n->slug, 'claims' => $claims]);
    }

    public function fieldTrail(int $session): JsonResponse
    {
        $s = NgoFieldSession::findOrFail($session);
        $points = NgoFieldTrackPoint::where('field_session_id', $s->id)->count();

        return response()->json([
            'session_id' => $s->id,
            'status' => $s->status,
            'active' => $s->status === 'active',
            'started_at' => optional($s->started_at)->toIso8601String(),
            'ended_at' => optional($s->ended_at)->toIso8601String(),
            'points' => $points,
            'distance_meters' => (int) $s->distance_meters,
        ]);
    }

    public function csrImpact(string $ngo): JsonResponse
    {
        $n = $this->ngo($ngo);
        $approvedSpend = (int) NgoExpenseClaim::where('ngo_id', $n->id)->where('status', 'approved')->sum('amount');
        $verifiedSessions = NgoFieldSession::where('ngo_id', $n->id)->where('status', 'completed')->count();
        $pendingDocs = NGODocument::where('ngo_id', $n->id)->where('status', 'pending')->count();
        $balance = (int) optional(NGOLedgerEntry::where('ngo_id', $n->id)->latest('id')->first())->balance_after;

        return response()->json([
            'ngo' => $n->slug,
            'verified_field_sessions' => $verifiedSessions,
            'approved_spend' => $approvedSpend,
            'ledger_balance' => $balance,
            'compliance_pending' => $pendingDocs,
            'sdg' => ['SDG 6'],
        ]);
    }
}
