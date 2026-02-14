<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NGO;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CSRController extends Controller
{
    public function index()
    {
        return Inertia::render('CSR/Index', [
            'stats' => [
                'total_companies' => User::whereHas('roles', function($query) {
                    $query->where('name', 'corporate_csr_manager');
                })->count(),
                'active_campaigns' => Campaign::where('status', 'active')->count(),
                'total_ngos_partnered' => NGO::where('verification_status', 'verified')->count(),
                'total_funds_distributed' => DB::table('donations')->sum('amount'),
                'companies_this_year' => User::whereHas('roles', function($query) {
                    $query->where('name', 'corporate_csr_manager');
                })->whereYear('created_at', now()->year)->count(),
            ],
            'featured_campaigns' => Campaign::with(['ngo', 'donations'])
                ->where('is_featured', true)
                ->where('status', 'active')
                ->take(6)
                ->get(),
            'recent_activities' => $this->getRecentActivities(),
        ]);
    }

    public function create()
    {
        try {
            return Inertia::render('CSR/Create', [
                'sectors' => $this->getCSRSectors(),
                'impact_areas' => $this->getImpactAreas(),
                'partnership_types' => $this->getPartnershipTypes(),
            ]);
        } catch (\Exception $e) {
            // Fallback to basic data if helper methods fail
            return Inertia::render('CSR/Create', [
                'sectors' => [],
                'impact_areas' => [],
                'partnership_types' => [],
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'industry_sector' => 'required|string|max:255',
            'csr_domains' => 'required|array|min:1',
            'sdg_goals' => 'required|array|min:1',
            'company_type' => 'required|in:mnc,smc,startup,non_profit',
            'registration_number' => 'required|string|max:255',
            'pan_number' => 'required|string|size:10',
            'gst_number' => 'nullable|string|max:15',
            'company_phone' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'established_year' => 'required|integer|min:1800|max:' . now()->year,
            'employee_count' => 'required|integer|min:1',
            'annual_turnover' => 'required|numeric|min:0',
            'csr_budget' => 'required|numeric|min:0',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_designation' => 'required|string|max:255',
            'contact_person_email' => 'required|email',
            'contact_person_phone' => 'required|string|max:20',
            'registered_office_address' => 'required|string|max:500',
            'corporate_office_address' => 'required|string|max:500',
            'csr_focus_areas' => 'required|array|min:1',
            'partnership_type' => 'required|in:funding,technical,volunteer,mentorship',
            'previous_csr_activities' => 'nullable|string|max:1000',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_brochure' => 'nullable|file|mimes:pdf,doc,doc|max:5120',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120',
            'terms_accepted' => 'accepted',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $validated['contact_person_name'],
                'email' => $validated['company_email'],
                'password' => bcrypt(Str::random(12)), // Temporary password
                'email_verified_at' => now(),
            ]);

            // Assign CSR role
            $user->assignRole('corporate_csr_manager');

            // Create company profile
            $companyProfile = $user->companyProfile()->create([
                'company_name' => $validated['company_name'],
                'company_type' => $validated['company_type'],
                'industry_sector' => $validated['industry_sector'],
                'registration_number' => $validated['registration_number'],
                'pan_number' => $validated['pan_number'],
                'gst_number' => $validated['gst_number'],
                'company_phone' => $validated['company_phone'],
                'website' => $validated['website'],
                'established_year' => $validated['established_year'],
                'employee_count' => $validated['employee_count'],
                'annual_turnover' => $validated['annual_turnover'],
                'csr_budget' => $validated['csr_budget'],
                'registered_office_address' => $validated['registered_office_address'],
                'corporate_office_address' => $validated['corporate_office_address'],
                'csr_focus_areas' => json_encode($validated['csr_focus_areas']),
                'partnership_type' => $validated['partnership_type'],
                'previous_csr_activities' => $validated['previous_csr_activities'],
                'verification_status' => 'pending',
            ]);

            // Handle file uploads
            if ($request->hasFile('company_logo')) {
                $logoPath = $request->file('company_logo')->store('company-logos', 'public');
                $companyProfile->update(['company_logo' => $logoPath]);
            }

            if ($request->hasFile('company_brochure')) {
                $brochurePath = $request->file('company_brochure')->store('company-brochures', 'public');
                $companyProfile->update(['company_brochure' => $brochurePath]);
            }

            if ($request->hasFile('registration_certificate')) {
                $certPath = $request->file('registration_certificate')->store('registration-certificates', 'public');
                $companyProfile->update(['registration_certificate' => $certPath]);
            }

            // Create contact person
            $companyProfile->contactPerson()->create([
                'name' => $validated['contact_person_name'],
                'designation' => $validated['contact_person_designation'],
                'email' => $validated['contact_person_email'],
                'phone' => $validated['contact_person_phone'],
            ]);

            DB::commit();

            return redirect()->route('csr.dashboard')
                ->with('success', 'CSR registration completed successfully! We will review your application within 48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $user = auth()->user();
        $company = $user->companyProfile;
        
        if (!$company) {
            return redirect()->route('csr.create');
        }

        // Get analytics data
        $analytics = $company->analytics()
            ->selectRaw('year, 
                SUM(total_funds_distributed) as total_funds,
                SUM(funds_utilized) as utilized_funds,
                SUM(ngos_partnered) as ngos_partnered,
                SUM(beneficiaries_reached) as beneficiaries,
                COUNT(*) as quarters')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($year) {
                $year->quarters = $company->analytics()
                    ->where('year', $year->year)
                    ->get();
                return $year;
            });

        // Get compliance reports
        $complianceReports = $company->complianceReports()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get domains
        $domains = $company->domains()->get();

        // Calculate stats
        $stats = [
            'total_campaigns' => $company->campaigns()->count(),
            'active_domains' => $company->domains()->where('verification_status', 'verified')->count(),
            'sdg_aligned' => 12, // This would be calculated from actual SDG alignment
            'compliance_score' => $complianceReports->first() ? $complianceReports->first()->compliance_score : 0,
        ];

        return Inertia::render('CSR/Dashboard', [
            'company' => $company,
            'stats' => $stats,
            'recent_campaigns' => $company->campaigns()->latest()->take(5)->get(),
            'analytics' => $analytics,
            'complianceReports' => $complianceReports,
            'domains' => $domains,
        ]);
    }

    private function getCSRSectors(): array
    {
        return [
            'technology' => 'Technology & Innovation',
            'healthcare' => 'Healthcare & Wellness',
            'education' => 'Education & Skill Development',
            'environment' => 'Environment & Sustainability',
            'rural_development' => 'Rural Development',
            'women_empowerment' => 'Women Empowerment',
            'child_welfare' => 'Child Welfare',
            'arts_culture' => 'Arts & Culture',
            'sports' => 'Sports Development',
            'disability_inclusion' => 'Disability Inclusion',
            'senior_care' => 'Senior Citizen Care',
        ];
    }

    private function getImpactAreas(): array
    {
        return [
            'education' => 'Education & Literacy',
            'healthcare' => 'Healthcare & Medical Support',
            'environment' => 'Environmental Conservation',
            'rural_development' => 'Rural Infrastructure',
            'women_empowerment' => 'Women Empowerment',
            'youth_development' => 'Youth Development',
            'skill_development' => 'Skill Development',
            'disability_support' => 'Disability Support',
            'elderly_care' => 'Elderly Care',
            'arts_culture' => 'Arts & Culture Preservation',
            'sports_development' => 'Sports Development',
            'clean_water' => 'Clean Water & Sanitation',
            'renewable_energy' => 'Renewable Energy',
            'agriculture' => 'Agricultural Development',
            'microfinance' => 'Microfinance Support',
            'digital_literacy' => 'Digital Literacy',
        ];
    }

    private function getPartnershipTypes(): array
    {
        return [
            'funding' => [
                'title' => 'Financial Partnership',
                'description' => 'Direct funding for NGO projects and campaigns',
                'benefits' => ['Tax benefits', 'Brand visibility', 'Social impact metrics'],
            ],
            'technical' => [
                'title' => 'Technical Partnership',
                'description' => 'Providing technical expertise and resources',
                'benefits' => ['Knowledge sharing', 'Capacity building', 'Innovation transfer'],
            ],
            'volunteer' => [
                'title' => 'Employee Volunteer Program',
                'description' => 'Employee volunteering for social causes',
                'benefits' => ['Employee engagement', 'Team building', 'Social impact'],
            ],
            'mentorship' => [
                'title' => 'Mentorship Program',
                'description' => 'Mentoring NGOs and social enterprises',
                'benefits' => ['Leadership development', 'Network expansion', 'Social innovation'],
            ],
        ];
    }

    private function getRecentActivities(): array
    {
        return [
            [
                'type' => 'registration',
                'title' => 'New CSR Company Registration',
                'description' => 'TechCorp India Pvt Ltd registered for CSR partnership',
                'time' => '2 hours ago',
                'icon' => 'building-office-2',
            ],
            [
                'type' => 'campaign',
                'title' => 'New Campaign Launched',
                'description' => 'Education for All campaign started with 5 NGO partners',
                'time' => '5 hours ago',
                'icon' => 'gift',
            ],
            [
                'type' => 'partnership',
                'title' => 'Partnership Agreement',
                'description' => 'Partnership signed with Rural Development Foundation',
                'time' => '1 day ago',
                'icon' => 'handshake',
            ],
        ];
    }

    private function getUpcomingActivities($companyProfile): array
    {
        return [
            [
                'title' => 'CSR Strategy Meeting',
                'date' => now()->addDays(3)->format('M d, Y'),
                'type' => 'meeting',
                'priority' => 'high',
            ],
            [
                'title' => 'NGO Site Visit',
                'date' => now()->addDays(7)->format('M d, Y'),
                'type' => 'visit',
                'priority' => 'medium',
            ],
            [
                'title' => 'Impact Assessment Report Due',
                'date' => now()->addDays(15)->format('M d, Y'),
                'type' => 'deadline',
                'priority' => 'high',
            ],
        ];
    }

    private function calculateImpactScore($companyProfile): int
    {
        $baseScore = 0;
        
        // Company size contribution
        $baseScore += min($companyProfile->employee_count / 100, 20);
        
        // CSR budget contribution
        $baseScore += min($companyProfile->csr_budget / 1000000, 25);
        
        // Partnership diversity
        $partnershipTypes = is_array($companyProfile->csr_focus_areas) ? count($companyProfile->csr_focus_areas) : 0;
        $baseScore += min($partnershipTypes * 5, 20);
        
        // Years in operation
        $yearsInOperation = now()->year - $companyProfile->established_year;
        $baseScore += min($yearsInOperation / 5, 15);
        
        return min($baseScore, 100);
    }
}
