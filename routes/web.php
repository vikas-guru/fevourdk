<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\NgoFieldTaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MultiRoleRegistrationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CSRController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\NGO\FieldOperationsController;
use App\Http\Controllers\NGO\FinanceClaimsController;
use App\Http\Controllers\NGO\NgoFinanceController;
use App\Http\Controllers\NGO\HrOpsController;
use App\Http\Controllers\NGO\NGOFeedStudioController;
use App\Http\Controllers\NGO\WorkplaceSecurityController;
use App\Http\Controllers\NGO\NGOSocialConnectController;
use App\Http\Controllers\NGO\NGOWebsiteController;
use App\Http\Controllers\NGOController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/feeds/{post}', [FeedController::class, 'show'])
    ->whereNumber('post')
    ->name('feeds.show');
Route::post('/feeds/{post}/view', [FeedController::class, 'recordView'])
    ->whereNumber('post')
    ->middleware('throttle:180,1')
    ->name('feeds.record-view');

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
    Route::post('/register/ngo-chat/verify-registration', [MultiRoleRegistrationController::class, 'verifyNgoRegistration'])
        ->middleware('throttle:20,1')
        ->name('register.ngo-chat.verify-registration');
    Route::post('/register/ngo-chat/verify-otp', [MultiRoleRegistrationController::class, 'verifyNgoChatOtp'])
        ->middleware('throttle:30,1')
        ->name('register.ngo-chat.verify-otp');
    Route::post('/register/ngo-chat/draft', [MultiRoleRegistrationController::class, 'saveNgoChatDraft'])
        ->middleware('throttle:40,1')
        ->name('register.ngo-chat.draft.save');
    Route::get('/register/ngo-chat/draft-by-resume/{token}', [MultiRoleRegistrationController::class, 'getNgoChatDraftByResumeToken'])
        ->middleware('throttle:30,1')
        ->where('token', '[A-Za-z0-9]{32,128}')
        ->name('register.ngo-chat.draft.resume');
    Route::get('/register/ngo-chat/draft/{draftId}', [MultiRoleRegistrationController::class, 'getNgoChatDraft'])
        ->middleware('throttle:60,1')
        ->name('register.ngo-chat.draft.get');
    Route::post('/register/corporate', [MultiRoleRegistrationController::class, 'registerCorporate'])->name('register.corporate');
    Route::get('/csr', [CSRController::class, 'index'])->name('csr.index');
    Route::get('/csr/register', [CSRController::class, 'create'])->name('csr.create');
    Route::post('/csr/register', [CSRController::class, 'store'])->name('csr.store');
    Route::post('/register/volunteer', [MultiRoleRegistrationController::class, 'registerVolunteer'])->name('register.volunteer');
    // Per-phone OTP abuse is capped in OtpService (5 sends / hour / number). This limit is only
    // for coarse IP protection; 10/min was easy to hit during NGO chat + individual reg + retries.
    Route::post('/auth/send-otp', [MultiRoleRegistrationController::class, 'sendOTP'])
        ->middleware('throttle:60,1')
        ->name('auth.send-otp');

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
    Route::get('/feeds', [FeedController::class, 'index'])->name('feeds.index');
    Route::post('/feeds', [FeedController::class, 'store'])->name('feeds.store');
    Route::post('/feeds/{post}/react', [FeedController::class, 'react'])->name('feeds.react');
    Route::post('/feeds/{post}/comment', [FeedController::class, 'comment'])->name('feeds.comment');
    Route::post('/feeds/{post}/share', [FeedController::class, 'share'])->name('feeds.share');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('/dashboard', function () {
        // Redirect admins to admin dashboard
        if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('state_admin')) {
            return redirect('/admin/dashboard');
        }
        // Finance-only NGO users land on treasury
        if (
            auth()->user()->hasRole('ngo_finance')
            && ! auth()->user()->hasRole('ngo_admin')
            && ! auth()->user()->hasRole('ngo_staff')
        ) {
            return redirect('/ngo/finance');
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

    // NGO Dashboard Routes (finance officers use a scoped subset — see NgoFinanceWorkspaceScope)
    Route::middleware(['role:ngo_admin|ngo_staff|ngo_finance', 'ngo.finance.scope'])->prefix('ngo')->name('ngo.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\NGO\NGODashboardController::class, 'index'])->name('dashboard');
        Route::get('/post-update', [\App\Http\Controllers\NGO\NGODashboardController::class, 'postUpdate'])->name('post-update');
        Route::get('/feed-studio', [NGOFeedStudioController::class, 'index'])->name('feed-studio');
        Route::get('/analytics', [\App\Http\Controllers\NGO\NGODashboardController::class, 'analytics'])->name('analytics');
        Route::get('/profile', [\App\Http\Controllers\NGO\NGODashboardController::class, 'profile'])->name('profile');
        Route::get('/campaigns', [\App\Http\Controllers\NGO\NGODashboardController::class, 'campaigns'])->name('campaigns');
        Route::get('/donations', [\App\Http\Controllers\NGO\NGODashboardController::class, 'donations'])->name('donations');
        Route::get('/documents', [\App\Http\Controllers\NGO\NGODashboardController::class, 'documents'])->name('documents');
        Route::get('/banking', [NgoFinanceController::class, 'banking'])->name('banking');
        Route::get('/finance', [NgoFinanceController::class, 'hub'])->name('finance.hub');
        Route::get('/finance/activity', [NgoFinanceController::class, 'activity'])->name('finance.activity');
        Route::get('/finance/payments', [NgoFinanceController::class, 'payments'])->name('finance.payments');
        Route::post('/finance/payments', [NgoFinanceController::class, 'storePayment'])->name('finance.payments.store');
        Route::patch('/finance/payments/{payment}/paid', [NgoFinanceController::class, 'markPaymentPaid'])->name('finance.payments.paid');
        Route::put('/finance/preferences', [NgoFinanceController::class, 'updatePreferences'])->name('finance.preferences');
        Route::post('/finance/bank-accounts', [NgoFinanceController::class, 'storeBankAccount'])->name('bank-accounts.store');
        Route::put('/finance/bank-accounts/{bankAccount}', [NgoFinanceController::class, 'updateBankAccount'])->name('bank-accounts.update');
        Route::delete('/finance/bank-accounts/{bankAccount}', [NgoFinanceController::class, 'destroyBankAccount'])->name('bank-accounts.destroy');
        Route::get('/digitalization', [\App\Http\Controllers\NGO\NGODashboardController::class, 'digitalization'])->name('digitalization');
        Route::put('/digitalization', [\App\Http\Controllers\NGO\NGODashboardController::class, 'updateDigitalization'])->name('digitalization.update');
        Route::get('/ledger', [\App\Http\Controllers\NGO\NGODashboardController::class, 'ledger'])->name('ledger');
        Route::post('/ledger', [\App\Http\Controllers\NGO\NGODashboardController::class, 'storeLedgerEntry'])->name('ledger.store');

        Route::get('/field', [FieldOperationsController::class, 'hub'])->name('field.hub');
        Route::get('/field/app', [FieldOperationsController::class, 'app'])->name('field.app');
        Route::post('/field/tasks', [FieldOperationsController::class, 'storeTask'])->name('field.tasks.store');
        Route::patch('/field/tasks/{task}', [FieldOperationsController::class, 'updateTask'])->name('field.tasks.update');
        Route::post('/field/sessions', [FieldOperationsController::class, 'startSession'])->name('field.sessions.start');
        Route::post('/field/sessions/{session}/points', [FieldOperationsController::class, 'appendPoints'])
            ->middleware('throttle:120,1')
            ->name('field.sessions.points');
        Route::post('/field/sessions/{session}/end', [FieldOperationsController::class, 'endSession'])->name('field.sessions.end');
        Route::get('/field/sessions/{session}/trail', [FieldOperationsController::class, 'sessionTrail'])->name('field.sessions.trail');

        Route::get('/workplace-security', [WorkplaceSecurityController::class, 'index'])->name('workplace-security');
        Route::put('/workplace-security', [WorkplaceSecurityController::class, 'update'])->name('workplace-security.update');
        Route::post('/workplace-security/trusted-ips', [WorkplaceSecurityController::class, 'storeTrustedIp'])->name('workplace-security.trusted-ips.store');
        Route::delete('/workplace-security/trusted-ips/{trustedIp}', [WorkplaceSecurityController::class, 'destroyTrustedIp'])->name('workplace-security.trusted-ips.destroy');

        Route::get('/hr', [HrOpsController::class, 'index'])->name('hr.index');
        Route::get('/hr/team', [HrOpsController::class, 'team'])->name('hr.team');
        Route::post('/hr/employees', [HrOpsController::class, 'storeEmployee'])->name('hr.employees.store');
        Route::patch('/hr/members/{user}', [HrOpsController::class, 'updateMember'])->name('hr.members.update');
        Route::patch('/hr/members/{user}/active', [HrOpsController::class, 'updateMemberActive'])->name('hr.members.active');
        Route::post('/hr/leave-types', [HrOpsController::class, 'storeLeaveType'])->name('hr.leave-types.store');
        Route::delete('/hr/leave-types/{leaveType}', [HrOpsController::class, 'destroyLeaveType'])->name('hr.leave-types.destroy');
        Route::get('/hr/leaves', [HrOpsController::class, 'leaves'])->name('hr.leaves');
        Route::post('/hr/leaves', [HrOpsController::class, 'storeLeaveRequest'])->name('hr.leaves.store');
        Route::patch('/hr/leaves/{leaveRequest}', [HrOpsController::class, 'decideLeave'])->name('hr.leaves.decide');

        Route::get('/finance/claims', [FinanceClaimsController::class, 'index'])->name('finance.claims');
        Route::post('/finance/claims', [FinanceClaimsController::class, 'store'])->name('finance.claims.store');
        Route::patch('/finance/claims/{claim}', [FinanceClaimsController::class, 'decide'])->name('finance.claims.decide');
        Route::get('/website-preview', [NGOWebsiteController::class, 'preview'])->name('website.preview');
        Route::get('/social-connect', [NGOSocialConnectController::class, 'index'])->name('social-connect');
        Route::put('/social-connect/{platform}', [NGOSocialConnectController::class, 'update'])->name('social-connect.update');
        Route::delete('/social-connect/{platform}', [NGOSocialConnectController::class, 'destroy'])->name('social-connect.destroy');
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
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/field-tasks', [NgoFieldTaskController::class, 'index'])->name('field-tasks.index');
    Route::get('/field-tasks/create', [NgoFieldTaskController::class, 'create'])->name('field-tasks.create');
    Route::get('/field-tasks/staff/{ngo}', [NgoFieldTaskController::class, 'staffForNgo'])->name('field-tasks.staff');
    Route::post('/field-tasks', [NgoFieldTaskController::class, 'store'])->name('field-tasks.store');
    Route::get('/payments', [PaymentSettingsController::class, 'index'])->name('payments.index');
    Route::put('/payments', [PaymentSettingsController::class, 'update'])->name('payments.update');
    Route::get('/test', function () {
        return inertia('Admin/Test');
    })->name('test');
    Route::get('/ngos', [NGOController::class, 'index'])->name('ngos.index');
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
    Route::post('/ngos/{ngo}/reject', [NGOController::class, 'reject'])->name('ngos.reject');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/toggle-block', [AdminUserController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

    Route::get('/individuals', [AdminUserController::class, 'individuals'])->name('individuals.index');
    Route::get('/individuals/{user}', [AdminUserController::class, 'showIndividual'])->name('individuals.show');
    Route::post('/individuals/{user}/approve', [AdminUserController::class, 'approveIndividual'])->name('individuals.approve');
});

// CSR Routes
Route::middleware(['auth', 'role:corporate_csr_manager'])->prefix('csr')->name('csr.')->group(function () {
    Route::get('/dashboard', [CSRController::class, 'dashboard'])->name('dashboard');
});

// Donor Routes
Route::middleware(['auth', 'role:donor'])->prefix('donor')->name('donor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Donor\DonorDashboardController::class, 'index'])->name('dashboard');
});

// Public NGO website microsites: /{ngo-slug}
Route::get('/{ngoSlug}', [NGOWebsiteController::class, 'showBySlug'])
    ->where('ngoSlug', '^(?!admin|api|storage|build|assets|icons|login|register|dashboard|ngo|csr|donor|campaigns|donate|about|team|events|gallery|partners|contact|terms|privacy|accessibility|focus|programs|test|test-auth).+$')
    ->name('ngo.website.show');
