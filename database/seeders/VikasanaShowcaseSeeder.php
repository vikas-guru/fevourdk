<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\FeedPost;
use App\Models\NGO;
use App\Models\NgoSupporter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Brings the Vikasana NGO to life for demos: rich verified profile + microsite,
 * real campaigns, a living activity feed, and a social graph of followers/supporters
 * across every role (donor, volunteer, general users).
 *
 * Idempotent — safe to run repeatedly. Assumes Vikasana was registered via the
 * NGO signup flow (email: vikasana@fevourdk.org). Falls back to creating it.
 */
class VikasanaShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve the NGO the registered admin actually logs into (users.ngo_id),
        // NOT a fuzzy name match — otherwise data lands on a stray duplicate.
        $admin = User::where('email', 'vikasana@fevourdk.org')->first();

        $ngo = ($admin && $admin->ngo_id) ? NGO::find($admin->ngo_id) : null;
        $ngo = $ngo ?: NGO::where('slug', 'vikasana')->first();

        if (! $ngo) {
            $ngo = new NGO;
            $ngo->name = 'Vikasana Institute for Rural and Urban Development';
            $ngo->email = 'vikasana@fevourdk.org';
            $ngo->phone = '9448464171';
        }

        // Free the 'vikasana' slug + hide any OTHER Vikasana duplicates from discovery.
        NGO::where('name', 'like', 'Vikasana%')
            ->when($ngo->exists, fn ($q) => $q->where('id', '!=', $ngo->id))
            ->update(['slug' => null, 'is_active' => false, 'verification_status' => 'pending']);

        $this->fillProfile($ngo);

        $admin = $admin ?? $this->ensureAdmin($ngo);
        // Make sure the admin is linked to this NGO.
        if ($admin->ngo_id !== $ngo->id) {
            $admin->ngo_id = $ngo->id;
            $admin->save();
        }

        $campaigns = $this->seedCampaigns($ngo);
        $this->seedFeed($ngo, $admin);
        $supporters = $this->seedSupporters($ngo);
        $this->seedDonations($ngo, $campaigns, $supporters['donor']);

        $this->command?->info('Vikasana showcase ready: '.$ngo->name.' (/'.$ngo->slug.')');
    }

    private function fillProfile(NGO $ngo): void
    {
        $ngo->name = 'Vikasana Institute for Rural and Urban Development';
        $ngo->slug = 'vikasana';
        $ngo->description = 'Vikasana Institute for Rural and Urban Development is a Mandya-based voluntary organisation founded on 30 December 1984, working across Mandya, Kodagu and Ramanagara districts. Guided by the motto "Inclusion through productivity", Vikasana runs 15+ development programmes spanning agriculture, water, health, environment, housing, human rights and women\'s empowerment — reaching 100+ villages over 39 years of grassroots service.';
        $ngo->founder_name = 'Keshavan Namboodari';
        $ngo->founder_email = 'vikasanairud@gmail.com';
        $ngo->founder_phone = '9448464171';
        $ngo->cofounder_name = 'Mahesh Chandra Guru';
        $ngo->cofounder_email = 'guru@vikasana.org';
        $ngo->cofounder_phone = '7483159735';
        $ngo->registration_number = '94/84-85';
        if (empty($ngo->pan)) {
            $ngo->pan = 'AABTV0094K';
        }
        $ngo->address = '#1813, 1st Cross, Bandigowda Layout, Behind LIC Office, Mandya - 571401';
        // Locate the org in Mandya (not Bangalore) so the profile, card and map resolve correctly.
        $mandyaDistrict = \App\Models\District::where('name', 'Mandya')->first();
        $mandyaCity = \App\Models\City::where('name', 'Mandya')->first();
        if ($mandyaDistrict) {
            $ngo->district_id = $mandyaDistrict->id;
            $ngo->state_id = $mandyaDistrict->state_id;
        }
        if ($mandyaCity) {
            $ngo->city_id = $mandyaCity->id;
        }
        $ngo->theme_color = '#2e7d32';
        $ngo->logo = '/assets/vikasana/logos/vikasana-logo.png';
        $ngo->website_url = '/vikasana';
        $ngo->focus_areas = [
            'Agriculture Development',
            'Water Resources',
            'Health & Family Welfare',
            'Environment & Forests',
            'Housing & Sanitation',
            'Human Rights',
            "Women's Empowerment",
            'Child Welfare & Education',
        ];
        $ngo->verification_status = 'verified';
        $ngo->verified_at = now();
        $ngo->is_active = true;
        $ngo->digitalization_settings = ['microsite' => $this->micrositeContent()];
        $ngo->save();
    }

    private function micrositeContent(): array
    {
        return [
            'hero_subtitle' => 'Mandya · Karnataka · Since 1984',
            'hero_description' => 'For 39+ years Vikasana has worked shoulder-to-shoulder with rural and urban communities across Mandya, Kodagu and Ramanagara — turning "Inclusion through productivity" into water, food, health and dignity for thousands of families.',
            'slide1_s1_n' => '39+', 'slide1_s1_l' => 'Years of service',
            'slide1_s2_n' => '3', 'slide1_s2_l' => 'Districts',
            'slide1_s3_n' => '100+', 'slide1_s3_l' => 'Villages',
            'slide1_s4_n' => '15+', 'slide1_s4_l' => 'Programmes',
            'mission_subtitle' => 'Inclusion through productivity',
            'mission_description' => 'We design practical, locally-led programmes — from watershed management and FPOs to SHGs, health camps and child protection — that help marginalised communities build lasting livelihoods.',
            'vision_subtitle' => 'Be Human',
            'vision_description' => 'Humanity is the first step of social change. We envision resilient Karnataka communities able to shape their own progress with dignity and equity.',
            'impact_title' => 'Our impact', 'impact_subtitle' => '39 years on the ground',
            'impact_description' => '100+ villages reached, 100 COVID-19 relief kits distributed, multiple Childline school-awareness drives, and gender-equality programmes — delivered with government, banks and community partners.',
            'stat_1_h' => 'Registered', 'stat_1_p' => 'Reg. 94/84-85 · Societies Act 1960',
            'stat_2_h' => 'Compliance', 'stat_2_p' => '12A · 80G · FCRA',
            'stat_3_h' => 'Location', 'stat_3_p' => 'Mandya, Karnataka',
            'stat_4_h' => 'Status', 'stat_4_p' => 'Verified on FEVOURD-K',
            'about_section_title' => 'About Vikasana',
            'about_section_subtitle' => 'Four decades of grassroots development',
            'about_who_heading' => 'Who we are',
            'about_who_byline' => 'Founded 1984 · Led by Keshavan Namboodari & Mahesh Chandra Guru',
            'about_extra' => 'Vikasana is registered under the Karnataka Societies Registration Act 1960 (94/84-85) with 12A, 80G and FCRA approvals. Our executive committee is chaired by President Dr. R L Jagadish, with leadership across finance, programmes and field operations.',
            'about_vision_quote' => 'Be Human — Humanity is the first step of social change.',
            'donate_title' => 'Support Vikasana',
            'donate_subtitle' => 'Every contribution funds water, food security, health camps and child protection across rural Karnataka.',
            'donate_impact_blurb' => '₹2,500 supports a village health camp · ₹5,000 funds an SHG livelihood kit · ₹10,000 helps a watershed recharge effort. All donations are 80G tax-exempt.',
            'contact_intro' => 'Reach our Mandya headquarters to collaborate, volunteer, or partner on rural development.',
            'programs' => [
                ['title' => 'Agriculture & FPOs', 'body' => 'Sustainable farming, food processing and Farmer Producer Organisations that raise rural incomes.', 'icon' => 'fa-seedling'],
                ['title' => 'Water Resources', 'body' => 'Drinking water, watershed management and irrigation for drought-prone Mandya villages.', 'icon' => 'fa-tint'],
                ['title' => 'Health & Family Welfare', 'body' => 'Medical camps, nutrition and maternal-child health outreach.', 'icon' => 'fa-heartbeat'],
                ['title' => 'Environment & Forests', 'body' => 'Tree plantation, biodiversity and conservation drives with the Karnataka Police.', 'icon' => 'fa-tree'],
                ['title' => 'Housing & Sanitation', 'body' => 'Rural housing, sanitation and community centres.', 'icon' => 'fa-home'],
                ['title' => 'Human Rights', 'body' => 'Legal awareness, child rights and gender equality.', 'icon' => 'fa-gavel'],
                ['title' => "Women's Empowerment", 'body' => 'Self-Help Groups, capacity building and economic independence.', 'icon' => 'fa-hands-helping'],
                ['title' => 'Child Welfare & Education', 'body' => 'Childline awareness, school programmes and protection of child rights.', 'icon' => 'fa-graduation-cap'],
            ],
            'stories' => [
                ['title' => 'World Environment Day with Karnataka Police', 'body' => 'A community plantation and awareness drive at Doddakoppalu, Mandya, in partnership with the Karnataka Police Department and Gram Panchayat Kirugavalu.', 'category' => 'Environment', 'date' => 'Jun 2024', 'image' => '/assets/vikasana/activities/world-environment-day.jpg'],
                ['title' => 'COVID-19 Relief: 100 grocery kits', 'body' => 'During the pandemic Vikasana distributed 100 grocery kits to needy families and farm labourers across Gamanahalli, Chandagalu, Gandhinagar and Shankarpura.', 'category' => 'Relief', 'date' => '2021', 'image' => '/assets/vikasana/activities/covid-relief.jpg'],
                ['title' => 'Childline school awareness', 'body' => 'Child-rights education and protection sessions at GHPS Mahadevpura, S R Patna Taluk, in partnership with Childline India.', 'category' => 'Protection', 'date' => 'Ongoing', 'image' => '/assets/vikasana/activities/childline-awareness.jpg'],
                ['title' => 'Gender discrimination awareness', 'body' => 'An awareness programme reaching 50+ participants on gender equality and women\'s rights.', 'category' => 'Equality', 'date' => '2023', 'image' => '/assets/vikasana/activities/gender-discrimination.jpg'],
                ['title' => 'Clean water for Mandya villages', 'body' => 'Watershed management and drinking-water access work across drought-prone villages in Mandya district.', 'category' => 'Water', 'date' => 'Ongoing', 'image' => '/assets/vikasana/activities/water-resources.jpg'],
                ['title' => 'Village health camps', 'body' => 'Preventive health camps and nutrition outreach bringing care closer to rural families.', 'category' => 'Health', 'date' => 'Ongoing', 'image' => '/assets/vikasana/activities/health-camp.jpg'],
            ],
        ];
    }

    private function ensureAdmin(NGO $ngo): User
    {
        $admin = User::firstOrNew(['email' => 'vikasana@fevourdk.org']);
        $admin->name = 'Mahesh Chandra Guru';
        $admin->first_name = 'Mahesh';
        $admin->last_name = 'Chandra Guru';
        $admin->phone = '9448464171';
        $admin->password = Hash::make('Vikasana@123');
        $admin->user_type = 'ngo';
        $admin->ngo_id = $ngo->id;
        $admin->is_active = true;
        $admin->email_verified_at = now();
        $admin->phone_verified_at = now();
        $admin->save();
        try {
            $admin->assignRole('ngo_admin');
        } catch (\Throwable $e) {
        }

        return $admin;
    }

    /** @return array<int,Campaign> */
    private function seedCampaigns(NGO $ngo): array
    {
        $defs = [
            ['title' => 'Clean Drinking Water for Mandya Villages', 'img' => '/assets/vikasana/activities/water-resources.jpg', 'target' => 800000, 'raised' => 512000, 'donors' => 184, 'focus' => ['Water Resources'], 'featured' => true],
            ['title' => 'Sustainable Agriculture & FPO Support', 'img' => '/assets/vikasana/activities/agriculture-development.jpg', 'target' => 600000, 'raised' => 327500, 'donors' => 121, 'focus' => ['Agriculture Development'], 'featured' => false],
            ['title' => "Women's Empowerment through Self-Help Groups", 'img' => '/assets/vikasana/blog/women-empowerment.jpg', 'target' => 450000, 'raised' => 289000, 'donors' => 96, 'focus' => ["Women's Empowerment"], 'featured' => false],
            ['title' => 'Childline — Protecting Child Rights', 'img' => '/assets/vikasana/activities/childline-awareness.jpg', 'target' => 350000, 'raised' => 141000, 'donors' => 73, 'focus' => ['Child Welfare & Education'], 'featured' => false],
        ];

        $out = [];
        foreach ($defs as $d) {
            $c = Campaign::firstOrNew(['ngo_id' => $ngo->id, 'slug' => Str::slug($d['title'])]);
            $c->title = $d['title'];
            $c->description = 'Part of Vikasana\'s long-term work across Mandya, Kodagu and Ramanagara. '.$d['title'].' channels community and donor support into measurable rural impact, reported transparently on FEVOURD-K.';
            $c->featured_image = $d['img'];
            $c->target_amount = $d['target'];
            $c->raised_amount = $d['raised'];
            $c->donor_count = $d['donors'];
            $c->start_date = now()->subMonths(4);
            $c->end_date = now()->addMonths(3);
            $c->status = 'active';
            $c->focus_areas = $d['focus'];
            $c->is_featured = $d['featured'];
            $c->is_active = true;
            $c->save();
            $out[] = $c;
        }

        return $out;
    }

    private function seedFeed(NGO $ngo, User $admin): void
    {
        $posts = [
            ['title' => 'World Environment Day with Karnataka Police 🌱', 'body' => 'Today we joined hands with the Karnataka Police Department and Gram Panchayat Kirugavalu for a tree plantation and environmental awareness drive at Doddakoppalu, Mandya. Hundreds of saplings planted, hundreds of hearts committed to a greener Karnataka.', 'img' => '/assets/vikasana/activities/world-environment-day.jpg', 'views' => 1240, 'days' => 6],
            ['title' => '100 grocery kits reached families in need', 'body' => 'During the COVID-19 crisis, Vikasana distributed 100 grocery kits to farm labourers and vulnerable families across Gamanahalli, Chandagalu, Gandhinagar and Shankarpura. Thank you to every donor who made this possible. 🙏', 'img' => '/assets/vikasana/activities/covid-relief.jpg', 'views' => 2110, 'days' => 14],
            ['title' => 'Childline awareness at GHPS Mahadevpura', 'body' => 'Our team conducted a child-rights and protection awareness session at the Government Higher Primary School, Mahadevpura, S R Patna Taluk — in partnership with Childline India. Every child deserves to know their rights.', 'img' => '/assets/vikasana/activities/childline-awareness.jpg', 'views' => 860, 'days' => 21],
            ['title' => 'Standing up against gender discrimination', 'body' => 'A powerful awareness programme reaching 50+ participants on gender equality, women\'s rights and dignity. Change begins with a conversation.', 'img' => '/assets/vikasana/activities/gender-discrimination.jpg', 'views' => 940, 'days' => 30],
            ['title' => 'Bringing health camps closer to villages', 'body' => 'Preventive health screening, nutrition guidance and maternal-child care — our mobile health camps continue to serve remote Mandya villages where the nearest clinic is hours away.', 'img' => '/assets/vikasana/activities/health-camp.jpg', 'views' => 1380, 'days' => 40],
        ];

        foreach ($posts as $p) {
            $post = FeedPost::firstOrNew(['ngo_id' => $ngo->id, 'title' => $p['title']]);
            $post->user_id = $admin->id;
            $post->body = $p['body'];
            $post->image_url = $p['img'];
            $post->media = [['type' => 'image', 'path' => $p['img']]];
            $post->meta = ['source' => 'showcase'];
            $post->is_published = true;
            $post->views_count = $p['views'];
            $post->created_at = now()->subDays($p['days']);
            $post->updated_at = now()->subDays($p['days']);
            $post->save();
        }
    }

    /** @return array{donor:User,volunteer:User} */
    private function seedSupporters(NGO $ngo): array
    {
        // Primary demo donor (login: donor@fevourdk.org / password)
        $donorUser = $this->makeUser('Ananya Rao', 'donor@fevourdk.org', '9000000001', 'individual', 'donor');
        $donor = Donor::firstOrNew(['user_id' => $donorUser->id]);
        $donor->donor_type = 'individual';
        $donor->save();

        // Primary demo volunteer (login: volunteer@fevourdk.org / password)
        $volunteerUser = $this->makeUser('Rahul Kumar', 'volunteer@fevourdk.org', '9000000002', 'volunteer', 'volunteer');

        // Extra followers so the social graph looks alive.
        $extras = [
            ['Priya Nair', 'priya.supporter@fevourdk.org', '9000000003', 'donor'],
            ['Vivek Shetty', 'vivek.supporter@fevourdk.org', '9000000004', 'donor'],
            ['Meera Joshi', 'meera.supporter@fevourdk.org', '9000000005', 'volunteer'],
            ['Arjun Gowda', 'arjun.supporter@fevourdk.org', '9000000006', 'donor'],
            ['Sneha Reddy', 'sneha.supporter@fevourdk.org', '9000000007', 'volunteer'],
            ['Kiran Das', 'kiran.supporter@fevourdk.org', '9000000008', 'donor'],
        ];

        // Donor: follows AND supports. Volunteer: follows.
        $this->follow($ngo, $donorUser, true, true);
        $this->follow($ngo, $volunteerUser, true, false);

        foreach ($extras as $i => [$name, $email, $phone, $role]) {
            $u = $this->makeUser($name, $email, $phone, $role === 'volunteer' ? 'volunteer' : 'individual', $role);
            // Mix of followers and supporters.
            $this->follow($ngo, $u, true, $i % 2 === 0);
        }

        return ['donor' => $donorUser, 'volunteer' => $volunteerUser];
    }

    private function makeUser(string $name, string $email, string $phone, string $type, string $role): User
    {
        $parts = explode(' ', $name, 2);
        $u = User::firstOrNew(['email' => $email]);
        $u->name = $name;
        $u->first_name = $parts[0];
        $u->last_name = $parts[1] ?? '';
        $u->phone = $phone;
        $u->password = Hash::make('password');
        $u->user_type = $type;
        $u->is_active = true;
        $u->email_verified_at = now();
        $u->phone_verified_at = now();
        $u->save();
        try {
            $u->assignRole($role);
        } catch (\Throwable $e) {
        }

        return $u;
    }

    private function follow(NGO $ngo, User $user, bool $following, bool $supporting): void
    {
        $row = NgoSupporter::firstOrNew(['ngo_id' => $ngo->id, 'user_id' => $user->id]);
        $row->is_following = $following;
        $row->is_supporting = $supporting;
        $row->followed_at = $following ? now() : null;
        $row->supported_at = $supporting ? now() : null;
        $row->save();
    }

    /** @param array<int,Campaign> $campaigns */
    private function seedDonations(NGO $ngo, array $campaigns, User $donorUser): void
    {
        $donor = Donor::where('user_id', $donorUser->id)->first();
        if (! $donor || empty($campaigns)) {
            return;
        }

        $rows = [
            ['campaign' => $campaigns[0], 'amount' => 5000, 'days' => 12],
            ['campaign' => $campaigns[1], 'amount' => 2500, 'days' => 25],
            ['campaign' => $campaigns[2] ?? $campaigns[0], 'amount' => 10000, 'days' => 40],
        ];

        foreach ($rows as $r) {
            $txn = 'VIK-DEMO-'.$donor->id.'-'.$r['campaign']->id;
            $d = Donation::firstOrNew(['transaction_id' => $txn]);
            $d->ngo_id = $ngo->id;
            $d->campaign_id = $r['campaign']->id;
            $d->donor_id = $donor->id;
            $d->donation_type = 'one_time';
            $d->gateway_transaction_id = $txn;
            $d->amount = $r['amount'];
            $d->currency = 'INR';
            $d->payment_gateway = 'razorpay';
            $d->status = 'completed';
            $d->is_anonymous = false;
            $d->donated_at = now()->subDays($r['days']);
            $d->created_at = now()->subDays($r['days']);
            $d->save();
        }

        $total = Donation::where('donor_id', $donor->id)->where('status', 'completed')->sum('amount');
        $donor->total_donated = $total;
        $donor->donation_count = Donation::where('donor_id', $donor->id)->where('status', 'completed')->count();
        $donor->first_donation_date = now()->subDays(40);
        $donor->last_donation_date = now()->subDays(12);
        $donor->save();
    }
}
