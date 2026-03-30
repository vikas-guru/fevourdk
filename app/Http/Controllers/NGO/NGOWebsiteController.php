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

        return view('ngo.site-template', [
            'ngo' => $ngo,
            'preview' => $isOwnerPreview,
            'microsite' => NgoMicrositeContent::for($ngo),
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
