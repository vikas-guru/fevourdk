<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\NGO;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(): Response
    {
        // Get comprehensive platform statistics
        $stats = [
            'ngos' => NGO::where('verification_status', 'verified')->count(),
            'campaigns' => Campaign::where('status', 'active')->count(),
            'donors' => Donation::distinct('donor_id')->count(),
            'fundsRaised' => Donation::where('status', 'completed')->sum('amount'),
            'families' => 5000, // Example data - should come from impact tracking
            'shgs' => 20000, // Example data - should come from SHG registrations
            'fpos' => 500, // Example data - should come from FPO registrations
            'farmers' => 5000, // Example data - should come from farmer outreach
            'cbos' => 1000, // Example data - should come from CBO partnerships
        ];

        // Get featured campaigns with enhanced data
        $featuredCampaigns = Campaign::with(['ngo'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'slug' => $campaign->slug,
                    'description' => $campaign->description,
                    'featured_image' => $campaign->featured_image,
                    'target_amount' => $campaign->target_amount,
                    'raised_amount' => $campaign->raised_amount,
                    'progress_percentage' => $campaign->progress_percentage,
                    'end_date' => $campaign->end_date,
                    'location' => $campaign->location,
                    'category' => $campaign->category,
                    'ngo' => [
                        'name' => $campaign->ngo->name,
                        'slug' => $campaign->ngo->slug,
                        'logo' => $campaign->ngo->logo,
                    ],
                ];
            });

        // Get upcoming events
        $upcomingEvents = [
            [
                'title' => 'CSR/CSO\'s Conclave – South India 2026',
                'description' => 'Two-day regional event focused on environmental sustainability, climate change, and sustainable practices',
                'date' => 'January 7-8, 2026',
                'location' => 'Golden Metro Hotel, Sheshadripuram, Bengaluru',
                'organizers' => 'FEVOURD-K in collaboration with Vishwa Yuvak Kendra (VYK)',
                'registration_url' => '/events/conclave-2026',
            ],
        ];

        return Inertia::render('Welcome', [
            'stats' => $stats,
            'featuredCampaigns' => $featuredCampaigns,
            'upcomingEvents' => $upcomingEvents,
            'siteUrl' => rtrim((string) config('app.url'), '/'),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About', [
            'stats' => [
                'ngos' => 800,
                'districts' => 31,
                'founded' => 1982,
                'beneficiaries' => '100,000+',
            ],
        ]);
    }

    public function programs(): Response
    {
        return Inertia::render('Programs');
    }

    public function team(): Response
    {
        return Inertia::render('Team');
    }

    public function events(): Response
    {
        return Inertia::render('Events');
    }

    public function gallery(): Response
    {
        return Inertia::render('Gallery');
    }

    public function partners(): Response
    {
        return Inertia::render('Partners');
    }

    public function contact(): Response
    {
        return Inertia::render('Contact');
    }

    public function digitalization(): Response
    {
        return Inertia::render('Digitalization');
    }

    public function legalStatus(): Response
    {
        return Inertia::render('LegalStatus');
    }

    public function terms(): Response
    {
        return Inertia::render('Legal/Terms');
    }

    public function privacy(): Response
    {
        return Inertia::render('Legal/Privacy');
    }

    public function accessibility(): Response
    {
        return Inertia::render('Legal/Accessibility');
    }

    public function focusChildren(): Response
    {
        return Inertia::render('Focus/Children');
    }

    public function focusEnvironment(): Response
    {
        return Inertia::render('Focus/Environment');
    }

    public function focusCommunity(): Response
    {
        return Inertia::render('Focus/Community');
    }

    public function focusDisability(): Response
    {
        return Inertia::render('Focus/Disability');
    }
}
