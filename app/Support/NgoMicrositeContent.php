<?php

namespace App\Support;

use App\Models\NGO;
use Illuminate\Support\Str;

/**
 * Merges NGO database fields with digitalization_settings.microsite for the public Themes layout.
 *
 * Editable keys (see Digitalization form): hero_*, mission_*, vision_*, impact_*, stat_*_h/p,
 * programs[], stories[], donate_*, about_extra.
 */
class NgoMicrositeContent
{
    public static function for(NGO $ngo): array
    {
        $m = data_get($ngo->digitalization_settings, 'microsite', []) ?: [];
        if (! is_array($m)) {
            $m = [];
        }

        $desc = trim((string) ($ngo->description ?? ''));
        if ($desc === '') {
            $desc = 'We are a mission-led organisation working with communities for lasting social impact.';
        }

        $focus = is_array($ngo->focus_areas) ? array_values(array_filter($ngo->focus_areas)) : [];

        return [
            'theme_hex' => self::normalizeHex($ngo->theme_color ?? '#2563eb'),
            'slides' => self::slides($ngo, $m, $desc),
            'quick_stats' => self::quickStats($ngo, $m),
            'stories' => self::stories($ngo, $m, $focus, $desc),
            'about' => self::aboutBlock($ngo, $m, $desc),
            'programs' => self::programs($ngo, $m, $focus),
            'team' => self::team($ngo),
            'donate' => [
                'title' => self::str($m, 'donate_title', 'Support our mission'),
                'subtitle' => self::str($m, 'donate_subtitle', 'Your support helps us scale programmes and stay transparent on FEVOURD-K.'),
                'impact_blurb' => self::str($m, 'donate_impact_blurb', 'Every contribution strengthens communities we serve together with partners and volunteers.'),
            ],
            'contact_intro' => self::str($m, 'contact_intro', 'Reach out to collaborate, volunteer, or learn more about our work.'),
        ];
    }

    public static function themeAsset(string $path): string
    {
        $path = ltrim($path, '/');

        return asset($path);
    }

    private static function normalizeHex(?string $hex): string
    {
        $hex = is_string($hex) ? trim($hex) : '';
        if ($hex === '') {
            return '#2563eb';
        }
        if (! preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $hex)) {
            return '#2563eb';
        }

        return strlen($hex) === 4 ? self::expandShortHex($hex) : $hex;
    }

    private static function expandShortHex(string $short): string
    {
        $h = ltrim($short, '#');

        return '#'.$h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
    }

    private static function str(array $m, string $key, string $default): string
    {
        $v = $m[$key] ?? null;

        return is_string($v) && trim($v) !== '' ? trim($v) : $default;
    }

    private static function slides(NGO $ngo, array $m, string $desc): array
    {
        $short = Str::limit($desc, 320, '…');

        return [
            [
                'title' => $ngo->name,
                'subtitle' => self::str($m, 'hero_subtitle', 'Trusted non-profit · community impact'),
                'description' => self::str($m, 'hero_description', $short),
                'stats' => [
                    ['number' => self::str($m, 'slide1_s1_n', '100%'), 'label' => self::str($m, 'slide1_s1_l', 'Committed')],
                    ['number' => self::str($m, 'slide1_s2_n', '24/7'), 'label' => self::str($m, 'slide1_s2_l', 'Dedication')],
                    ['number' => self::str($m, 'slide1_s3_n', 'NGO'), 'label' => self::str($m, 'slide1_s3_l', 'Registered')],
                    ['number' => self::str($m, 'slide1_s4_n', '∞'), 'label' => self::str($m, 'slide1_s4_l', 'Hope')],
                ],
            ],
            [
                'title' => 'Our mission',
                'subtitle' => self::str($m, 'mission_subtitle', 'Inclusion through action'),
                'description' => self::str($m, 'mission_description', 'We focus on practical programmes that improve lives, with transparency and local partnership.'),
                'stats' => [
                    ['number' => '1K+', 'label' => 'Lives touched'],
                    ['number' => '50+', 'label' => 'Partners'],
                    ['number' => '100%', 'label' => 'Accountability'],
                    ['number' => '365', 'label' => 'Days of care'],
                ],
            ],
            [
                'title' => 'Our vision',
                'subtitle' => self::str($m, 'vision_subtitle', 'Humanity first'),
                'description' => self::str($m, 'vision_description', 'A future where communities are resilient, inclusive, and able to shape their own progress.'),
                'stats' => [
                    ['number' => '1', 'label' => 'Planet'],
                    ['number' => '∞', 'label' => 'Kindness'],
                    ['number' => 'NGO', 'label' => 'Non-profit'],
                    ['number' => '✓', 'label' => 'Verified'],
                ],
            ],
            [
                'title' => self::str($m, 'impact_title', 'Our impact'),
                'subtitle' => self::str($m, 'impact_subtitle', 'Together we go further'),
                'description' => self::str($m, 'impact_description', $short),
                'stats' => [
                    ['number' => '500+', 'label' => 'Activities'],
                    ['number' => '1000s', 'label' => 'Reached'],
                    ['number' => '50+', 'label' => 'Volunteers'],
                    ['number' => 'A+', 'label' => 'Trust'],
                ],
            ],
        ];
    }

    private static function quickStats(NGO $ngo, array $m): array
    {
        $reg = $ngo->registration_number ? Str::limit($ngo->registration_number, 24, '…') : 'Registered NGO';

        return [
            [
                'title' => self::str($m, 'stat_1_h', 'Registered'),
                'subtitle' => self::str($m, 'stat_1_p', $reg),
            ],
            [
                'title' => self::str($m, 'stat_2_h', 'Focus areas'),
                'subtitle' => self::str($m, 'stat_2_p', count(is_array($ngo->focus_areas) ? $ngo->focus_areas : []).' programmes'),
            ],
            [
                'title' => self::str($m, 'stat_3_h', 'Location'),
                'subtitle' => self::str($m, 'stat_3_p', $ngo->city?->name ?? 'Karnataka, India'),
            ],
            [
                'title' => self::str($m, 'stat_4_h', 'Status'),
                'subtitle' => self::str($m, 'stat_4_p', $ngo->verification_status === 'verified' ? 'Verified on FEVOURD-K' : 'Onboarding'),
            ],
        ];
    }

    private static function stories(NGO $ngo, array $m, array $focus, string $desc): array
    {
        $custom = $m['stories'] ?? null;
        $out = [];
        if (is_array($custom)) {
            foreach ($custom as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $t = trim((string) ($row['title'] ?? ''));
                if ($t === '') {
                    continue;
                }
                $out[] = [
                    'title' => $t,
                    'body' => trim((string) ($row['body'] ?? Str::limit($desc, 200, '…'))),
                    'category' => trim((string) ($row['category'] ?? 'Update')),
                    'date' => trim((string) ($row['date'] ?? now()->format('M Y'))),
                    'image' => self::storyImage($row['image'] ?? null),
                ];
            }
        }

        $pool = [
            ['img' => '/assets/Themes/assets/images/blog/education-programs.jpg', 'cat' => 'Education'],
            ['img' => '/assets/Themes/assets/images/blog/women-empowerment.jpg', 'cat' => 'Community'],
            ['img' => '/assets/Themes/assets/images/blog/climate-action.jpg', 'cat' => 'Environment'],
            ['img' => '/assets/Themes/assets/images/blog/housing-support.jpg', 'cat' => 'Livelihoods'],
            ['img' => '/assets/Themes/assets/images/blog/child-rights-protection.jpg', 'cat' => 'Protection'],
            ['img' => '/assets/Themes/assets/images/blog/sustainable-agriculture.jpg', 'cat' => 'Agriculture'],
        ];

        $i = 0;
        while (count($out) < 6) {
            if (count($focus) > 0) {
                $f = $focus[$i % count($focus)];
                $title = is_string($f) && $f !== '' ? $f.' initiative' : 'Field update';
            } else {
                $title = 'Community update';
            }
            $meta = $pool[$i % count($pool)];
            $out[] = [
                'title' => $title,
                'body' => Str::limit($desc, 220, '…'),
                'category' => $meta['cat'],
                'date' => 'Ongoing',
                'image' => self::themeAsset($meta['img']),
            ];
            $i++;
        }

        return array_slice($out, 0, 6);
    }

    private static function storyImage(mixed $path): string
    {
        $p = is_string($path) ? trim($path) : '';
        if ($p === '') {
            return self::themeAsset('/assets/Themes/assets/images/blog/education-programs.jpg');
        }
        if (Str::startsWith($p, ['http://', 'https://'])) {
            return $p;
        }

        return self::themeAsset(Str::startsWith($p, '/') ? $p : '/'.$p);
    }

    private static function aboutBlock(NGO $ngo, array $m, string $desc): array
    {
        $extra = self::str($m, 'about_extra', '');
        $whoLine = $ngo->founder_name
            ? 'Led by '.$ngo->founder_name.($ngo->cofounder_name ? ' and '.$ngo->cofounder_name : '')
            : 'Volunteer-led team';

        return [
            'section_title' => self::str($m, 'about_section_title', 'About '.$ngo->name),
            'section_subtitle' => self::str($m, 'about_section_subtitle', 'Mission, vision, and community commitment'),
            'who_heading' => self::str($m, 'about_who_heading', 'Who we are'),
            'who_byline' => self::str($m, 'about_who_byline', $whoLine),
            'paragraphs' => array_values(array_filter([
                $desc,
                $extra !== '' ? $extra : null,
                'We partner locally, report transparently, and welcome collaborators who share our values.',
            ])),
            'vision_quote' => self::str($m, 'about_vision_quote', 'Humanity is the first step of social change.'),
        ];
    }

    private static function programs(NGO $ngo, array $m, array $focus): array
    {
        $custom = $m['programs'] ?? null;
        $out = [];
        if (is_array($custom)) {
            foreach ($custom as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $t = trim((string) ($row['title'] ?? ''));
                if ($t === '') {
                    continue;
                }
                $icon = trim((string) ($row['icon'] ?? 'fa-star'));
                if (! Str::startsWith($icon, 'fa-')) {
                    $icon = 'fa-'.$icon;
                }
                $out[] = [
                    'title' => $t,
                    'body' => trim((string) ($row['body'] ?? '')),
                    'icon' => $icon,
                ];
            }
        }
        if (count($out) > 0) {
            return array_slice($out, 0, 12);
        }

        $icons = ['fa-graduation-cap', 'fa-hands-helping', 'fa-seedling', 'fa-heartbeat', 'fa-tree', 'fa-hands', 'fa-home', 'fa-gavel'];
        foreach (array_slice($focus, 0, 8) as $i => $area) {
            if (! is_string($area) || trim($area) === '') {
                continue;
            }
            $out[] = [
                'title' => $area,
                'body' => 'A core programme area for '.$ngo->name.', delivered with partners and volunteers.',
                'icon' => $icons[$i % count($icons)],
            ];
        }

        if (count($out) === 0) {
            $out = [
                ['title' => 'Community programmes', 'body' => 'Grassroots projects aligned with local needs.', 'icon' => 'fa-users'],
                ['title' => 'Education & skills', 'body' => 'Learning, awareness, and livelihood readiness.', 'icon' => 'fa-graduation-cap'],
                ['title' => 'Health & wellbeing', 'body' => 'Outreach, camps, and preventive care support.', 'icon' => 'fa-heartbeat'],
            ];
        }

        return $out;
    }

    private static function team(NGO $ngo): array
    {
        $team = [];
        if ($ngo->founder_name) {
            $team[] = [
                'name' => $ngo->founder_name,
                'role' => 'Founder / Lead',
                'bio' => $ngo->founder_email ? 'Reachable at '.$ngo->founder_email : 'Leading organisational strategy and partnerships.',
                'image' => self::themeAsset('/assets/Themes/assets/images/leadership/founder.jpg'),
            ];
        }
        if ($ngo->cofounder_name) {
            $team[] = [
                'name' => $ngo->cofounder_name,
                'role' => 'Co-founder',
                'bio' => $ngo->cofounder_email ? 'Contact: '.$ngo->cofounder_email : 'Supporting programmes and field engagement.',
                'image' => self::themeAsset('/assets/Themes/assets/images/leadership/secretary.jpg'),
            ];
        }

        return $team;
    }
}
