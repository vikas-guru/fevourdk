<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\NGO;
use App\Support\Seo;
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
            'seo' => array_merge(Seo::page(
                'FEVOURD-K — Karnataka voluntary organisations hub',
                'FEVOURD-K connects 800+ voluntary organisations across Karnataka with donors, verified campaigns, NGO digital tools, and transparent giving.',
                '/',
            ), ['no_title_suffix' => true]),
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
            'seo' => Seo::page(
                'About FEVOURD-K',
                'Mission, history, and impact of the Federation of Voluntary Organisations Karnataka — Karnataka\'s apex body for voluntary organisations.',
                '/about',
            ),
        ]);
    }

    public function programs(): Response
    {
        return Inertia::render('Programs');
    }

    public function team(): Response
    {
        return Inertia::render('Team', [
            'seo' => Seo::page(
                'Leadership & team — FEVOURD-K',
                'Meet the leadership and governance structure behind the Federation of Voluntary Organisations Karnataka.',
                '/team',
            ),
        ]);
    }

    public function events(): Response
    {
        return Inertia::render('Events', [
            'seo' => Seo::page(
                'Events — FEVOURD-K',
                'FEVOURD-K programmes, conclaves, and community events across Karnataka.',
                '/events',
            ),
        ]);
    }

    public function gallery(): Response
    {
        return Inertia::render('Gallery', [
            'seo' => Seo::page(
                'Gallery — FEVOURD-K',
                'Photos and highlights from FEVOURD-K field work, partner organisations, and events.',
                '/gallery',
            ),
        ]);
    }

    public function partners(): Response
    {
        return Inertia::render('Partners', [
            'seo' => Seo::page(
                'Partners & collaborators — FEVOURD-K',
                'CSR, government, and civil society partners working with FEVOURD-K across Karnataka.',
                '/partners',
            ),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact', [
            'seo' => Seo::page(
                'Contact FEVOURD-K',
                'Reach the FEVOURD-K secretariat for partnerships, media, and volunteer enquiries.',
                '/contact',
            ),
        ]);
    }

    public function digitalization(): Response
    {
        return Inertia::render('Digitalization', [
            'seo' => Seo::page(
                'Digital tools for NGOs — FEVOURD-K',
                'How FEVOURD-K helps voluntary organisations digitise operations, compliance, and transparency.',
                '/digitalization',
            ),
        ]);
    }

    public function legalStatus(): Response
    {
        return Inertia::render('LegalStatus', [
            'seo' => Seo::page(
                'Legal status — FEVOURD-K',
                'Registration, legal standing, and governance information for the Federation of Voluntary Organisations Karnataka.',
                '/legal-status',
            ),
        ]);
    }

    public function terms(): Response
    {
        return Inertia::render('Legal/Terms', [
            'seo' => Seo::page(
                'Terms of use — FEVOURD-K',
                'Terms and conditions for using the FEVOURD-K platform, donations, and registrations.',
                '/terms',
            ),
        ]);
    }

    public function privacy(): Response
    {
        return Inertia::render('Legal/Privacy', [
            'seo' => Seo::page(
                'Privacy policy — FEVOURD-K',
                'How FEVOURD-K collects, uses, and protects personal data.',
                '/privacy',
            ),
        ]);
    }

    public function accessibility(): Response
    {
        return Inertia::render('Legal/Accessibility', [
            'seo' => Seo::page(
                'Accessibility — FEVOURD-K',
                'Accessibility commitments and how to request support on the FEVOURD-K platform.',
                '/accessibility',
            ),
        ]);
    }

    public function focusChildren(): Response
    {
        return Inertia::render('Focus/Children', [
            'seo' => Seo::page(
                'Children & education — FEVOURD-K',
                'FEVOURD-K focus on child rights, education, and protection through member NGOs.',
                '/focus/children',
            ),
        ]);
    }

    public function focusEnvironment(): Response
    {
        return Inertia::render('Focus/Environment', [
            'seo' => Seo::page(
                'Environment & climate — FEVOURD-K',
                'FEVOURD-K programmes on sustainability, climate resilience, and environmental justice.',
                '/focus/environment',
            ),
        ]);
    }

    public function focusCommunity(): Response
    {
        return Inertia::render('Focus/Community', [
            'seo' => Seo::page(
                'Community & livelihoods — FEVOURD-K',
                'FEVOURD-K work on community institutions, livelihoods, and rural development.',
                '/focus/community',
            ),
        ]);
    }

    public function focusDisability(): Response
    {
        return Inertia::render('Focus/Disability', [
            'seo' => Seo::page(
                'Disability inclusion — FEVOURD-K',
                'Inclusive development and support for persons with disabilities through FEVOURD-K network NGOs.',
                '/focus/disability',
            ),
        ]);
    }
}
