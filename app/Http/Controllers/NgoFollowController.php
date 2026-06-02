<?php

namespace App\Http\Controllers;

use App\Models\NGO;
use App\Models\NgoSupporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NgoFollowController extends Controller
{
    /**
     * Toggle the authenticated user's "follow" (subscribe to updates) on an NGO.
     * Available to every role — donor, volunteer, CSR, admin, etc.
     */
    public function toggleFollow(Request $request, NGO $ngo)
    {
        $user = Auth::user();

        $row = NgoSupporter::firstOrNew([
            'ngo_id' => $ngo->id,
            'user_id' => $user->id,
        ]);

        $row->is_following = ! $row->is_following;
        $row->followed_at = $row->is_following ? now() : null;
        // Dropping a follow also drops the support endorsement.
        if (! $row->is_following) {
            $row->is_supporting = false;
            $row->supported_at = null;
        }
        $row->save();

        return $this->respond($request, $ngo, $row);
    }

    /**
     * Toggle the authenticated user's "support" (publicly back the cause).
     * Supporting implies following.
     */
    public function toggleSupport(Request $request, NGO $ngo)
    {
        $user = Auth::user();

        $row = NgoSupporter::firstOrNew([
            'ngo_id' => $ngo->id,
            'user_id' => $user->id,
        ]);

        $row->is_supporting = ! $row->is_supporting;
        $row->supported_at = $row->is_supporting ? now() : null;
        if ($row->is_supporting) {
            $row->is_following = true;
            $row->followed_at = $row->followed_at ?: now();
        }
        $row->save();

        return $this->respond($request, $ngo, $row);
    }

    private function respond(Request $request, NGO $ngo, NgoSupporter $row)
    {
        $payload = [
            'ngo_id' => $ngo->id,
            'is_following' => $row->is_following,
            'is_supporting' => $row->is_supporting,
            'followers_count' => NgoSupporter::where('ngo_id', $ngo->id)->where('is_following', true)->count(),
            'supporters_count' => NgoSupporter::where('ngo_id', $ngo->id)->where('is_supporting', true)->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return back()->with('follow_state', $payload);
    }
}
