<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NGO;
use App\Models\Corporate;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class MultiRoleRegistrationController extends Controller
{
    /**
     * Show user type selection page
     */
    public function selectUserType()
    {
        return inertia('Auth/SelectUserType');
    }

    /**
     * Show registration form for selected user type
     */
    public function showRegistrationForm($userType)
    {
        $validTypes = ['individual', 'ngo', 'ngo-chat', 'corporate', 'volunteer'];
        
        if (!in_array($userType, $validTypes)) {
            return redirect()->route('register.select-type')
                ->with('error', 'Invalid user type selected');
        }

        // Get states for location selection
        $states = \App\Models\State::all();
        $cities = \App\Models\City::all();
        
        return inertia("Auth/Register/{$userType}", [
            'states' => $states,
            'cities' => $cities,
            'focusAreas' => $this->getFocusAreas(),
            'skills' => $this->getVolunteerSkills()
        ]);
    }

    /**
     * Register individual user
     */
    public function registerIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'postal_code' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'otp_code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                     ->withInput();
        }

        // Verify OTP (simplified for now)
        if (!$this->verifyOTP($request->phone, $request->otp_code)) {
            return back()->with('error', 'Invalid OTP code')
                     ->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'city_id' => $request->city_id,
            'postal_code' => $request->postal_code,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'user_type' => 'individual',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        // Assign individual role
        $user->assignRole('Donor');

        event(new Registered($user));
        
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Register NGO (Chat Style)
     */
    public function registerNGOChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ngo_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:50|unique:ngos,registration_number',
            'pan' => 'required|string|size:10|unique:ngos,pan',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'focus_areas' => 'required|array|min:1',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'documents' => 'required|array|min:1',
            'documents.registration_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.pan_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'accept_terms' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate slug from NGO name
            $slug = Str::slug($request->ngo_name);
            $originalSlug = $slug;
            $counter = 1;
            
            while (NGO::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create NGO
            $ngo = NGO::create([
                'name' => $request->ngo_name,
                'slug' => $slug,
                'registration_number' => $request->registration_number,
                'pan' => $request->pan,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city_id' => $this->getCityIdByName($request->city),
                'description' => $request->description,
                'focus_areas' => $request->focus_areas,
                'verification_status' => 'pending',
                'is_active' => false
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('ngo_logos', 'public');
                $ngo->update(['logo' => $logoPath]);
            }

            // Handle document uploads
            $documentTypes = [
                'registration_certificate' => 'registration_certificate',
                'pan_card' => 'pan_card'
            ];

            foreach ($documentTypes as $field => $type) {
                if ($request->hasFile("documents.{$field}")) {
                    $document = $request->file("documents.{$field}");
                    $path = $document->store('ngo_documents', 'public');
                    
                    $ngo->documents()->create([
                        'document_type' => $type,
                        'file_path' => $path,
                        'file_name' => $document->getClientOriginalName(),
                        'file_type' => $document->getClientOriginalExtension(),
                        'file_size' => $document->getSize(),
                        'status' => 'pending_verification'
                    ]);
                }
            }

            // Generate NGO website
            $this->generateNGOWebsite($ngo);

            return response()->json([
                'success' => true,
                'message' => 'NGO registration submitted successfully!',
                'ngo_id' => $ngo->id,
                'website_url' => $ngo->website_url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get city ID by name (helper method)
     */
    private function getCityIdByName($cityName)
    {
        $city = \App\Models\City::where('name', 'like', "%{$cityName}%")->first();
        return $city ? $city->id : null;
    }
    public function registerNGO(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Basic Information
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:50|unique:ngos,registration_number',
            'pan' => 'required|string|size:10|unique:ngos,pan',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'city_id' => 'required|exists:cities,id',
            
            // Organization Details
            'description' => 'required|string|max:2000',
            'focus_areas' => 'required|array|min:1',
            'focus_areas.*' => 'string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'year_established' => 'nullable|integer|min:1900|max:' . date('Y'),
            'team_size' => 'nullable|string',
            
            // Bank Account
            'bank_account.account_holder_name' => 'required|string|max:255',
            'bank_account.bank_name' => 'required|string|max:255',
            'bank_account.account_number' => 'required|string|max:50',
            'bank_account.ifsc_code' => 'required|string|max:20',
            'bank_account.branch_name' => 'required|string|max:255',
            
            // Payment Gateways
            'payment_gateways' => 'nullable|array',
            'payment_gateways.*' => 'string|in:razorpay,phonepe,stripe',
            
            // Admin Contact
            'admin_contact.name' => 'required|string|max:255',
            'admin_contact.email' => 'required|email|max:255',
            'admin_contact.phone' => 'required|string|max:20',
            
            // Documents
            'documents.registration_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.pan_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'documents.eightyg_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Terms
            'accept_terms' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                     ->withInput();
        }

        // Generate slug from NGO name
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (NGO::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create NGO
        $ngo = NGO::create([
            'name' => $request->name,
            'slug' => $slug,
            'registration_number' => $request->registration_number,
            'pan' => $request->pan,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'address' => $request->address,
            'city_id' => $request->city_id,
            'description' => $request->description,
            'focus_areas' => $request->focus_areas,
            'verification_status' => 'pending',
            'is_active' => false
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('ngo_logos', 'public');
            $ngo->update(['logo' => $logoPath]);
        }

        // Create bank account
        if ($request->bank_account) {
            $ngo->bankAccounts()->create([
                'account_holder_name' => $request->bank_account['account_holder_name'],
                'bank_name' => $request->bank_account['bank_name'],
                'account_number' => $request->bank_account['account_number'],
                'ifsc_code' => $request->bank_account['ifsc_code'],
                'branch_name' => $request->bank_account['branch_name'],
                'is_primary' => true
            ]);
        }

        // Create payment gateway configurations
        if ($request->payment_gateways) {
            foreach ($request->payment_gateways as $gateway) {
                $ngo->paymentGateways()->create([
                    'gateway_name' => $gateway,
                    'is_active' => false, // Will be activated after configuration
                    'credentials' => json_encode([])
                ]);
            }
        }

        // Handle document uploads
        $documentTypes = [
            'registration_certificate' => 'registration_certificate',
            'pan_card' => 'pan_card',
            'eightyg_certificate' => '80g_certificate'
        ];

        foreach ($documentTypes as $field => $type) {
            if ($request->hasFile("documents.{$field}")) {
                $document = $request->file("documents.{$field}");
                $path = $document->store('ngo_documents', 'public');
                
                $ngo->documents()->create([
                    'document_type' => $type,
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientOriginalExtension(),
                    'file_size' => $document->getSize(),
                    'status' => 'pending_verification'
                ]);
            }
        }

        // Create user account for NGO admin
        $user = User::create([
            'first_name' => $request->admin_contact['name'],
            'email' => $request->admin_contact['email'],
            'phone' => $request->admin_contact['phone'],
            'password' => Hash::make(Str::random(10)), // Will be emailed
            'role' => 'ngo_admin',
            'ngo_id' => $ngo->id
        ]);

        // Create NGO user relationship
        $ngo->users()->create([
            'user_id' => $user->id,
            'role' => 'admin',
            'is_active' => true
        ]);

        // Generate NGO website
        $this->generateNGOWebsite($ngo);

        // Send welcome email with login credentials
        // TODO: Implement email sending

        event(new Registered($user));
        
        Auth::login($user);

        return redirect()->route('ngo.dashboard')
            ->with('success', 'NGO registration submitted successfully! Your application is under verification.');
    }

    /**
     * Register Corporate/CSR Entity
     */
    public function registerCorporate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'pan_number' => 'required|string|size:10|unique:corporates,pan_number',
            'cin_number' => 'nullable|string|size:21|unique:corporates,cin_number',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
            'postal_code' => 'required|string|max:10',
            'website' => 'nullable|url|max:255',
            'description' => 'required|string|max:2000',
            'industry_sector' => 'required|string|max:255',
            'annual_turnover' => 'nullable|integer|min:0',
            'csr_budget' => 'required|integer|min:0',
            'preferred_causes' => 'required|array|min:1',
            'preferred_causes.*' => 'exists:focus_areas,id',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_designation' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'gst_number' => 'nullable|string|max:15',
            'documents' => 'required|array|min:1',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'otp_code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                     ->withInput();
        }

        // Verify OTP
        if (!$this->verifyOTP($request->phone, $request->otp_code)) {
            return back()->with('error', 'Invalid OTP code')
                     ->withInput();
        }

        // Create Corporate entity
        $corporate = Corporate::create([
            'name' => $request->company_name,
            'pan_number' => $request->pan_number,
            'cin_number' => $request->cin_number,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'website' => $request->website,
            'description' => $request->description,
            'industry_sector' => $request->industry_sector,
            'annual_turnover' => $request->annual_turnover,
            'csr_budget' => $request->csr_budget,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_designation' => $request->contact_person_designation,
            'contact_person_phone' => $request->contact_person_phone,
            'contact_person_email' => $request->contact_person_email,
            'gst_number' => $request->gst_number,
            'status' => 'active'
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('corporate_documents', 'public');
                $corporate->documents()->create([
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'file_type' => $document->getClientOriginalExtension(),
                    'file_size' => $document->getSize()
                ]);
            }
        }

        // Attach preferred causes
        $corporate->focusAreas()->attach($request->preferred_causes);

        // Create user account
        $user = User::create([
            'first_name' => $request->company_name,
            'last_name' => 'CSR Manager',
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'city_id' => $request->city_id,
            'user_type' => 'corporate',
            'corporate_id' => $corporate->id,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        // Assign Corporate CSR Manager role
        $user->assignRole('Corporate CSR Manager');

        event(new Registered($user));
        
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Corporate account created successfully!');
    }

    /**
     * Register Volunteer
     */
    public function registerVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'postal_code' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'education' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'skills' => 'required|array|min:1',
            'skills.*' => 'exists:skills,id',
            'availability' => 'required|array|min:1',
            'availability.*' => 'in:weekdays,weekends,mornings,afternoons,evenings',
            'volunteer_interests' => 'required|array|min:1',
            'volunteer_interests.*' => 'exists:focus_areas,id',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'previous_volunteer' => 'nullable|boolean',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relation' => 'required|string|max:100',
            'motivation' => 'required|string|max:1000',
            'otp_code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                     ->withInput();
        }

        // Verify OTP
        if (!$this->verifyOTP($request->phone, $request->otp_code)) {
            return back()->with('error', 'Invalid OTP code')
                     ->withInput();
        }

        // Create user account
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'city_id' => $request->city_id,
            'postal_code' => $request->postal_code,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        // Create volunteer profile
        $volunteer = Volunteer::create([
            'user_id' => $user->id,
            'education' => $request->education,
            'occupation' => $request->occupation,
            'experience_years' => $request->experience_years,
            'previous_volunteer' => $request->previous_volunteer ?? false,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relation' => $request->emergency_contact_relation,
            'motivation' => $request->motivation,
            'status' => 'active'
        ]);

        // Attach skills and interests
        $volunteer->skills()->attach($request->skills);
        $volunteer->availability()->attach($request->availability);
        $volunteer->interests()->attach($request->volunteer_interests);

        // Assign Volunteer role
        $user->assignRole('Volunteer');

        event(new Registered($user));
        
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Volunteer registration successful!');
    }

    /**
     * Send OTP for phone verification
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20'
        ]);

        $phone = $request->phone;
        $otp = rand(100000, 999999);
        
        // Store OTP in cache (simplified - in production use Redis/Database)
        cache(['otp_' . $phone => $otp], now()->addMinutes(10));

        // Send OTP via SMS (integrate with SMS gateway)
        // $this->sendSMS($phone, "Your FEVOURD-K OTP is: $otp");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove in production
        ]);
    }

    /**
     * Verify OTP
     */
    private function verifyOTP($phone, $otp)
    {
        $cachedOTP = cache('otp_' . $phone);
        return $cachedOTP && $cachedOTP == $otp;
    }

    /**
     * Get focus areas for dropdowns
     */
    private function getFocusAreas()
    {
        return [
            'Education',
            'Healthcare',
            'Environment',
            'Women Empowerment',
            'Child Welfare',
            'Rural Development',
            'Disaster Relief',
            'Agriculture',
            'Skill Development',
            'Senior Citizens',
            'Water & Sanitation',
            'Wildlife Conservation',
            'Arts & Culture',
            'Social Justice',
            'Technology for Good'
        ];
    }

    /**
     * Generate NGO website
     */
    private function generateNGOWebsite($ngo)
    {
        // Create NGO website directory structure
        $websitePath = public_path("ngo-websites/{$ngo->slug}");
        
        if (!is_dir($websitePath)) {
            mkdir($websitePath, 0755, true);
        }

        // Generate basic website files
        $this->generateNGOHomePage($ngo, $websitePath);
        $this->generateNGOAboutPage($ngo, $websitePath);
        $this->generateNGOContactPage($ngo, $websitePath);
        
        // Create .htaccess for clean URLs
        $htaccessContent = "
RewriteEngine On
RewriteRule ^about$ about.php [L]
RewriteRule ^contact$ contact.php [L]
RewriteRule ^donate$ https://fevourd-k.org/campaigns?ngo={$ngo->slug} [L,R=301]
";
        file_put_contents($websitePath . '/.htaccess', $htaccessContent);
        
        // Store website URL in NGO record
        $ngo->update(['website_url' => url("ngo-websites/{$ngo->slug}")]);
    }

    /**
     * Generate NGO home page
     */
    private function generateNGOHomePage($ngo, $websitePath)
    {
        $content = $this->getNGOWebsiteTemplate($ngo, 'home');
        file_put_contents($websitePath . '/index.php', $content);
    }

    /**
     * Generate NGO about page
     */
    private function generateNGOAboutPage($ngo, $websitePath)
    {
        $content = $this->getNGOWebsiteTemplate($ngo, 'about');
        file_put_contents($websitePath . '/about.php', $content);
    }

    /**
     * Generate NGO contact page
     */
    private function generateNGOContactPage($ngo, $websitePath)
    {
        $content = $this->getNGOWebsiteTemplate($ngo, 'contact');
        file_put_contents($websitePath . '/contact.php', $content);
    }

    /**
     * Get NGO website template
     */
    private function getNGOWebsiteTemplate($ngo, $page)
    {
        $logoUrl = $ngo->logo ? asset('storage/' . $ngo->logo) : asset('images/default-ngo-logo.png');
        $focusAreas = is_array($ngo->focus_areas) ? implode(', ', $ngo->focus_areas) : '';
        
        $header = "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>{$ngo->name} - " . ($page === 'home' ? 'Home' : ucfirst($page)) . "</title>
    <meta name=\"description\" content=\"{$ngo->name} - {$ngo->description}\">
    <link href=\"https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css\" rel=\"stylesheet\">
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css\" rel=\"stylesheet\">
</head>
<body class=\"bg-gray-50\">
    <!-- Header -->
    <header class=\"bg-white shadow-lg sticky top-0 z-50\">
        <div class=\"container mx-auto px-4 py-4\">
            <div class=\"flex justify-between items-center\">
                <div class=\"flex items-center space-x-4\">
                    <img src=\"{$logoUrl}\" alt=\"{$ngo->name}\" class=\"h-12 w-auto rounded-lg\">
                    <h1 class=\"text-2xl font-bold text-gray-800\">{$ngo->name}</h1>
                </div>
                <nav class=\"hidden md:flex space-x-6\">
                    <a href=\"index.php\" class=\"text-gray-600 hover:text-blue-600 transition-colors\">Home</a>
                    <a href=\"about.php\" class=\"text-gray-600 hover:text-blue-600 transition-colors\">About</a>
                    <a href=\"contact.php\" class=\"text-gray-600 hover:text-blue-600 transition-colors\">Contact</a>
                    <a href=\"donate\" class=\"bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors\">Donate</a>
                </nav>
            </div>
        </div>
    </header>
";

        $footer = "
    <!-- Footer -->
    <footer class=\"bg-gray-800 text-white py-8\">
        <div class=\"container mx-auto px-4\">
            <div class=\"grid grid-cols-1 md:grid-cols-3 gap-8\">
                <div>
                    <h3 class=\"text-xl font-bold mb-4\">{$ngo->name}</h3>
                    <p class=\"text-gray-300\">{$ngo->description}</p>
                </div>
                <div>
                    <h4 class=\"text-lg font-semibold mb-4\">Contact Info</h4>
                    <p class=\"text-gray-300\"><i class=\"fas fa-envelope mr-2\"></i>{$ngo->email}</p>
                    <p class=\"text-gray-300\"><i class=\"fas fa-phone mr-2\"></i>{$ngo->phone}</p>
                    <p class=\"text-gray-300\"><i class=\"fas fa-map-marker-alt mr-2\"></i>{$ngo->address}</p>
                </div>
                <div>
                    <h4 class=\"text-lg font-semibold mb-4\">Focus Areas</h4>
                    <p class=\"text-gray-300\">{$focusAreas}</p>
                </div>
            </div>
            <div class=\"border-t border-gray-700 mt-8 pt-8 text-center\">
                <p class=\"text-gray-400\">&copy; " . date('Y') . " {$ngo->name}. All rights reserved.</p>
                <p class=\"text-gray-400 text-sm mt-2\">Powered by <a href=\"https://fevourd-k.org\" class=\"text-blue-400 hover:text-blue-300\">FEVOURD-K</a></p>
            </div>
        </div>
    </footer>
</body>
</html>";

        if ($page === 'home') {
            $content = "
    <!-- Hero Section -->
    <section class=\"bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20\">
        <div class=\"container mx-auto px-4 text-center\">
            <h2 class=\"text-4xl md:text-5xl font-bold mb-4\">Welcome to {$ngo->name}</h2>
            <p class=\"text-xl mb-8 max-w-3xl mx-auto\">{$ngo->description}</p>
            <a href=\"donate\" class=\"bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors\">Support Our Cause</a>
        </div>
    </section>

    <!-- About Section -->
    <section class=\"py-16\">
        <div class=\"container mx-auto px-4\">
            <div class=\"text-center mb-12\">
                <h3 class=\"text-3xl font-bold text-gray-800 mb-4\">About Us</h3>
                <p class=\"text-lg text-gray-600 max-w-3xl mx-auto\">{$ngo->description}</p>
            </div>
            
            <div class=\"grid grid-cols-1 md:grid-cols-3 gap-8 mt-12\">
                <div class=\"text-center p-6 bg-white rounded-lg shadow-lg\">
                    <i class=\"fas fa-heart text-4xl text-red-500 mb-4\"></i>
                    <h4 class=\"text-xl font-semibold mb-2\">Our Mission</h4>
                    <p class=\"text-gray-600\">Making a positive impact in communities through dedicated service and compassion.</p>
                </div>
                <div class=\"text-center p-6 bg-white rounded-lg shadow-lg\">
                    <i class=\"fas fa-bullseye text-4xl text-blue-500 mb-4\"></i>
                    <h4 class=\"text-xl font-semibold mb-2\">Our Vision</h4>
                    <p class=\"text-gray-600\">Creating sustainable change and empowering communities for a better future.</p>
                </div>
                <div class=\"text-center p-6 bg-white rounded-lg shadow-lg\">
                    <i class=\"fas fa-users text-4xl text-green-500 mb-4\"></i>
                    <h4 class=\"text-xl font-semibold mb-2\">Our Values</h4>
                    <p class=\"text-gray-600\">Integrity, compassion, and commitment to excellence in all we do.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class=\"bg-blue-600 text-white py-16\">
        <div class=\"container mx-auto px-4 text-center\">
            <h3 class=\"text-3xl font-bold mb-4\">Make a Difference Today</h3>
            <p class=\"text-xl mb-8\">Your support helps us continue our mission and serve more communities.</p>
            <a href=\"donate\" class=\"bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors\">Donate Now</a>
        </div>
    </section>";
        } elseif ($page === 'about') {
            $content = "
    <!-- About Hero -->
    <section class=\"bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16\">
        <div class=\"container mx-auto px-4 text-center\">
            <h2 class=\"text-4xl font-bold mb-4\">About {$ngo->name}</h2>
            <p class=\"text-xl max-w-3xl mx-auto\">Learn more about our mission, vision, and the work we do in the community.</p>
        </div>
    </section>

    <!-- About Content -->
    <section class=\"py-16\">
        <div class=\"container mx-auto px-4\">
            <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-12 items-center\">
                <div>
                    <h3 class=\"text-3xl font-bold text-gray-800 mb-6\">Our Story</h3>
                    <p class=\"text-lg text-gray-600 mb-6\">{$ngo->description}</p>
                    <p class=\"text-gray-600 mb-6\">We are committed to making a positive impact in the communities we serve. Through our dedicated programs and services, we strive to address critical needs and create lasting change.</p>
                    <div class=\"grid grid-cols-2 gap-4 mt-8\">
                        <div class=\"text-center p-4 bg-blue-50 rounded-lg\">
                            <i class=\"fas fa-calendar text-blue-600 text-2xl mb-2\"></i>
                            <p class=\"font-semibold\">Founded</p>
                            <p class=\"text-gray-600\">" . ($ngo->year_established ?? 'Unknown') . "</p>
                        </div>
                        <div class=\"text-center p-4 bg-green-50 rounded-lg\">
                            <i class=\"fas fa-users text-green-600 text-2xl mb-2\"></i>
                            <p class=\"font-semibold\">Team Size</p>
                            <p class=\"text-gray-600\">" . ($ngo->team_size ?? 'Growing') . "</p>
                        </div>
                    </div>
                </div>
                <div class=\"text-center\">
                    <img src=\"{$logoUrl}\" alt=\"{$ngo->name}\" class=\"rounded-lg shadow-xl mx-auto\">
                </div>
            </div>
            
            <div class=\"mt-16\">
                <h3 class=\"text-3xl font-bold text-gray-800 mb-8 text-center\">Our Focus Areas</h3>
                <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6\">
                    {$this->generateFocusAreaCards($ngo->focus_areas)}
                </div>
            </div>
        </div>
    </section>";
        } elseif ($page === 'contact') {
            $content = "
    <!-- Contact Hero -->
    <section class=\"bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16\">
        <div class=\"container mx-auto px-4 text-center\">
            <h2 class=\"text-4xl font-bold mb-4\">Contact Us</h2>
            <p class=\"text-xl max-w-3xl mx-auto\">Get in touch with us to learn more about our work or to get involved.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class=\"py-16\">
        <div class=\"container mx-auto px-4\">
            <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-12\">
                <div>
                    <h3 class=\"text-2xl font-bold text-gray-800 mb-6\">Get in Touch</h3>
                    <form class=\"space-y-6\">
                        <div>
                            <label class=\"block text-gray-700 mb-2\">Name *</label>
                            <input type=\"text\" class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500\" required>
                        </div>
                        <div>
                            <label class=\"block text-gray-700 mb-2\">Email *</label>
                            <input type=\"email\" class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500\" required>
                        </div>
                        <div>
                            <label class=\"block text-gray-700 mb-2\">Phone</label>
                            <input type=\"tel\" class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500\">
                        </div>
                        <div>
                            <label class=\"block text-gray-700 mb-2\">Message *</label>
                            <textarea rows=\"5\" class=\"w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500\" required></textarea>
                        </div>
                        <button type=\"submit\" class=\"w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors\">Send Message</button>
                    </form>
                </div>
                <div>
                    <h3 class=\"text-2xl font-bold text-gray-800 mb-6\">Contact Information</h3>
                    <div class=\"space-y-4\">
                        <div class=\"flex items-center space-x-4\">
                            <i class=\"fas fa-envelope text-blue-600 text-xl\"></i>
                            <div>
                                <p class=\"font-semibold\">Email</p>
                                <p class=\"text-gray-600\">{$ngo->email}</p>
                            </div>
                        </div>
                        <div class=\"flex items-center space-x-4\">
                            <i class=\"fas fa-phone text-blue-600 text-xl\"></i>
                            <div>
                                <p class=\"font-semibold\">Phone</p>
                                <p class=\"text-gray-600\">{$ngo->phone}</p>
                            </div>
                        </div>
                        <div class=\"flex items-center space-x-4\">
                            <i class=\"fas fa-map-marker-alt text-blue-600 text-xl\"></i>
                            <div>
                                <p class=\"font-semibold\">Address</p>
                                <p class=\"text-gray-600\">{$ngo->address}</p>
                            </div>
                        </div>
                        " . ($ngo->website ? "
                        <div class=\"flex items-center space-x-4\">
                            <i class=\"fas fa-globe text-blue-600 text-xl\"></i>
                            <div>
                                <p class=\"font-semibold\">Website</p>
                                <p class=\"text-gray-600\"><a href=\"{$ngo->website}\" target=\"_blank\" class=\"text-blue-600 hover:underline\">{$ngo->website}</a></p>
                            </div>
                        </div>" : '') . "
                    </div>
                </div>
            </div>
        </div>
    </section>";
        }

        return $header . $content . $footer;
    }

    /**
     * Generate focus area cards for about page
     */
    private function generateFocusAreaCards($focusAreas)
    {
        if (!is_array($focusAreas)) {
            return '';
        }

        $cards = '';
        $icons = [
            'Education' => 'fa-graduation-cap',
            'Healthcare' => 'fa-heartbeat',
            'Environment' => 'fa-leaf',
            'Women Empowerment' => 'fa-female',
            'Child Welfare' => 'fa-child',
            'Rural Development' => 'fa-seedling',
            'Disaster Relief' => 'fa-life-ring',
            'Agriculture' => 'fa-tractor',
            'Skill Development' => 'fa-tools',
            'Senior Citizens' => 'fa-user-friends'
        ];

        foreach ($focusAreas as $area) {
            $icon = $icons[$area] ?? 'fa-star';
            $cards .= "
                    <div class=\"text-center p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow\">
                        <i class=\"fas {$icon} text-4xl text-blue-600 mb-4\"></i>
                        <h4 class=\"text-xl font-semibold mb-2\">{$area}</h4>
                        <p class=\"text-gray-600\">Dedicated programs and initiatives in {$area}.</p>
                    </div>";
        }

        return $cards;
    }

    /**
     * Get volunteer skills
     */
    private function getVolunteerSkills()
    {
        return [
            ['id' => 1, 'name' => 'Teaching', 'category' => 'Education'],
            ['id' => 2, 'name' => 'Healthcare', 'category' => 'Medical'],
            ['id' => 3, 'name' => 'Web Development', 'category' => 'Technology'],
            ['id' => 4, 'name' => 'Graphic Design', 'category' => 'Creative'],
            ['id' => 5, 'name' => 'Content Writing', 'category' => 'Communication'],
            ['id' => 6, 'name' => 'Event Management', 'category' => 'Operations'],
            ['id' => 7, 'name' => 'Fundraising', 'category' => 'Development'],
            ['id' => 8, 'name' => 'Social Media', 'category' => 'Marketing'],
            ['id' => 9, 'name' => 'Photography', 'category' => 'Creative'],
            ['id' => 10, 'name' => 'Data Analysis', 'category' => 'Technology'],
            ['id' => 11, 'name' => 'Project Management', 'category' => 'Operations'],
            ['id' => 12, 'name' => 'Counseling', 'category' => 'Social Work'],
        ];
    }
}
