<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NGO;
use App\Support\NgoMicrositeContent;
use Illuminate\Support\Facades\Auth;

class NGOWebsiteController extends Controller
{
    public function showBySlug(string $ngoSlug)
    {
        $ngo = NGO::query()->where('slug', $ngoSlug)->with('city')->firstOrFail();
        $currentUser = Auth::user();
        $isOwnerPreview = $currentUser && (int) ($currentUser->ngo_id ?? 0) === (int) $ngo->id;

        if (! $ngo->is_active && ! $isOwnerPreview) {
            abort(404);
        }

        $myState = $currentUser
            ? \App\Models\NgoSupporter::where('ngo_id', $ngo->id)->where('user_id', $currentUser->id)->first()
            : null;

        return view('ngo.site-template', [
            'ngo' => $ngo,
            'preview' => $isOwnerPreview,
            'microsite' => NgoMicrositeContent::for($ngo),
            'followersCount' => $ngo->followers_count,
            'supportersCount' => $ngo->supporters_count,
            'isFollowing' => (bool) ($myState?->is_following),
            'isSupporting' => (bool) ($myState?->is_supporting),
            'authed' => (bool) $currentUser,
            'isOwner' => $isOwnerPreview,
        ]);
    }

    public function preview()
    {
        $ngo = Auth::user()?->ngo;
        abort_unless($ngo, 403);
        $ngo->loadMissing('city');

        return view('ngo.site-template', [
            'ngo' => $ngo,
            'preview' => true,
            'microsite' => NgoMicrositeContent::for($ngo),
        ]);
    }
}
