<?php

namespace App\Support;

/**
 * Single source of truth for the NGO help center / "Disha" help bot.
 *
 * Real-platform facts only — every article describes how the FevourdK NGO
 * workspace actually works. No DB, no migration: edit this file to edit help.
 *
 * Article body is light, trusted HTML (we author it here, never user input),
 * rendered with v-html on the client.
 */
class HelpCenter
{
    /**
     * Categories used for the filter chips. `key` matches each article's `category`.
     */
    public static function categories(): array
    {
        return [
            ['key' => 'getting-started', 'label' => 'Getting started', 'icon' => '🚀'],
            ['key' => 'website',         'label' => 'Your website',    'icon' => '🌐'],
            ['key' => 'campaigns',       'label' => 'Campaigns',       'icon' => '🎯'],
            ['key' => 'donations',       'label' => 'Donations',       'icon' => '💚'],
            ['key' => 'money',           'label' => 'Money & ledger',  'icon' => '📒'],
            ['key' => 'documents',       'label' => 'Documents',       'icon' => '📄'],
            ['key' => 'teams',           'label' => 'Field & teams',   'icon' => '📍'],
            ['key' => 'community',       'label' => 'Community feed',  'icon' => '📣'],
            ['key' => 'trust',           'label' => 'Getting verified', 'icon' => '✅'],
        ];
    }

    /**
     * All help articles. `keywords` powers fuzzy search; `related` are slugs.
     */
    public static function articles(): array
    {
        return [
            [
                'slug' => 'welcome',
                'category' => 'getting-started',
                'icon' => '🚀',
                'title' => 'Welcome to your NGO workspace',
                'excerpt' => 'A quick map of your dashboard and where everything lives.',
                'keywords' => 'start begin overview dashboard home tour basics intro',
                'body' => <<<'HTML'
<p>Your workspace is the control room for your organisation on FevourdK. From the dashboard you can reach every tool from the left menu (tap <strong>Menu</strong> on mobile).</p>
<ul>
  <li><strong>Dashboard</strong> — your headline numbers and quick links.</li>
  <li><strong>Campaigns</strong> — focused fundraising appeals.</li>
  <li><strong>Donations</strong> — every gift you receive.</li>
  <li><strong>Finance &amp; ledger</strong> — transparent money in and out.</li>
  <li><strong>Digitalization</strong> — edit your public website, no code.</li>
  <li><strong>Field &amp; HR</strong> — your team and on-the-ground work.</li>
</ul>
<p>New to a page? Tap the <strong>Disha help bot</strong> (bottom-right) and choose <em>“Take a tour of this page.”</em></p>
HTML,
                'related' => ['edit-website', 'first-campaign', 'get-verified'],
            ],
            [
                'slug' => 'edit-website',
                'category' => 'website',
                'icon' => '🌐',
                'title' => 'Edit your public website (no code)',
                'excerpt' => 'Change your words, photos, and colours with a live preview.',
                'keywords' => 'website edit digitalization microsite site design logo colour photo preview no-code builder',
                'body' => <<<'HTML'
<p>Open <strong>Digitalization</strong> from the menu. You will see plain-language sections on the left and your real website updating live on the right.</p>
<ol>
  <li>Open a section — <em>Look &amp; feel, Welcome, Mission &amp; vision, Key facts, About, What you do, Stories, Donations, Contact</em>.</li>
  <li>Change the text, pick an icon, upload a photo, or choose a colour.</li>
  <li>Press <strong>Save</strong>. The preview reloads with your changes — that is exactly what visitors see.</li>
</ol>
<p>Your logo and colours flow through the whole site automatically. You never touch code.</p>
HTML,
                'related' => ['welcome', 'get-verified'],
            ],
            [
                'slug' => 'first-campaign',
                'category' => 'campaigns',
                'icon' => '🎯',
                'title' => 'Run your first campaign',
                'excerpt' => 'Raise for a specific cause with its own goal and story.',
                'keywords' => 'campaign fundraise appeal goal target raise cause project create',
                'body' => <<<'HTML'
<p>A campaign is a focused appeal — a borewell, school kits, a medical fund — with its own goal and progress bar.</p>
<ol>
  <li>Go to <strong>Campaigns</strong> and start a new one.</li>
  <li>Add a clear title, a goal amount, and an honest story with photos.</li>
  <li>Publish — donors can give to it directly from your public site.</li>
</ol>
<p>Track each campaign’s progress against its goal and post updates to keep supporters engaged.</p>
HTML,
                'related' => ['receive-donations', 'post-update'],
            ],
            [
                'slug' => 'receive-donations',
                'category' => 'donations',
                'icon' => '💚',
                'title' => 'Receive & track donations',
                'excerpt' => 'How money reaches you and where to see every gift.',
                'keywords' => 'donation donate payment gateway razorpay receive money gift receipt track donor',
                'body' => <<<'HTML'
<p><strong>The platform never holds your money.</strong> Donations flow through <em>your own</em> payment gateway straight to your account. FevourdK just shows you the record.</p>
<ul>
  <li>Connect your gateway under <strong>Banking &amp; gateways</strong> (e.g. Razorpay).</li>
  <li>Every gift appears under <strong>Donations</strong> with donor, amount, and date.</li>
  <li>Donations post to your <strong>ledger</strong> as credits, keeping your books transparent.</li>
</ul>
HTML,
                'related' => ['ledger-transparency', 'first-campaign'],
            ],
            [
                'slug' => 'ledger-transparency',
                'category' => 'money',
                'icon' => '📒',
                'title' => 'The ledger & transparency',
                'excerpt' => 'Your single source of financial truth — every rupee in and out.',
                'keywords' => 'ledger transparency money finance balance credit debit cashbook accounts expense income reconcile',
                'body' => <<<'HTML'
<p>The <strong>ledger</strong> records every credit and debit in order, with a running balance. Nothing is hidden — that is what builds donor trust.</p>
<ul>
  <li><strong>Credits</strong> — donations and other income.</li>
  <li><strong>Debits</strong> — salaries, vendor payments, and approved expense claims.</li>
  <li>Many entries post <em>automatically</em> from donations and finance actions; you can also record entries by hand.</li>
</ul>
<p>The <strong>Finance hub</strong> rolls this up into live balances and lets you pay staff or vendors and approve claims.</p>
HTML,
                'related' => ['receive-donations', 'pay-staff'],
            ],
            [
                'slug' => 'pay-staff',
                'category' => 'money',
                'icon' => '↗',
                'title' => 'Pay staff, vendors & expense claims',
                'excerpt' => 'Outbound payments that auto-post to your ledger.',
                'keywords' => 'pay salary stipend vendor payment outbound expense claim reimburse finance approve',
                'body' => <<<'HTML'
<p>From the <strong>Finance hub</strong>:</p>
<ul>
  <li><strong>Pay staff / vendors</strong> — record salaries, stipends, and vendor payments.</li>
  <li><strong>Expense claims</strong> — team members submit claims; when you approve, the amount auto-posts to the ledger as a debit.</li>
</ul>
<p>Every outbound payment keeps your transparent books in sync — no separate bookkeeping needed.</p>
HTML,
                'related' => ['ledger-transparency'],
            ],
            [
                'slug' => 'documents',
                'category' => 'documents',
                'icon' => '📄',
                'title' => 'Manage your documents',
                'excerpt' => 'Keep registration and compliance papers in one safe place.',
                'keywords' => 'documents files upload registration 80g 12a fcra compliance certificate store',
                'body' => <<<'HTML'
<p>Use <strong>Documents</strong> to store your organisation’s key papers — registration certificate, 80G/12A, FCRA, and other compliance documents.</p>
<p>Keeping these complete and current speeds up <strong>verification</strong> and reassures donors and CSR partners.</p>
HTML,
                'related' => ['get-verified'],
            ],
            [
                'slug' => 'field-teams',
                'category' => 'teams',
                'icon' => '📍',
                'title' => 'Field teams & site visits',
                'excerpt' => 'Assign tasks, see them on the map, track live routes.',
                'keywords' => 'field site visit task assign map gps route team tracker on-ground hr employees leave',
                'body' => <<<'HTML'
<p>Coordinate work on the ground from <strong>Site visits &amp; tasks</strong>:</p>
<ul>
  <li>Create a task and assign it to a team member.</li>
  <li>See tasks and visits on the map to plan routes and coverage.</li>
  <li>The <strong>Field tracker</strong> records the live GPS trail of a visit.</li>
</ul>
<p>Your people, roles, and leave live under <strong>HRMS</strong> — one login for the whole team.</p>
HTML,
                'related' => ['welcome'],
            ],
            [
                'slug' => 'post-update',
                'category' => 'community',
                'icon' => '📣',
                'title' => 'Post updates to the community feed',
                'excerpt' => 'Share impact and grow your reach and followers.',
                'keywords' => 'post update feed community share story photo reach followers feed studio publish',
                'body' => <<<'HTML'
<p>Sharing your work grows your reach and follower count — which lifts the numbers on your dashboard.</p>
<ul>
  <li><strong>Post an update</strong> — a quick message straight to the public feed.</li>
  <li><strong>Feed Studio</strong> — compose richer posts with photos and stories.</li>
</ul>
<p>Keep it human: what you did, who it helped, and a good photo. Supporters following your organisation see it instantly.</p>
HTML,
                'related' => ['first-campaign', 'welcome'],
            ],
            [
                'slug' => 'get-verified',
                'category' => 'trust',
                'icon' => '✅',
                'title' => 'Getting verified',
                'excerpt' => 'Earn the verified badge and build donor trust.',
                'keywords' => 'verify verified badge trust approval review compliance documents profile complete',
                'body' => <<<'HTML'
<p>A <strong>verified</strong> badge tells donors and CSR partners your organisation is genuine. To get there:</p>
<ol>
  <li>Complete your <strong>profile</strong> — name, focus areas, contact, and description.</li>
  <li>Upload your key papers under <strong>Documents</strong> (registration, 80G/12A, etc.).</li>
  <li>Submit for review. Your status shows in the workspace header — <em>Under verification</em> until approved.</li>
</ol>
<p>Accurate, complete details get you verified faster.</p>
HTML,
                'related' => ['documents', 'edit-website'],
            ],
        ];
    }
}
