<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\MultiRoleRegistrationController;
use App\Http\Controllers\NGOController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CSRController;

// Static Pages
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs');
Route::get('/team', [WelcomeController::class, 'team'])->name('team');
Route::get('/events', [WelcomeController::class, 'events'])->name('events');
Route::get('/gallery', [WelcomeController::class, 'gallery'])->name('gallery');
Route::get('/partners', [WelcomeController::class, 'partners'])->name('partners');
Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');
Route::get('/digitalization', [WelcomeController::class, 'digitalization'])->name('digitalization');
Route::get('/legal-status', [WelcomeController::class, 'legalStatus'])->name('legal-status');

// Donation page
Route::get('/donate', [DonationController::class, 'donate'])->name('donate');

// Legal pages
Route::get('/terms', [WelcomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [WelcomeController::class, 'privacy'])->name('privacy');
Route::get('/accessibility', [WelcomeController::class, 'accessibility'])->name('accessibility');

// Focus areas
Route::get('/focus/children', [WelcomeController::class, 'focusChildren'])->name('focus.children');
Route::get('/focus/environment', [WelcomeController::class, 'focusEnvironment'])->name('focus.environment');
Route::get('/focus/community', [WelcomeController::class, 'focusCommunity'])->name('focus.community');
Route::get('/focus/disability', [WelcomeController::class, 'focusDisability'])->name('focus.disability');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/test', function () {
    return Inertia::render('Auth/Test');
})->name('test');
    Route::get('/register', [MultiRoleRegistrationController::class, 'selectUserType'])->name('register.select-type');
    Route::get('/register/{userType}', [MultiRoleRegistrationController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/register/individual', [MultiRoleRegistrationController::class, 'registerIndividual'])->name('register.individual');
    Route::post('/register/ngo', [MultiRoleRegistrationController::class, 'registerNGO'])->name('register.ngo');
    Route::post('/register/ngo-chat', [MultiRoleRegistrationController::class, 'registerNGOChat'])->name('register.ngo-chat');
    Route::post('/register/corporate', [MultiRoleRegistrationController::class, 'registerCorporate'])->name('register.corporate');
    Route::get('/csr', [CSRController::class, 'index'])->name('csr.index');
    Route::get('/csr/register', [CSRController::class, 'create'])->name('csr.create');
    Route::post('/csr/register', [CSRController::class, 'store'])->name('csr.store');
    Route::post('/register/volunteer', [MultiRoleRegistrationController::class, 'registerVolunteer'])->name('register.volunteer');
    Route::post('/auth/send-otp', [MultiRoleRegistrationController::class, 'sendOTP'])->name('auth.send-otp');
    
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Profile and settings
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    
    Route::get('/dashboard', function () {
        // Redirect admins to admin dashboard
        if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('state_admin')) {
            return redirect('/admin/dashboard');
        }
        // Redirect NGO users to NGO dashboard
        if (auth()->user()->hasRole('ngo_admin') || auth()->user()->hasRole('ngo_staff')) {
            return redirect('/ngo/dashboard');
        }
        // Redirect CSR users to CSR dashboard
        if (auth()->user()->hasRole('corporate_csr_manager')) {
            return redirect('/csr/dashboard');
        }
        // Redirect donors to donor dashboard
        if (auth()->user()->hasRole('donor')) {
            return redirect('/donor/dashboard');
        }
        return inertia('Dashboard');
    })->name('dashboard');
    
    // NGO Dashboard Routes
    Route::middleware(['role:ngo_admin|ngo_staff'])->prefix('ngo')->name('ngo.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\NGO\NGODashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [\App\Http\Controllers\NGO\NGODashboardController::class, 'profile'])->name('profile');
        Route::get('/campaigns', [\App\Http\Controllers\NGO\NGODashboardController::class, 'campaigns'])->name('campaigns');
        Route::get('/donations', [\App\Http\Controllers\NGO\NGODashboardController::class, 'donations'])->name('donations');
        Route::get('/documents', [\App\Http\Controllers\NGO\NGODashboardController::class, 'documents'])->name('documents');
        Route::get('/banking', [\App\Http\Controllers\NGO\NGODashboardController::class, 'banking'])->name('banking');
    });
});

// Public NGO and Campaign routes
Route::get('/ngos', [NGOController::class, 'index'])->name('ngos.index');
Route::get('/ngos/{ngo:slug}', [NGOController::class, 'show'])->name('ngos.show');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');

// Public donors route
Route::get('/donors', [\App\Http\Controllers\DonorController::class, 'index'])->name('donors.index');

// Protected routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // NGO management
    Route::resource('ngos', NGOController::class)->except(['index', 'show']);
    
    // Campaign management
    Route::resource('campaigns', CampaignController::class)->except(['index', 'show']);
    
    // Donation routes
    Route::post('/donations/process', [DonationController::class, 'process'])->name('donations.process');
    Route::get('/donations/success', [DonationController::class, 'success'])->name('donations.success');
    Route::get('/donations/failure', [DonationController::class, 'failure'])->name('donations.failure');
});

Route::get('/test-auth', function () {
    return response()->json([
        'user' => auth()->user(),
        'roles' => auth()->user() ? auth()->user()->roles->pluck('name') : [],
    ]);
})->middleware('auth');

// Admin routes
Route::middleware(['auth', 'role:super_admin|state_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/test', function () {
        return inertia('Admin/Test');
    })->name('test');
    Route::post('/ngos', [NGOController::class, 'store'])->name('ngos.store');
    Route::get('/ngos/{ngo}', [NGOController::class, 'show'])->name('ngos.show');
    Route::get('/ngos/{ngo}/edit', [NGOController::class, 'edit'])->name('ngos.edit');
    Route::put('/ngos/{ngo}', [NGOController::class, 'update'])->name('ngos.update');
    Route::delete('/ngos/{ngo}', [NGOController::class, 'destroy'])->name('ngos.destroy');
    
    // Campaigns management
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    
    // Donations management
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    
    // Programs management
    Route::resource('programs', AdminProgramController::class);
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset-theme', [SettingsController::class, 'resetTheme'])->name('settings.reset-theme');
    Route::get('/settings/export', [SettingsController::class, 'exportSettings'])->name('settings.export');
    Route::post('/settings/import', [SettingsController::class, 'importSettings'])->name('settings.import');
    
    Route::get('/ngos/pending', [NGOController::class, 'pending'])->name('ngos.pending');
    Route::post('/ngos/{ngo}/verify', [NGOController::class, 'verify'])->name('ngos.verify');
});

// CSR Routes
Route::middleware(['auth', 'role:corporate_csr_manager'])->prefix('csr')->name('csr.')->group(function () {
    Route::get('/dashboard', [CSRController::class, 'dashboard'])->name('dashboard');
});

// Donor Routes
Route::middleware(['auth', 'role:donor'])->prefix('donor')->name('donor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Donor\DonorDashboardController::class, 'index'])->name('dashboard');
});
