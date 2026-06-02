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
                'title_kn' => self::knPair($m, 'donate_title', 'ನಮ್ಮ ಧ್ಯೇಯವನ್ನು ಬೆಂಬಲಿಸಿ'),
                'subtitle' => self::str($m, 'donate_subtitle', 'Your support helps us scale programmes and stay transparent on FEVOURD-K.'),
                'subtitle_kn' => self::knPair($m, 'donate_subtitle', 'ನಿಮ್ಮ ಬೆಂಬಲವು ಕಾರ್ಯಕ್ರಮಗಳನ್ನು ವಿಸ್ತರಿಸಲು ಮತ್ತು FEVOURD-K ನಲ್ಲಿ ಪಾರದರ್ಶಕವಾಗಿರಲು ನೆರವಾಗುತ್ತದೆ.'),
                'impact_blurb' => self::str($m, 'donate_impact_blurb', 'Every contribution strengthens communities we serve together with partners and volunteers.'),
                'impact_blurb_kn' => self::knPair($m, 'donate_impact_blurb', 'ಪ್ರತಿ ಕೊಡುಗೆಯೂ ನಾವು ಪಾಲುದಾರರು ಮತ್ತು ಸ್ವಯಂಸೇವಕರೊಂದಿಗೆ ಸೇವೆ ಸಲ್ಲಿಸುವ ಸಮುದಾಯಗಳನ್ನು ಬಲಪಡಿಸುತ್ತದೆ.'),
            ],
            'contact_intro' => self::str($m, 'contact_intro', 'Reach out to collaborate, volunteer, or learn more about our work.'),
            'contact_intro_kn' => self::knPair($m, 'contact_intro', 'ಸಹಯೋಗ ನೀಡಲು, ಸ್ವಯಂಸೇವೆ ಮಾಡಲು ಅಥವಾ ನಮ್ಮ ಕೆಲಸದ ಬಗ್ಗೆ ಇನ್ನಷ್ಟು ತಿಳಿಯಲು ಸಂಪರ್ಕಿಸಿ.'),
        ];
    }

    /**
     * Kannada companion for a string field.
     * - Returns the admin's "<key>_kn" override when present.
     * - Otherwise returns the supplied Kannada default ONLY when the English
     *   field is also using its default (so a customised English value never
     *   shows a mismatched canned Kannada translation).
     * - Returns '' when there is no Kannada to show (=> EN ⇄ ಕನ್ನಡ engine keeps English).
     */
    private static function knPair(array $m, string $key, string $knDefault): string
    {
        $override = $m[$key.'_kn'] ?? null;
        if (is_string($override) && trim($override) !== '') {
            return trim($override);
        }
        $enCustom = isset($m[$key]) && is_string($m[$key]) && trim($m[$key]) !== '';

        return $enCustom ? '' : $knDefault;
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
                'title_kn' => '', // proper name stays as-is
                'subtitle' => self::str($m, 'hero_subtitle', 'Trusted non-profit · community impact'),
                'subtitle_kn' => self::knPair($m, 'hero_subtitle', 'ವಿಶ್ವಾಸಾರ್ಹ ಲಾಭರಹಿತ ಸಂಸ್ಥೆ · ಸಮುದಾಯ ಪರಿಣಾಮ'),
                'description' => self::str($m, 'hero_description', $short),
                'description_kn' => self::knPair($m, 'hero_description', ''),
                'stats' => [
                    ['number' => self::str($m, 'slide1_s1_n', '100%'), 'label' => self::str($m, 'slide1_s1_l', 'Committed'), 'label_kn' => self::knPair($m, 'slide1_s1_l', 'ಬದ್ಧ')],
                    ['number' => self::str($m, 'slide1_s2_n', '24/7'), 'label' => self::str($m, 'slide1_s2_l', 'Dedication'), 'label_kn' => self::knPair($m, 'slide1_s2_l', 'ಸಮರ್ಪಣೆ')],
                    ['number' => self::str($m, 'slide1_s3_n', 'NGO'), 'label' => self::str($m, 'slide1_s3_l', 'Registered'), 'label_kn' => self::knPair($m, 'slide1_s3_l', 'ನೋಂದಾಯಿತ')],
                    ['number' => self::str($m, 'slide1_s4_n', '∞'), 'label' => self::str($m, 'slide1_s4_l', 'Hope'), 'label_kn' => self::knPair($m, 'slide1_s4_l', 'ಭರವಸೆ')],
                ],
            ],
            [
                'title' => 'Our mission',
                'title_kn' => 'ನಮ್ಮ ಧ್ಯೇಯ',
                'subtitle' => self::str($m, 'mission_subtitle', 'Inclusion through action'),
                'subtitle_kn' => self::knPair($m, 'mission_subtitle', 'ಕ್ರಿಯೆಯ ಮೂಲಕ ಒಳಗೊಳ್ಳುವಿಕೆ'),
                'description' => self::str($m, 'mission_description', 'We focus on practical programmes that improve lives, with transparency and local partnership.'),
                'description_kn' => self::knPair($m, 'mission_description', 'ಪಾರದರ್ಶಕತೆ ಮತ್ತು ಸ್ಥಳೀಯ ಪಾಲುದಾರಿಕೆಯೊಂದಿಗೆ ಜೀವನವನ್ನು ಸುಧಾರಿಸುವ ಪ್ರಾಯೋಗಿಕ ಕಾರ್ಯಕ್ರಮಗಳ ಮೇಲೆ ನಾವು ಗಮನ ಹರಿಸುತ್ತೇವೆ.'),
                'stats' => [
                    ['number' => '1K+', 'label' => 'Lives touched', 'label_kn' => 'ಸ್ಪರ್ಶಿಸಿದ ಜೀವನಗಳು'],
                    ['number' => '50+', 'label' => 'Partners', 'label_kn' => 'ಪಾಲುದಾರರು'],
                    ['number' => '100%', 'label' => 'Accountability', 'label_kn' => 'ಜವಾಬ್ದಾರಿ'],
                    ['number' => '365', 'label' => 'Days of care', 'label_kn' => 'ಆರೈಕೆಯ ದಿನಗಳು'],
                ],
            ],
            [
                'title' => 'Our vision',
                'title_kn' => 'ನಮ್ಮ ದೃಷ್ಟಿಕೋನ',
                'subtitle' => self::str($m, 'vision_subtitle', 'Humanity first'),
                'subtitle_kn' => self::knPair($m, 'vision_subtitle', 'ಮಾನವೀಯತೆ ಮೊದಲು'),
                'description' => self::str($m, 'vision_description', 'A future where communities are resilient, inclusive, and able to shape their own progress.'),
                'description_kn' => self::knPair($m, 'vision_description', 'ಸಮುದಾಯಗಳು ಸ್ಥಿತಿಸ್ಥಾಪಕ, ಒಳಗೊಳ್ಳುವ ಮತ್ತು ತಮ್ಮ ಪ್ರಗತಿಯನ್ನು ರೂಪಿಸಿಕೊಳ್ಳಬಲ್ಲ ಭವಿಷ್ಯ.'),
                'stats' => [
                    ['number' => '1', 'label' => 'Planet', 'label_kn' => 'ಗ್ರಹ'],
                    ['number' => '∞', 'label' => 'Kindness', 'label_kn' => 'ದಯೆ'],
                    ['number' => 'NGO', 'label' => 'Non-profit', 'label_kn' => 'ಲಾಭರಹಿತ'],
                    ['number' => '✓', 'label' => 'Verified', 'label_kn' => 'ಪರಿಶೀಲಿತ'],
                ],
            ],
            [
                'title' => self::str($m, 'impact_title', 'Our impact'),
                'title_kn' => self::knPair($m, 'impact_title', 'ನಮ್ಮ ಪರಿಣಾಮ'),
                'subtitle' => self::str($m, 'impact_subtitle', 'Together we go further'),
                'subtitle_kn' => self::knPair($m, 'impact_subtitle', 'ಒಟ್ಟಿಗೆ ನಾವು ಮುಂದೆ ಸಾಗುತ್ತೇವೆ'),
                'description' => self::str($m, 'impact_description', $short),
                'description_kn' => self::knPair($m, 'impact_description', ''),
                'stats' => [
                    ['number' => '500+', 'label' => 'Activities', 'label_kn' => 'ಚಟುವಟಿಕೆಗಳು'],
                    ['number' => '1000s', 'label' => 'Reached', 'label_kn' => 'ತಲುಪಲಾಗಿದೆ'],
                    ['number' => '50+', 'label' => 'Volunteers', 'label_kn' => 'ಸ್ವಯಂಸೇವಕರು'],
                    ['number' => 'A+', 'label' => 'Trust', 'label_kn' => 'ವಿಶ್ವಾಸ'],
                ],
            ],
        ];
    }

    private static function quickStats(NGO $ngo, array $m): array
    {
        $reg = $ngo->registration_number ? Str::limit($ngo->registration_number, 24, '…') : 'Registered NGO';

        $verified = $ngo->verification_status === 'verified';

        return [
            [
                'title' => self::str($m, 'stat_1_h', 'Registered'),
                'title_kn' => self::knPair($m, 'stat_1_h', 'ನೋಂದಾಯಿತ'),
                'subtitle' => self::str($m, 'stat_1_p', $reg),
                'subtitle_kn' => self::knPair($m, 'stat_1_p', ''),
            ],
            [
                'title' => self::str($m, 'stat_2_h', 'Focus areas'),
                'title_kn' => self::knPair($m, 'stat_2_h', 'ಗಮನ ಕ್ಷೇತ್ರಗಳು'),
                'subtitle' => self::str($m, 'stat_2_p', count(is_array($ngo->focus_areas) ? $ngo->focus_areas : []).' programmes'),
                'subtitle_kn' => self::knPair($m, 'stat_2_p', count(is_array($ngo->focus_areas) ? $ngo->focus_areas : []).' ಕಾರ್ಯಕ್ರಮಗಳು'),
            ],
            [
                'title' => self::str($m, 'stat_3_h', 'Location'),
                'title_kn' => self::knPair($m, 'stat_3_h', 'ಸ್ಥಳ'),
                'subtitle' => self::str($m, 'stat_3_p', $ngo->city?->name ?? 'Karnataka, India'),
                'subtitle_kn' => self::knPair($m, 'stat_3_p', $ngo->city?->name ? '' : 'ಕರ್ನಾಟಕ, ಭಾರತ'),
            ],
            [
                'title' => self::str($m, 'stat_4_h', 'Status'),
                'title_kn' => self::knPair($m, 'stat_4_h', 'ಸ್ಥಿತಿ'),
                'subtitle' => self::str($m, 'stat_4_p', $verified ? 'Verified on FEVOURD-K' : 'Onboarding'),
                'subtitle_kn' => self::knPair($m, 'stat_4_p', $verified ? 'FEVOURD-K ನಲ್ಲಿ ಪರಿಶೀಲಿತ' : 'ಸೇರ್ಪಡೆ ಪ್ರಕ್ರಿಯೆಯಲ್ಲಿ'),
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
                    'title_kn' => trim((string) ($row['title_kn'] ?? '')),
                    'body' => trim((string) ($row['body'] ?? Str::limit($desc, 200, '…'))),
                    'body_kn' => trim((string) ($row['body_kn'] ?? '')),
                    'category' => trim((string) ($row['category'] ?? 'Update')),
                    'category_kn' => trim((string) ($row['category_kn'] ?? self::categoryKn(trim((string) ($row['category'] ?? 'Update'))))),
                    'date' => trim((string) ($row['date'] ?? now()->format('M Y'))),
                    'date_kn' => trim((string) ($row['date_kn'] ?? '')),
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
                'title_kn' => '', // focus-area derived (English) — admin can add Kannada later
                'body' => Str::limit($desc, 220, '…'),
                'body_kn' => '',
                'category' => $meta['cat'],
                'category_kn' => self::categoryKn($meta['cat']),
                'date' => 'Ongoing',
                'date_kn' => 'ನಡೆಯುತ್ತಿದೆ',
                'image' => self::themeAsset($meta['img']),
            ];
            $i++;
        }

        return array_slice($out, 0, 6);
    }

    private static function categoryKn(string $cat): string
    {
        $map = [
            'Education' => 'ಶಿಕ್ಷಣ',
            'Community' => 'ಸಮುದಾಯ',
            'Environment' => 'ಪರಿಸರ',
            'Livelihoods' => 'ಜೀವನೋಪಾಯ',
            'Protection' => 'ರಕ್ಷಣೆ',
            'Agriculture' => 'ಕೃಷಿ',
            'Update' => 'ನವೀಕರಣ',
            'Health' => 'ಆರೋಗ್ಯ',
        ];

        return $map[$cat] ?? '';
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

        // Build paragraphs + their Kannada companions together so indexes stay aligned.
        // desc/extra are user prose (English-only here) -> '' ; the fixed closing line has Kannada.
        $paragraphs = [$desc];
        $paragraphsKn = ['']; // '' -> EN ⇄ ಕನ್ನಡ engine keeps the English description
        if ($extra !== '') {
            $paragraphs[] = $extra;
            $paragraphsKn[] = '';
        }
        $paragraphs[] = 'We partner locally, report transparently, and welcome collaborators who share our values.';
        $paragraphsKn[] = 'ನಾವು ಸ್ಥಳೀಯವಾಗಿ ಪಾಲುದಾರಿಕೆ ಮಾಡುತ್ತೇವೆ, ಪಾರದರ್ಶಕವಾಗಿ ವರದಿ ಮಾಡುತ್ತೇವೆ ಮತ್ತು ನಮ್ಮ ಮೌಲ್ಯಗಳನ್ನು ಹಂಚಿಕೊಳ್ಳುವ ಸಹಯೋಗಿಗಳನ್ನು ಸ್ವಾಗತಿಸುತ್ತೇವೆ.';

        return [
            'section_title' => self::str($m, 'about_section_title', 'About '.$ngo->name),
            'section_title_kn' => self::knPair($m, 'about_section_title', $ngo->name.' ಬಗ್ಗೆ'),
            'section_subtitle' => self::str($m, 'about_section_subtitle', 'Mission, vision, and community commitment'),
            'section_subtitle_kn' => self::knPair($m, 'about_section_subtitle', 'ಧ್ಯೇಯ, ದೃಷ್ಟಿಕೋನ ಮತ್ತು ಸಮುದಾಯ ಬದ್ಧತೆ'),
            'who_heading' => self::str($m, 'about_who_heading', 'Who we are'),
            'who_heading_kn' => self::knPair($m, 'about_who_heading', 'ನಾವು ಯಾರು'),
            'who_byline' => self::str($m, 'about_who_byline', $whoLine),
            'paragraphs' => $paragraphs,
            'paragraphs_kn' => $paragraphsKn,
            'vision_quote' => self::str($m, 'about_vision_quote', 'Humanity is the first step of social change.'),
            'vision_quote_kn' => self::knPair($m, 'about_vision_quote', 'ಮಾನವೀಯತೆಯೇ ಸಾಮಾಜಿಕ ಬದಲಾವಣೆಯ ಮೊದಲ ಹೆಜ್ಜೆ.'),
            'mission_intro' => self::str($m, 'about_mission_intro', 'To empower marginalized communities through strategic interventions in:'),
            'mission_intro_kn' => self::knPair($m, 'about_mission_intro', 'ಕಾರ್ಯತಂತ್ರ ಮಧ್ಯಸ್ಥಿಕೆಗಳ ಮೂಲಕ ಅಂಚಿನಲ್ಲಿರುವ ಸಮುದಾಯಗಳನ್ನು ಸಬಲೀಕರಣಗೊಳಿಸುವುದು:'),
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
                    'title_kn' => trim((string) ($row['title_kn'] ?? '')),
                    'body' => trim((string) ($row['body'] ?? '')),
                    'body_kn' => trim((string) ($row['body_kn'] ?? '')),
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
                'title_kn' => '', // focus-area label (English) — admin can add Kannada later
                'body' => 'A core programme area for '.$ngo->name.', delivered with partners and volunteers.',
                'body_kn' => $ngo->name.' ಸಂಸ್ಥೆಯ ಪ್ರಮುಖ ಕಾರ್ಯಕ್ರಮ ಕ್ಷೇತ್ರ, ಪಾಲುದಾರರು ಮತ್ತು ಸ್ವಯಂಸೇವಕರೊಂದಿಗೆ ನಡೆಸಲಾಗುತ್ತದೆ.',
                'icon' => $icons[$i % count($icons)],
            ];
        }

        if (count($out) === 0) {
            $out = [
                ['title' => 'Community programmes', 'title_kn' => 'ಸಮುದಾಯ ಕಾರ್ಯಕ್ರಮಗಳು', 'body' => 'Grassroots projects aligned with local needs.', 'body_kn' => 'ಸ್ಥಳೀಯ ಅಗತ್ಯಗಳಿಗೆ ಅನುಗುಣವಾದ ತಳಮಟ್ಟದ ಯೋಜನೆಗಳು.', 'icon' => 'fa-users'],
                ['title' => 'Education & skills', 'title_kn' => 'ಶಿಕ್ಷಣ ಮತ್ತು ಕೌಶಲ್ಯ', 'body' => 'Learning, awareness, and livelihood readiness.', 'body_kn' => 'ಕಲಿಕೆ, ಜಾಗೃತಿ ಮತ್ತು ಜೀವನೋಪಾಯ ಸಿದ್ಧತೆ.', 'icon' => 'fa-graduation-cap'],
                ['title' => 'Health & wellbeing', 'title_kn' => 'ಆರೋಗ್ಯ ಮತ್ತು ಯೋಗಕ್ಷೇಮ', 'body' => 'Outreach, camps, and preventive care support.', 'body_kn' => 'ಸಂಪರ್ಕ, ಶಿಬಿರಗಳು ಮತ್ತು ತಡೆಗಟ್ಟುವ ಆರೈಕೆ ಬೆಂಬಲ.', 'icon' => 'fa-heartbeat'],
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
