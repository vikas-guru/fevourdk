<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DonorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return Inertia::render('Donor/Dashboard', [
            'user' => $user->load('roles'),
            'donations' => $user->donations()->with('campaign', 'ngo')->latest()->take(10)->get(),
            'stats' => [
                'total_donated' => $user->donations()->sum('amount'),
                'campaigns_supported' => $user->donations()->distinct('campaign_id')->count(),
                'ngos_supported' => $user->donations()->distinct('ngo_id')->count(),
            ]
        ]);
    }
}
