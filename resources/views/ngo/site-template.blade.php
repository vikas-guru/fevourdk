@php
    $logoUrl = $ngo->logo
        ? (\Illuminate\Support\Str::startsWith($ngo->logo, ['http://', 'https://'])
            ? $ngo->logo
            : asset(\Illuminate\Support\Str::startsWith($ngo->logo, '/') ? ltrim($ngo->logo, '/') : 'storage/'.$ngo->logo))
        : asset('assets/Themes/assets/images/about/vikasana-brand.png');
    $hex = $microsite['theme_hex'] ?? '#2563eb';
    $blogSlides = (int) max(1, ceil(count($microsite['stories']) / 3));

    // Impact counters (template defaults; swap in NGO-specific values via microsite later)
    // *_kn = Kannada label for the EN ⇄ ಕನ್ನಡ toggle (numbers/icons stay as-is).
    $impactCards = [
        ['icon' => 'fa-users', 'target' => '5000', 'display' => '5,000+', 'label' => 'Lives touched', 'label_kn' => 'ಸ್ಪರ್ಶಿಸಿದ ಜೀವನಗಳು'],
        ['icon' => 'fa-hands-helping', 'target' => '120', 'display' => '120+', 'label' => 'Programmes run', 'label_kn' => 'ನಡೆಸಿದ ಕಾರ್ಯಕ್ರಮಗಳು'],
        ['icon' => 'fa-map-marked-alt', 'target' => '50', 'display' => '50+', 'label' => 'Communities reached', 'label_kn' => 'ತಲುಪಿದ ಸಮುದಾಯಗಳು'],
        ['icon' => 'fa-handshake', 'target' => '30', 'display' => '30+', 'label' => 'Partners', 'label_kn' => 'ಪಾಲುದಾರರು'],
        ['icon' => 'fa-user-friends', 'target' => '200', 'display' => '200+', 'label' => 'Volunteers', 'label_kn' => 'ಸ್ವಯಂಸೇವಕರು'],
        ['icon' => 'fa-smile', 'target' => '95', 'display' => '95%', 'label' => 'Positive feedback', 'label_kn' => 'ಸಕಾರಾತ್ಮಕ ಪ್ರತಿಕ್ರಿಯೆ'],
    ];

    // Gallery (template stock activity imagery — replace per NGO when uploads exist)
    $galleryItems = [
        ['img' => 'assets/Themes/assets/images/activities/world-environment-day.jpg', 'size' => 'large', 'title' => 'Field initiatives', 'title_kn' => 'ಕ್ಷೇತ್ರ ಉಪಕ್ರಮಗಳು', 'caption' => 'On-ground programmes with the community', 'caption_kn' => 'ಸಮುದಾಯದೊಂದಿಗೆ ನೆಲಮಟ್ಟದ ಕಾರ್ಯಕ್ರಮಗಳು'],
        ['img' => 'assets/Themes/assets/images/activities/covid-relief.jpg', 'size' => '', 'title' => 'Relief & support', 'title_kn' => 'ಪರಿಹಾರ ಮತ್ತು ಬೆಂಬಲ', 'caption' => 'Essential aid for those in need', 'caption_kn' => 'ಅಗತ್ಯವಿರುವವರಿಗೆ ಅಗತ್ಯ ನೆರವು'],
        ['img' => 'assets/Themes/assets/images/activities/childline-awareness.jpg', 'size' => '', 'title' => 'Awareness drives', 'title_kn' => 'ಜಾಗೃತಿ ಅಭಿಯಾನಗಳು', 'caption' => 'School and youth outreach', 'caption_kn' => 'ಶಾಲೆ ಮತ್ತು ಯುವಜನರ ಸಂಪರ್ಕ'],
        ['img' => 'assets/Themes/assets/images/activities/gender-discrimination.jpg', 'size' => '', 'title' => 'Equality & rights', 'title_kn' => 'ಸಮಾನತೆ ಮತ್ತು ಹಕ್ಕುಗಳು', 'caption' => 'Inclusion-focused sessions', 'caption_kn' => 'ಒಳಗೊಳ್ಳುವಿಕೆ ಕೇಂದ್ರಿತ ಅಧಿವೇಶನಗಳು'],
        ['img' => 'assets/Themes/assets/images/infrastructure/community-center.jpg', 'size' => 'wide', 'title' => 'Community development', 'title_kn' => 'ಸಮುದಾಯ ಅಭಿವೃದ್ಧಿ', 'caption' => 'Building lasting local capacity', 'caption_kn' => 'ಶಾಶ್ವತ ಸ್ಥಳೀಯ ಸಾಮರ್ಥ್ಯ ನಿರ್ಮಾಣ'],
        ['img' => 'assets/Themes/assets/images/activities/health-camp.jpg', 'size' => '', 'title' => 'Health camps', 'title_kn' => 'ಆರೋಗ್ಯ ಶಿಬಿರಗಳು', 'caption' => 'Medical services for rural areas', 'caption_kn' => 'ಗ್ರಾಮೀಣ ಪ್ರದೇಶಗಳಿಗೆ ವೈದ್ಯಕೀಯ ಸೇವೆಗಳು'],
        ['img' => 'assets/Themes/assets/images/infrastructure/village-meeting.jpg', 'size' => '', 'title' => 'Community engagement', 'title_kn' => 'ಸಮುದಾಯ ತೊಡಗಿಸಿಕೊಳ್ಳುವಿಕೆ', 'caption' => 'Village meetings & consultations', 'caption_kn' => 'ಗ್ರಾಮ ಸಭೆಗಳು ಮತ್ತು ಸಮಾಲೋಚನೆಗಳು'],
        ['img' => 'assets/Themes/assets/images/activities/water-resources.jpg', 'size' => '', 'title' => 'Water & environment', 'title_kn' => 'ನೀರು ಮತ್ತು ಪರಿಸರ', 'caption' => 'Sustainable resource projects', 'caption_kn' => 'ಸುಸ್ಥಿರ ಸಂಪನ್ಮೂಲ ಯೋಜನೆಗಳು'],
    ];

    // ---- Archive-3 parity blocks (template defaults; founder name pulled from NGO) ----
    $missionItems = [
        ['icon' => 'fa-graduation-cap', 'title' => 'Education', 'title_kn' => 'ಶಿಕ್ಷಣ', 'body' => 'Ensuring access to quality learning for underprivileged children', 'body_kn' => 'ಹಿಂದುಳಿದ ಮಕ್ಕಳಿಗೆ ಗುಣಮಟ್ಟದ ಕಲಿಕೆಯ ಲಭ್ಯತೆ ಖಚಿತಪಡಿಸುವುದು', 'n' => '01'],
        ['icon' => 'fa-heartbeat', 'title' => 'Health & Well-being', 'title_kn' => 'ಆರೋಗ್ಯ ಮತ್ತು ಯೋಗಕ್ಷೇಮ', 'body' => 'Providing healthcare services and awareness programs', 'body_kn' => 'ಆರೋಗ್ಯ ಸೇವೆಗಳು ಮತ್ತು ಜಾಗೃತಿ ಕಾರ್ಯಕ್ರಮಗಳನ್ನು ಒದಗಿಸುವುದು', 'n' => '02'],
        ['icon' => 'fa-hand-holding-usd', 'title' => 'Economic Development', 'title_kn' => 'ಆರ್ಥಿಕ ಅಭಿವೃದ್ಧಿ', 'body' => 'Supporting women entrepreneurship and self-help groups', 'body_kn' => 'ಮಹಿಳಾ ಉದ್ಯಮಶೀಲತೆ ಮತ್ತು ಸ್ವಸಹಾಯ ಗುಂಪುಗಳಿಗೆ ಬೆಂಬಲ', 'n' => '03'],
        ['icon' => 'fa-seedling', 'title' => 'Sustainable Agriculture', 'title_kn' => 'ಸುಸ್ಥಿರ ಕೃಷಿ', 'body' => 'Promoting organic farming and farmer producer organizations', 'body_kn' => 'ಸಾವಯವ ಕೃಷಿ ಮತ್ತು ರೈತ ಉತ್ಪಾದಕ ಸಂಸ್ಥೆಗಳ ಉತ್ತೇಜನ', 'n' => '04'],
    ];
    $approachItems = [
        ['icon' => 'fa-infinity', 'title' => 'Sustainable', 'title_kn' => 'ಸುಸ್ಥಿರ', 'body' => 'Long-term impact through self-sufficient initiatives', 'body_kn' => 'ಸ್ವಾವಲಂಬಿ ಉಪಕ್ರಮಗಳ ಮೂಲಕ ದೀರ್ಘಕಾಲೀನ ಪರಿಣಾಮ', 'stat' => '95%', 'stat_label' => 'Success Rate', 'stat_label_kn' => 'ಯಶಸ್ಸಿನ ದರ'],
        ['icon' => 'fa-users', 'title' => 'Inclusive', 'title_kn' => 'ಒಳಗೊಳ್ಳುವ', 'body' => 'Reaching the most vulnerable and marginalized groups', 'body_kn' => 'ಅತ್ಯಂತ ದುರ್ಬಲ ಮತ್ತು ಅಂಚಿನಲ್ಲಿರುವ ಗುಂಪುಗಳನ್ನು ತಲುಪುವುದು', 'stat' => '50K+', 'stat_label' => 'People Reached', 'stat_label_kn' => 'ತಲುಪಿದ ಜನರು'],
        ['icon' => 'fa-handshake', 'title' => 'Collaborative', 'title_kn' => 'ಸಹಯೋಗದ', 'body' => 'Partnering with NGOs, government agencies, and corporates', 'body_kn' => 'ಸ್ವಯಂಸೇವಾ ಸಂಸ್ಥೆಗಳು, ಸರ್ಕಾರಿ ಏಜೆನ್ಸಿಗಳು ಮತ್ತು ಕಂಪನಿಗಳೊಂದಿಗೆ ಪಾಲುದಾರಿಕೆ', 'stat' => '100+', 'stat_label' => 'Partners', 'stat_label_kn' => 'ಪಾಲುದಾರರು'],
    ];
    $approachImpact = [
        ['number' => '1M+', 'label' => 'Lives Impacted', 'label_kn' => 'ಪ್ರಭಾವಿತ ಜೀವನಗಳು'],
        ['number' => '500+', 'label' => 'Active Programs', 'label_kn' => 'ಸಕ್ರಿಯ ಕಾರ್ಯಕ್ರಮಗಳು'],
        ['number' => '100+', 'label' => 'Partner Organizations', 'label_kn' => 'ಪಾಲುದಾರ ಸಂಸ್ಥೆಗಳು'],
    ];
    $achievementItems = [
        ['icon' => 'fa-mountain', 'title' => 'Natural Resource Management', 'title_kn' => 'ನೈಸರ್ಗಿಕ ಸಂಪನ್ಮೂಲ ನಿರ್ವಹಣೆ', 'body' => 'Treated 20,000+ hectares of dry land for agricultural use', 'body_kn' => 'ಕೃಷಿ ಬಳಕೆಗಾಗಿ 20,000+ ಹೆಕ್ಟೇರ್ ಒಣ ಭೂಮಿಯನ್ನು ಸಂಸ್ಕರಿಸಲಾಗಿದೆ', 'number' => '20,000+'],
        ['icon' => 'fa-female', 'title' => 'Women Empowerment', 'title_kn' => 'ಮಹಿಳಾ ಸಬಲೀಕರಣ', 'body' => 'Established 3,000+ Self-Help Groups (SHGs) supporting women-led businesses', 'body_kn' => 'ಮಹಿಳಾ ನೇತೃತ್ವದ ವ್ಯವಹಾರಗಳಿಗೆ ಬೆಂಬಲ ನೀಡುವ 3,000+ ಸ್ವಸಹಾಯ ಗುಂಪುಗಳನ್ನು ಸ್ಥಾಪಿಸಲಾಗಿದೆ', 'number' => '3,000+'],
        ['icon' => 'fa-child', 'title' => 'Child Welfare & Adoption', 'title_kn' => 'ಮಕ್ಕಳ ಕಲ್ಯಾಣ ಮತ್ತು ದತ್ತು', 'body' => 'Rescued 52+ abandoned infants and facilitated legal adoptions', 'body_kn' => '52+ ಪರಿತ್ಯಕ್ತ ಶಿಶುಗಳನ್ನು ರಕ್ಷಿಸಿ ಕಾನೂನುಬದ್ಧ ದತ್ತುಗಳಿಗೆ ಅನುಕೂಲ ಮಾಡಿಕೊಡಲಾಗಿದೆ', 'number' => '52+'],
        ['icon' => 'fa-home', 'title' => 'Housing for the Poor', 'title_kn' => 'ಬಡವರಿಗೆ ವಸತಿ', 'body' => 'Facilitated construction of 1,500+ houses for Beedi workers and BPL families', 'body_kn' => 'ಬೀಡಿ ಕಾರ್ಮಿಕರು ಮತ್ತು ಬಿಪಿಎಲ್ ಕುಟುಂಬಗಳಿಗೆ 1,500+ ಮನೆಗಳ ನಿರ್ಮಾಣಕ್ಕೆ ಅನುಕೂಲ', 'number' => '1,500+'],
        ['icon' => 'fa-leaf', 'title' => 'Sustainable Farming', 'title_kn' => 'ಸುಸ್ಥಿರ ಕೃಷಿ', 'body' => 'Helped farmers transition to organic agriculture and obtain certifications', 'body_kn' => 'ರೈತರು ಸಾವಯವ ಕೃಷಿಗೆ ಪರಿವರ್ತನೆ ಹೊಂದಲು ಮತ್ತು ಪ್ರಮಾಣೀಕರಣ ಪಡೆಯಲು ಸಹಾಯ', 'number' => '500+'],
        ['icon' => 'fa-tools', 'title' => 'Skill Development', 'title_kn' => 'ಕೌಶಲ್ಯ ಅಭಿವೃದ್ಧಿ', 'body' => 'Trained 500+ rural women annually in entrepreneurship', 'body_kn' => 'ವಾರ್ಷಿಕವಾಗಿ 500+ ಗ್ರಾಮೀಣ ಮಹಿಳೆಯರಿಗೆ ಉದ್ಯಮಶೀಲತೆಯಲ್ಲಿ ತರಬೇತಿ', 'number' => '500+'],
    ];

    $directorName = $ngo->founder_name ?: 'Shri. Mahesh Chandra Guru';
    $directorImg = asset('assets/Themes/assets/images/leadership/guru.jpeg');
    $directorHighlights = [
        ['icon' => 'fa-award', 'strong' => '30+ Years of Service', 'strong_kn' => '30+ ವರ್ಷಗಳ ಸೇವೆ', 'span' => 'Dedicated leadership in social development', 'span_kn' => 'ಸಾಮಾಜಿಕ ಅಭಿವೃದ್ಧಿಯಲ್ಲಿ ಸಮರ್ಪಿತ ನಾಯಕತ್ವ'],
        ['icon' => 'fa-users', 'strong' => '50K+ Lives Impacted', 'strong_kn' => '50K+ ಜೀವನಗಳ ಮೇಲೆ ಪ್ರಭಾವ', 'span' => 'Transforming communities across Karnataka', 'span_kn' => 'ಕರ್ನಾಟಕದಾದ್ಯಂತ ಸಮುದಾಯಗಳ ಪರಿವರ್ತನೆ'],
        ['icon' => 'fa-handshake', 'strong' => 'State-level Leadership', 'strong_kn' => 'ರಾಜ್ಯ ಮಟ್ಟದ ನಾಯಕತ್ವ', 'span' => 'Coordinating NGOs through FEVOURD-K', 'span_kn' => 'FEVOURD-K ಮೂಲಕ ಸ್ವಯಂಸೇವಾ ಸಂಸ್ಥೆಗಳ ಸಮನ್ವಯ'],
    ];
    $directorQualities = [
        ['icon' => 'fa-heart', 'label' => 'Compassionate Leader', 'label_kn' => 'ಕರುಣಾಮಯಿ ನಾಯಕ'],
        ['icon' => 'fa-lightbulb', 'label' => 'Visionary Thinker', 'label_kn' => 'ದೂರದೃಷ್ಟಿಯ ಚಿಂತಕ'],
        ['icon' => 'fa-hands-helping', 'label' => 'Dedicated Servant', 'label_kn' => 'ಸಮರ್ಪಿತ ಸೇವಕ'],
        ['icon' => 'fa-graduation-cap', 'label' => 'Wise Mentor', 'label_kn' => 'ಜ್ಞಾನಿ ಮಾರ್ಗದರ್ಶಕ'],
    ];

    $leaders = [
        ['name' => 'Dr. R L Jagadish', 'role' => 'President', 'role_kn' => 'ಅಧ್ಯಕ್ಷ', 'img' => 'jaga.avif', 'featured' => false,
            'body' => 'Leading '.$ngo->name.' with vision and expertise in social development, driving strategic initiatives and organizational growth.',
            'body_kn' => $ngo->name.' ಸಂಸ್ಥೆಯನ್ನು ದೂರದೃಷ್ಟಿ ಮತ್ತು ಸಾಮಾಜಿಕ ಅಭಿವೃದ್ಧಿಯ ಪರಿಣತಿಯೊಂದಿಗೆ ಮುನ್ನಡೆಸುತ್ತಾ, ಕಾರ್ಯತಂತ್ರ ಉಪಕ್ರಮಗಳು ಮತ್ತು ಸಾಂಸ್ಥಿಕ ಬೆಳವಣಿಗೆಯನ್ನು ಮುನ್ನಡೆಸುತ್ತಿದ್ದಾರೆ.',
            'tags' => ['Visionary Leader', 'Strategic Planning', 'Social Development'],
            'tags_kn' => ['ದೂರದೃಷ್ಟಿಯ ನಾಯಕ', 'ಕಾರ್ಯತಂತ್ರ ಯೋಜನೆ', 'ಸಾಮಾಜಿಕ ಅಭಿವೃದ್ಧಿ']],
        ['name' => $directorName, 'role' => 'Director & Secretary', 'role_kn' => 'ನಿರ್ದೇಶಕ ಮತ್ತು ಕಾರ್ಯದರ್ಶಿ', 'img' => 'guru.jpeg', 'featured' => true,
            'body' => 'Leading '.$ngo->name.' with exceptional vision and unwavering commitment. Also serving as State President of FEVOURD-K, coordinating voluntary organizations across Karnataka.',
            'body_kn' => $ngo->name.' ಸಂಸ್ಥೆಯನ್ನು ಅಸಾಧಾರಣ ದೂರದೃಷ್ಟಿ ಮತ್ತು ಅಚಲ ಬದ್ಧತೆಯೊಂದಿಗೆ ಮುನ್ನಡೆಸುತ್ತಿದ್ದಾರೆ. FEVOURD-K ರಾಜ್ಯ ಅಧ್ಯಕ್ಷರಾಗಿಯೂ ಸೇವೆ ಸಲ್ಲಿಸುತ್ತಾ, ಕರ್ನಾಟಕದಾದ್ಯಂತ ಸ್ವಯಂಸೇವಾ ಸಂಸ್ಥೆಗಳನ್ನು ಸಮನ್ವಯಗೊಳಿಸುತ್ತಿದ್ದಾರೆ.',
            'tags' => ['Dedicated Service', 'State Leadership', '30+ Years Experience'],
            'tags_kn' => ['ಸಮರ್ಪಿತ ಸೇವೆ', 'ರಾಜ್ಯ ನಾಯಕತ್ವ', '30+ ವರ್ಷಗಳ ಅನುಭವ']],
        ['name' => 'Shri. Krishnegowda', 'role' => 'Treasurer', 'role_kn' => 'ಖಜಾಂಚಿ', 'img' => 'krishna.avif', 'featured' => false,
            'body' => 'Managing financial operations with integrity and ensuring transparency in all monetary matters, maintaining fiscal responsibility.',
            'body_kn' => 'ಪ್ರಾಮಾಣಿಕತೆಯಿಂದ ಆರ್ಥಿಕ ಕಾರ್ಯಾಚರಣೆಗಳನ್ನು ನಿರ್ವಹಿಸುತ್ತಾ, ಎಲ್ಲಾ ಹಣಕಾಸಿನ ವಿಷಯಗಳಲ್ಲಿ ಪಾರದರ್ಶಕತೆಯನ್ನು ಖಚಿತಪಡಿಸುತ್ತಾ, ಆರ್ಥಿಕ ಜವಾಬ್ದಾರಿಯನ್ನು ಕಾಪಾಡುತ್ತಿದ್ದಾರೆ.',
            'tags' => ['Financial Expert', 'Transparency', 'Fiscal Management'],
            'tags_kn' => ['ಹಣಕಾಸು ತಜ್ಞ', 'ಪಾರದರ್ಶಕತೆ', 'ಆರ್ಥಿಕ ನಿರ್ವಹಣೆ']],
    ];

    $successStories = [
        ['img' => 'assets/Themes/assets/images/impact/success-story-1.jpg', 'title' => 'Environmental Leadership', 'title_kn' => 'ಪರಿಸರ ನಾಯಕತ್ವ', 'date' => 'June 2020', 'date_kn' => 'ಜೂನ್ 2020',
            'body' => 'Successfully organized World Environment Day with Police Department collaboration, promoting environmental awareness across Doddakoppalu Mandya. Over 500 community members participated in tree plantation and cleanliness drives.',
            'body_kn' => 'ಪೊಲೀಸ್ ಇಲಾಖೆಯ ಸಹಯೋಗದೊಂದಿಗೆ ವಿಶ್ವ ಪರಿಸರ ದಿನವನ್ನು ಯಶಸ್ವಿಯಾಗಿ ಆಯೋಜಿಸಿ, ದೊಡ್ಡಕೊಪ್ಪಲು ಮಂಡ್ಯದಾದ್ಯಂತ ಪರಿಸರ ಜಾಗೃತಿ ಮೂಡಿಸಲಾಯಿತು. 500ಕ್ಕೂ ಹೆಚ್ಚು ಸಮುದಾಯದ ಸದಸ್ಯರು ಸಸಿ ನೆಡುವ ಮತ್ತು ಸ್ವಚ್ಛತಾ ಅಭಿಯಾನಗಳಲ್ಲಿ ಭಾಗವಹಿಸಿದರು.',
            'stats' => [['icon' => 'fa-tree', 'label' => '500+ Trees Planted', 'label_kn' => '500+ ಸಸಿಗಳ ನೆಡುವಿಕೆ'], ['icon' => 'fa-users', 'label' => '500+ Participants', 'label_kn' => '500+ ಭಾಗವಹಿಸಿದವರು']]],
        ['img' => 'assets/Themes/assets/images/impact/success-story-2.jpg', 'title' => 'Emergency Response Excellence', 'title_kn' => 'ತುರ್ತು ಪ್ರತಿಕ್ರಿಯೆಯ ಶ್ರೇಷ್ಠತೆ', 'date' => 'June 2020', 'date_kn' => 'ಜೂನ್ 2020',
            'body' => 'Distributed 100 grocery kits to needy people and farm laborers during COVID-19 pandemic, reaching the most vulnerable communities. Our rapid response team ensured essential supplies reached remote villages within 48 hours.',
            'body_kn' => 'ಕೋವಿಡ್-19 ಸಾಂಕ್ರಾಮಿಕ ಸಮಯದಲ್ಲಿ ಅಗತ್ಯವಿರುವ ಜನರು ಮತ್ತು ಕೃಷಿ ಕಾರ್ಮಿಕರಿಗೆ 100 ದಿನಸಿ ಕಿಟ್‌ಗಳನ್ನು ವಿತರಿಸಿ, ಅತ್ಯಂತ ದುರ್ಬಲ ಸಮುದಾಯಗಳನ್ನು ತಲುಪಲಾಯಿತು. ನಮ್ಮ ಕ್ಷಿಪ್ರ ಪ್ರತಿಕ್ರಿಯೆ ತಂಡವು 48 ಗಂಟೆಗಳೊಳಗೆ ದೂರದ ಹಳ್ಳಿಗಳಿಗೆ ಅಗತ್ಯ ಸಾಮಗ್ರಿಗಳನ್ನು ತಲುಪಿಸಿತು.',
            'stats' => [['icon' => 'fa-box', 'label' => '100+ Kits Distributed', 'label_kn' => '100+ ಕಿಟ್‌ಗಳ ವಿತರಣೆ'], ['icon' => 'fa-clock', 'label' => '48 Hours Response', 'label_kn' => '48 ಗಂಟೆಗಳ ಪ್ರತಿಕ್ರಿಯೆ']]],
        ['img' => 'assets/Themes/assets/images/impact/success-story-3.jpg', 'title' => 'Child Rights Advocacy', 'title_kn' => 'ಮಕ್ಕಳ ಹಕ್ಕುಗಳ ಪ್ರತಿಪಾದನೆ', 'date' => 'February 2020', 'date_kn' => 'ಫೆಬ್ರವರಿ 2020',
            'body' => 'Conducted Childline school awareness program at Mahadevpura GHPS school, protecting and educating children about their rights. The program reached 200+ students and trained 15 teachers as child protection advocates.',
            'body_kn' => 'ಮಹಾದೇವಪುರ ಜಿಎಚ್‌ಪಿಎಸ್ ಶಾಲೆಯಲ್ಲಿ ಚೈಲ್ಡ್‌ಲೈನ್ ಶಾಲಾ ಜಾಗೃತಿ ಕಾರ್ಯಕ್ರಮವನ್ನು ನಡೆಸಿ, ಮಕ್ಕಳಿಗೆ ಅವರ ಹಕ್ಕುಗಳ ಬಗ್ಗೆ ರಕ್ಷಣೆ ಮತ್ತು ಶಿಕ್ಷಣ ನೀಡಲಾಯಿತು. ಈ ಕಾರ್ಯಕ್ರಮವು 200+ ವಿದ್ಯಾರ್ಥಿಗಳನ್ನು ತಲುಪಿ, 15 ಶಿಕ್ಷಕರಿಗೆ ಮಕ್ಕಳ ರಕ್ಷಣಾ ಪ್ರತಿಪಾದಕರಾಗಿ ತರಬೇತಿ ನೀಡಿತು.',
            'stats' => [['icon' => 'fa-child', 'label' => '200+ Children Reached', 'label_kn' => '200+ ಮಕ್ಕಳನ್ನು ತಲುಪಲಾಗಿದೆ'], ['icon' => 'fa-chalkboard-teacher', 'label' => '15 Teachers Trained', 'label_kn' => '15 ಶಿಕ್ಷಕರಿಗೆ ತರಬೇತಿ']]],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ngo->name }} | FEVOURD-K</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($ngo->description ?? ''), 160) }}">
    <meta name="theme-color" content="{{ $hex }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Kannada webfonts (Inter/Fraunces have no Kannada glyphs) — Noto Sans/Serif Kannada --}}
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Kannada:wght@300;400;500;600;700&family=Noto+Serif+Kannada:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Set the language class before paint to avoid a flash of the wrong language/font --}}
    <script>
        (function () {
            try {
                var s = null;
                try { s = new URLSearchParams(window.location.search).get('lang'); } catch (e) {}
                if (s !== 'kn' && s !== 'en') {
                    try { s = localStorage.getItem('ngo_lang'); } catch (e) {}
                }
                if (s !== 'kn' && s !== 'en') {
                    var c = document.cookie.match(/(?:^|; )ngo_lang=([^;]+)/);
                    s = c ? decodeURIComponent(c[1]) : null;
                }
                if (s !== 'kn' && s !== 'en') {
                    var n = (navigator.language || navigator.userLanguage || '').toLowerCase();
                    s = n.indexOf('kn') === 0 ? 'kn' : 'en';
                }
                if (s === 'kn') {
                    document.documentElement.classList.add('lang-kn');
                    document.documentElement.lang = 'kn';
                }
            } catch (e) {}
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('assets/Themes/styles.css') }}">
    <style>
        :root {
            --primary-color: {{ $hex }};
            --secondary-color: {{ $hex }};
            --accent-color: {{ $hex }};
            --linkedin-blue: {{ $hex }};
        }
        .ngo-preview-ribbon {
            position: fixed;
            top: 12px;
            right: 12px;
            z-index: 100000;
            background: #111827;
            color: #fff;
            padding: 8px 14px;
            border-radius: 10px;
            font: 600 12px/1.2 Inter, system-ui, sans-serif;
            box-shadow: 0 4px 14px rgba(0,0,0,.2);
        }
        .ngo-preview-ribbon a {
            color: #93c5fd;
            margin-left: 8px;
        }
        /* Footer "Powered by NGO OS · Vikas Guru" badge */
        .fk-footer-bottom {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }
        .fk-footer-bottom .fk-copy { margin: 0; opacity: .85; }
        .fk-powered {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font: 500 13.5px/1 Inter, system-ui, sans-serif;
            color: rgba(255,255,255,.78);
        }
        .fk-powered__label { letter-spacing: .03em; text-transform: uppercase; font-size: 11px; opacity: .7; }
        .fk-powered__badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 999px;
            font-weight: 800;
            letter-spacing: .02em;
            color: #fff;
            background: linear-gradient(135deg, {{ $hex }}, #111827);
            box-shadow: 0 6px 18px -6px {{ $hex }}99, inset 0 0 0 1px rgba(255,255,255,.14);
        }
        .fk-powered__badge i { font-size: 12px; opacity: .92; }
        .fk-powered__dot { opacity: .45; }
        .fk-powered__by strong {
            background: linear-gradient(90deg, #fff, {{ $hex }});
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        @media (max-width: 640px) {
            .fk-footer-bottom { flex-direction: column; align-items: flex-start; }
        }

        /* ---- Kannada language mode: apply Noto Kannada via per-glyph fallback ---- */
        /* Inter lacks Kannada glyphs, so listing it first keeps Latin in Inter while
           Kannada codepoints fall through to Noto Sans/Serif Kannada — no tofu. */
        html.lang-kn body,
        html.lang-kn input,
        html.lang-kn textarea,
        html.lang-kn button,
        html.lang-kn .nav-link,
        html.lang-kn p,
        html.lang-kn span,
        html.lang-kn li,
        html.lang-kn a,
        html.lang-kn blockquote {
            font-family: 'Inter', 'Noto Sans Kannada', system-ui, sans-serif;
        }
        html.lang-kn h1, html.lang-kn h2, html.lang-kn h3,
        html.lang-kn h4, html.lang-kn h5, html.lang-kn h6,
        html.lang-kn .section-title, html.lang-kn .banner-title,
        html.lang-kn .loader-text {
            font-family: 'Inter', 'Noto Serif Kannada', 'Noto Sans Kannada', serif;
        }
        /* Kannada strings run longer — give headings/buttons a touch more breathing room */
        html.lang-kn .nav-link { letter-spacing: 0; }
        html.lang-kn .btn { white-space: normal; }

        /* ---- EN | ಕನ್ನಡ toggle pill (on-brand: gold #f2b40c active, paper/ink) ---- */
        .lang-toggle {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            padding: 3px;
            border-radius: 999px;
            background: rgba(13, 31, 92, 0.06);
            border: 1px solid rgba(13, 31, 92, 0.12);
            line-height: 1;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            flex: 0 0 auto;
        }
        .lang-toggle__opt {
            appearance: none;
            border: 0;
            background: transparent;
            cursor: pointer;
            font: 700 12.5px/1 'Inter', system-ui, sans-serif;
            color: #475569;
            padding: 7px 12px;
            border-radius: 999px;
            transition: color .18s ease, background .18s ease, box-shadow .18s ease, transform .18s ease;
            white-space: nowrap;
        }
        .lang-toggle__opt[data-lang-opt="kn"] {
            font-family: 'Noto Sans Kannada', 'Inter', sans-serif;
            font-weight: 600;
        }
        .lang-toggle__opt:hover { color: #0d1f5c; }
        .lang-toggle__opt.is-active {
            color: #1a1300;
            background: linear-gradient(135deg, #ffd24a, #f2b40c);
            box-shadow: 0 4px 12px -3px rgba(242, 180, 12, .55), inset 0 0 0 1px rgba(255,255,255,.35);
        }
        .lang-toggle__opt:focus-visible { outline: 2px solid #f2b40c; outline-offset: 2px; }
        /* Header pill keeps its own contrast on light/scrolled navbars */
        .nav-content .lang-toggle { margin-left: 14px; }
        @media (max-width: 1024px) {
            /* logo | pill | hamburger — logo shrinks so the pill stays fully on-screen */
            .nav-content { gap: 8px; }
            .nav-content .nav-logo { order: 1; min-width: 0; flex: 1 1 auto; }
            .nav-content .nav-logo .logo-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .nav-content .lang-toggle { order: 2; margin-left: 0; flex: 0 0 auto; }
            .nav-content .hamburger { order: 3; flex: 0 0 auto; }
        }
        @media (max-width: 480px) {
            .lang-toggle__opt { padding: 6px 9px; font-size: 11.5px; }
            .lang-toggle { padding: 2px; }
        }
    </style>
</head>
<body>
    @if($preview)
        <div class="ngo-preview-ribbon">
            Preview
            <a href="{{ url('/ngo/digitalization') }}">Edit content</a>
        </div>
    @endif

    @if(empty($isOwner))
        <div class="fk-follow-bar" style="--fkc: {{ $hex }}">
            <div class="fk-follow-bar__counts">
                <span><strong>{{ number_format($followersCount ?? 0) }}</strong> <span data-kn="ಅನುಸರಿಸುತ್ತಿದ್ದಾರೆ">following</span></span>
                <span class="fk-follow-bar__dot">·</span>
                <span><strong>{{ number_format($supportersCount ?? 0) }}</strong> <span data-kn="ಬೆಂಬಲಿಸುತ್ತಿದ್ದಾರೆ">supporting</span></span>
            </div>
            <div class="fk-follow-bar__actions">
                @if(!empty($authed))
                    <form method="POST" action="{{ url('/ngos/'.$ngo->id.'/follow') }}">
                        @csrf
                        <button type="submit" class="fk-fb-btn {{ !empty($isFollowing) ? 'is-on' : '' }}" data-kn="{!! !empty($isFollowing) ? '&#10003; ಅನುಸರಿಸುತ್ತಿದ್ದೀರಿ' : '&#43; ಅನುಸರಿಸಿ' !!}">
                            {!! !empty($isFollowing) ? '&#10003; Following' : '&#43; Follow' !!}
                        </button>
                    </form>
                    <form method="POST" action="{{ url('/ngos/'.$ngo->id.'/support') }}">
                        @csrf
                        <button type="submit" class="fk-fb-btn fk-fb-btn--support {{ !empty($isSupporting) ? 'is-on' : '' }}" data-kn="{!! !empty($isSupporting) ? '&#9829; ಬೆಂಬಲಿಸುತ್ತಿದ್ದೀರಿ' : '&#9825; ಬೆಂಬಲಿಸಿ' !!}">
                            {!! !empty($isSupporting) ? '&#9829; Supporting' : '&#9825; Support' !!}
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="fk-fb-btn" data-kn="&#43; ಅನುಸರಿಸಿ">&#43; Follow</a>
                    <a href="{{ url('/login') }}" class="fk-fb-btn fk-fb-btn--support" data-kn="&#9825; ಬೆಂಬಲಿಸಿ">&#9825; Support</a>
                @endif
            </div>
        </div>
        <style>
            .fk-follow-bar { position: fixed; z-index: 99990; left: 50%; bottom: 18px; transform: translateX(-50%);
                display: flex; align-items: center; gap: 14px; padding: 10px 12px 10px 18px;
                background: rgba(17,24,39,.92); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.12);
                border-radius: 999px; box-shadow: 0 18px 44px -16px rgba(0,0,0,.55); color: #fff;
                font: 500 14px/1 Inter, system-ui, sans-serif; max-width: calc(100vw - 24px); }
            .fk-follow-bar__counts { display: flex; align-items: center; gap: 8px; white-space: nowrap; color: #d1d5db; font-size: 13px; }
            .fk-follow-bar__counts strong { color: #fff; }
            .fk-follow-bar__dot { opacity: .5; }
            .fk-follow-bar__actions { display: flex; gap: 8px; }
            .fk-follow-bar form { margin: 0; }
            .fk-fb-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 999px;
                border: 0; cursor: pointer; font: 700 13.5px/1 Inter, system-ui, sans-serif; text-decoration: none;
                background: var(--fkc, #2e7d32); color: #fff; transition: transform .18s ease, filter .18s ease, opacity .18s ease; }
            .fk-fb-btn:hover { transform: translateY(-2px); filter: brightness(1.08); }
            .fk-fb-btn.is-on { background: rgba(255,255,255,.16); color: #fff; }
            .fk-fb-btn--support { background: #e11d48; }
            .fk-fb-btn--support.is-on { background: rgba(225,29,72,.22); color: #fecdd3; }
            @media (max-width: 560px) {
                .fk-follow-bar { flex-direction: column; gap: 10px; border-radius: 22px; left: 12px; right: 12px; transform: none; max-width: none; padding: 14px; }
                .fk-follow-bar__actions { width: 100%; }
                .fk-fb-btn { flex: 1; justify-content: center; }
            }
        </style>
    @endif

    <div class="loader">
        <div class="loader-content">
            <div class="loader-logo">
                <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}">
            </div>
            <div class="loader-text">{{ strtoupper(\Illuminate\Support\Str::limit($ngo->name, 28)) }}</div>
            <div class="loader-subtitle" data-kn="ಸಮುದಾಯ ಪರಿಣಾಮ">Community impact</div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
            <div class="loader-dots">
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
            </div>
        </div>
    </div>

    <header class="header">
        <nav class="navbar">
            <div class="nav-content">
                <div class="nav-logo">
                    <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}" class="logo-img">
                    <span class="logo-text">{{ \Illuminate\Support\Str::limit($ngo->name, 18) }}</span>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item"><a href="#home" class="nav-link" data-kn="ಮುಖಪುಟ">Home</a></li>
                    <li class="nav-item"><a href="#about" class="nav-link" data-kn="ನಮ್ಮ ಬಗ್ಗೆ">About</a></li>
                    <li class="nav-item"><a href="#activities" class="nav-link" data-kn="ಚಟುವಟಿಕೆಗಳು">Activities</a></li>
                    <li class="nav-item"><a href="#impact" class="nav-link" data-kn="ಪರಿಣಾಮ">Impact</a></li>
                    <li class="nav-item"><a href="#gallery" class="nav-link" data-kn="ಚಿತ್ರಸಂಪುಟ">Gallery</a></li>
                    <li class="nav-item"><a href="#blog" class="nav-link" data-kn="ಕಥೆಗಳು">Stories</a></li>
                    <li class="nav-item"><a href="#contact" class="nav-link" data-kn="ಸಂಪರ್ಕ">Contact</a></li>
                    <li class="nav-item"><a href="#donate" class="nav-link btn btn-primary" data-kn="ದೇಣಿಗೆ">Donate</a></li>
                </ul>
                <div class="lang-toggle" id="langToggle" role="group" aria-label="Language / ಭಾಷೆ">
                    <button type="button" class="lang-toggle__opt" data-lang-opt="en" aria-pressed="true">EN</button>
                    <button type="button" class="lang-toggle__opt" data-lang-opt="kn" aria-pressed="false">ಕನ್ನಡ</button>
                </div>
                <div class="hamburger"><span></span><span></span><span></span></div>
                <div class="fullscreen-menu" id="fullscreenMenu">
                    <button class="menu-close" id="menuClose" type="button"><i class="fas fa-times"></i></button>
                    <div class="menu-content">
                        <a href="#home" class="menu-item"><i class="fas fa-home"></i><span data-kn="ಮುಖಪುಟ">Home</span></a>
                        <a href="#about" class="menu-item"><i class="fas fa-info-circle"></i><span data-kn="ನಮ್ಮ ಬಗ್ಗೆ">About</span></a>
                        <a href="#activities" class="menu-item"><i class="fas fa-tasks"></i><span data-kn="ಚಟುವಟಿಕೆಗಳು">Activities</span></a>
                        <a href="#impact" class="menu-item"><i class="fas fa-chart-line"></i><span data-kn="ಪರಿಣಾಮ">Impact</span></a>
                        <a href="#gallery" class="menu-item"><i class="fas fa-images"></i><span data-kn="ಚಿತ್ರಸಂಪುಟ">Gallery</span></a>
                        <a href="#blog" class="menu-item"><i class="fas fa-blog"></i><span data-kn="ಕಥೆಗಳು">Stories</span></a>
                        <a href="#contact" class="menu-item"><i class="fas fa-envelope"></i><span data-kn="ಸಂಪರ್ಕ">Contact</span></a>
                        <a href="#donate" class="menu-item donate"><i class="fas fa-heart"></i><span data-kn="ದೇಣಿಗೆ">Donate</span></a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section id="home" class="hero">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-banner-carousel">
            <div class="banner-carousel-container">
                @foreach($microsite['slides'] as $idx => $slide)
                    <div class="banner-slide banner-slide-{{ ($idx % 4) + 1 }}">
                        <div class="banner-content">
                            <h1 class="banner-title" data-kn="{{ $slide['title_kn'] ?? '' }}">{{ $slide['title'] }}</h1>
                            <h2 class="banner-subtitle" data-kn="{{ $slide['subtitle_kn'] ?? '' }}">{{ $slide['subtitle'] }}</h2>
                            <p class="banner-description" data-kn="{{ $slide['description_kn'] ?? '' }}">{{ $slide['description'] }}</p>
                            <div class="banner-stats">
                                @foreach($slide['stats'] as $st)
                                    <div class="banner-stat">
                                        <div class="banner-stat-number">{{ $st['number'] }}</div>
                                        <div class="banner-stat-label" data-kn="{{ $st['label_kn'] ?? '' }}">{{ $st['label'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="banner-actions">
                                <a href="#donate" class="btn btn-primary"><i class="fas fa-heart"></i> <span data-kn="ದೇಣಿಗೆ">Donate</span></a>
                                <a href="#activities" class="btn btn-secondary"><i class="fas fa-tasks"></i> <span data-kn="ಕಾರ್ಯಕ್ರಮಗಳು">Programmes</span></a>
                                <a href="#contact" class="btn btn-outline"><i class="fas fa-envelope"></i> <span data-kn="ಸಂಪರ್ಕ">Contact</span></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="banner-indicators">
                @foreach($microsite['slides'] as $idx => $_)
                    <div class="banner-dot {{ $idx === 0 ? 'active' : '' }}"></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="quick-stats">
        <div class="container">
            <div class="stats-grid">
                @foreach($microsite['quick_stats'] as $qs)
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3 data-kn="{{ $qs['title_kn'] ?? '' }}">{{ $qs['title'] }}</h3>
                            <p data-kn="{{ $qs['subtitle_kn'] ?? '' }}">{{ $qs['subtitle'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="blog" class="blog">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="ಕಥೆಗಳು ಮತ್ತು ನವೀಕರಣಗಳು">Stories &amp; updates</h2>
                <p class="section-subtitle" data-kn="ನಮ್ಮ ಕೆಲಸದ ಮುಖ್ಯಾಂಶಗಳು">Highlights from our work — edit these under Digitalization → Microsite content</p>
            </div>
            <div class="blog-carousel-container">
                <div class="blog-carousel-track">
                    @foreach($microsite['stories'] as $story)
                        <article class="blog-card">
                            <div class="blog-image">
                                <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}">
                                <div class="blog-category" data-kn="{{ $story['category_kn'] ?? '' }}">{{ $story['category'] }}</div>
                                <div class="blog-date" data-kn="{{ $story['date_kn'] ?? '' }}">{{ $story['date'] }}</div>
                            </div>
                            <div class="blog-content">
                                <h3 data-kn="{{ $story['title_kn'] ?? '' }}">{{ $story['title'] }}</h3>
                                <p data-kn="{{ $story['body_kn'] ?? '' }}">{{ $story['body'] }}</p>
                                <div class="blog-meta">
                                    <span class="author">{{ $ngo->name }}</span>
                                    <span class="read-time" data-kn="ನವೀಕರಣ">Update</span>
                                    <button type="button" class="read-aloud-btn" aria-label="Read aloud"><i class="fas fa-volume-up"></i></button>
                                </div>
                                <a href="#contact" class="blog-link" data-kn="ನಮ್ಮನ್ನು ಸಂಪರ್ಕಿಸಿ">Connect with us</a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="blog-carousel-controls">
                    <button type="button" class="blog-carousel-btn blog-carousel-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                    <button type="button" class="blog-carousel-btn blog-carousel-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="blog-carousel-indicators">
                    @for($i = 0; $i < $blogSlides; $i++)
                        <button type="button" class="blog-indicator {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></button>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="{{ $microsite['about']['section_title_kn'] ?? '' }}">{{ $microsite['about']['section_title'] }}</h2>
                <p class="section-subtitle" data-kn="{{ $microsite['about']['section_subtitle_kn'] ?? '' }}">{{ $microsite['about']['section_subtitle'] }}</p>
            </div>
            <div class="who-we-are">
                <div class="who-we-are-content">
                    <div class="who-we-are-header">
                        <div class="vikasana-logo">
                            <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}">
                        </div>
                        <div class="who-we-are-text">
                            <h3 data-kn="{{ $microsite['about']['who_heading_kn'] ?? '' }}">{{ $microsite['about']['who_heading'] }}</h3>
                            <p>{!! nl2br(e($microsite['about']['who_byline'])) !!}</p>
                        </div>
                    </div>
                    @foreach($microsite['about']['paragraphs'] as $pi => $para)
                        <p data-kn="{{ $microsite['about']['paragraphs_kn'][$pi] ?? '' }}">{{ $para }}</p>
                    @endforeach
                </div>
                <div class="who-we-are-visual">
                    <div class="vision-quote">
                        <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                        <div class="quote-content">
                            <h4 data-kn="ನಮ್ಮ ದೃಷ್ಟಿಕೋನ">Our vision</h4>
                            <blockquote data-kn="{{ $microsite['about']['vision_quote_kn'] ?? '' }}">"{{ $microsite['about']['vision_quote'] }}"</blockquote>
                        </div>
                        <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                    </div>
                    <div class="vikasana-stats">
                        <div class="stat-item">
                            <div class="stat-number" data-target="1">1</div>
                            <div class="stat-label" data-kn="ಧ್ಯೇಯ">Mission</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="100">100%</div>
                            <div class="stat-label" data-kn="ಬದ್ಧತೆ">Commitment</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-target="24">24/7</div>
                            <div class="stat-label" data-kn="ಸಮರ್ಪಣೆ">Dedication</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Mission -->
            <div class="mission-section">
                <div class="mission-header">
                    <div class="mission-icon-large"><i class="fas fa-bullseye"></i></div>
                    <div class="mission-text">
                        <h3 data-kn="ನಮ್ಮ ಧ್ಯೇಯ">Our Mission</h3>
                        <p data-kn="{{ $microsite['about']['mission_intro_kn'] ?? 'ಕಾರ್ಯತಂತ್ರ ಮಧ್ಯಸ್ಥಿಕೆಗಳ ಮೂಲಕ ಅಂಚಿನಲ್ಲಿರುವ ಸಮುದಾಯಗಳನ್ನು ಸಬಲೀಕರಣಗೊಳಿಸುವುದು:' }}">{{ $microsite['about']['mission_intro'] ?? 'To empower marginalized communities through strategic interventions in:' }}</p>
                    </div>
                </div>
                <div class="mission-grid">
                    @foreach($missionItems as $mi)
                        <div class="mission-item">
                            <div class="mission-icon"><i class="fas {{ $mi['icon'] }}"></i></div>
                            <div class="mission-content">
                                <h4 data-kn="{{ $mi['title_kn'] ?? '' }}">{{ $mi['title'] }}</h4>
                                <p data-kn="{{ $mi['body_kn'] ?? '' }}">{{ $mi['body'] }}</p>
                                <div class="mission-number">{{ $mi['n'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Our Approach -->
            <div class="approach-section">
                <div class="approach-header">
                    <div class="approach-icon-large"><i class="fas fa-route"></i></div>
                    <div class="approach-text">
                        <h3 data-kn="ನಮ್ಮ ವಿಧಾನ">Our Approach</h3>
                        <p data-kn="{{ $ngo->name }} ಸಾಮಾಜಿಕ ಬದಲಾವಣೆಯ ಪ್ರಮುಖ ಚಾಲಕ ಶಕ್ತಿಯಾಗಿ ಸಮುದಾಯ ಭಾಗವಹಿಸುವಿಕೆಯನ್ನು ನಂಬುತ್ತದೆ. ನಾವು ಸ್ಥಳೀಯ ಸಮುದಾಯಗಳೊಂದಿಗೆ ಕೈಜೋಡಿಸಿ ಕೆಲಸ ಮಾಡುತ್ತಾ, ಒಡೆತನ ಮತ್ತು ಸಾಮೂಹಿಕ ಜವಾಬ್ದಾರಿಯ ಭಾವನೆಯನ್ನು ಬೆಳೆಸುತ್ತೇವೆ.">{{ $ngo->name }} believes in community participation as a key driver of social change. We work hand-in-hand with local communities, fostering a sense of ownership and collective responsibility.</p>
                    </div>
                </div>
                <div class="approach-items">
                    @foreach($approachItems as $ai)
                        <div class="approach-item">
                            <div class="approach-icon"><i class="fas {{ $ai['icon'] }}"></i></div>
                            <div class="approach-content">
                                <h4 data-kn="{{ $ai['title_kn'] ?? '' }}">{{ $ai['title'] }}</h4>
                                <p data-kn="{{ $ai['body_kn'] ?? '' }}">{{ $ai['body'] }}</p>
                                <div class="approach-stats">
                                    <span class="stat-number">{{ $ai['stat'] }}</span>
                                    <span class="stat-label" data-kn="{{ $ai['stat_label_kn'] ?? '' }}">{{ $ai['stat_label'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="approach-impact">
                    @foreach($approachImpact as $aim)
                        <div class="impact-stat">
                            <div class="impact-number">{{ $aim['number'] }}</div>
                            <div class="impact-label" data-kn="{{ $aim['label_kn'] ?? '' }}">{{ $aim['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Key Achievements -->
            <div class="achievements-section">
                <h3 data-kn="ಪ್ರಮುಖ ಸಾಧನೆಗಳು ಮತ್ತು ಮೈಲಿಗಲ್ಲುಗಳು">Key Achievements &amp; Milestones</h3>
                <div class="achievements-container">
                    <div class="achievements-track">
                        @foreach($achievementItems as $ach)
                            <div class="achievement-item">
                                <div class="achievement-icon"><i class="fas {{ $ach['icon'] }}"></i></div>
                                <h4 data-kn="{{ $ach['title_kn'] ?? '' }}">{{ $ach['title'] }}</h4>
                                <p data-kn="{{ $ach['body_kn'] ?? '' }}">{{ $ach['body'] }}</p>
                                <div class="achievement-number">{{ $ach['number'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Director & Secretary -->
            <div class="guru-tribute">
                <div class="tribute-header">
                    <h3 data-kn="ನಿರ್ದೇಶಕ ಮತ್ತು ಕಾರ್ಯದರ್ಶಿ">Director &amp; Secretary</h3>
                    <p data-kn="{{ $directorName }} - {{ $ngo->name }} ಅನ್ನು ಶ್ರೇಷ್ಠತೆಯಿಂದ ಮುನ್ನಡೆಸುತ್ತಿದ್ದಾರೆ">{{ $directorName }} - Leading {{ $ngo->name }} with Excellence</p>
                    <div class="additional-roles">
                        <span class="role-badge" data-kn="ರಾಜ್ಯ ಅಧ್ಯಕ್ಷರು, FEVOURD-K">State President, FEVOURD-K</span>
                        <span class="role-description" data-kn="ಕರ್ನಾಟಕದ ನಗರ ಮತ್ತು ಗ್ರಾಮೀಣ ಅಭಿವೃದ್ಧಿಗಾಗಿ ಸ್ವಯಂಸೇವಾ ಸಂಸ್ಥೆಗಳ ಒಕ್ಕೂಟ">Federation of Voluntary Organisations for Urban and Rural Development in Karnataka</span>
                    </div>
                </div>
                <div class="tribute-content">
                    <div class="tribute-left">
                        <div class="tribute-image-container">
                            <div class="tribute-image">
                                <img src="{{ $directorImg }}" alt="{{ $directorName }}">
                            </div>
                            <div class="image-caption">
                                <strong>{{ $directorName }}</strong><br>
                                <span data-kn="ನಿರ್ದೇಶಕ ಮತ್ತು ಕಾರ್ಯದರ್ಶಿ, {{ \Illuminate\Support\Str::limit($ngo->name, 24) }}">Director &amp; Secretary, {{ \Illuminate\Support\Str::limit($ngo->name, 24) }}</span><br>
                                <span data-kn="ರಾಜ್ಯ ಅಧ್ಯಕ್ಷರು, FEVOURD-K">State President, FEVOURD-K</span>
                            </div>
                        </div>
                    </div>
                    <div class="tribute-right">
                        <div class="tribute-text">
                            <h4 data-kn="ದೂರದೃಷ್ಟಿಯ ನಾಯಕತ್ವ ಮತ್ತು ಸಮರ್ಪಣೆ">Visionary Leadership &amp; Dedication</h4>
                            <p data-kn="{{ $directorName }}, ನಮ್ಮ ಪ್ರಸ್ತುತ ನಿರ್ದೇಶಕ ಮತ್ತು ಕಾರ್ಯದರ್ಶಿ, {{ $ngo->name }} ಅನ್ನು ಅಸಾಧಾರಣ ದೂರದೃಷ್ಟಿ ಮತ್ತು ಅಚಲ ಬದ್ಧತೆಯೊಂದಿಗೆ ಮುನ್ನಡೆಸುತ್ತಿದ್ದಾರೆ. FEVOURD-K ರಾಜ್ಯ ಅಧ್ಯಕ್ಷರಾಗಿ, ಅವರು ಕರ್ನಾಟಕದಾದ್ಯಂತ ಸ್ವಯಂಸೇವಾ ಸಂಸ್ಥೆಗಳನ್ನು ಸಮನ್ವಯಗೊಳಿಸಿ ಮುನ್ನಡೆಸುತ್ತಾ, ಸಹಯೋಗವನ್ನು ಬೆಳೆಸುತ್ತಾ ರಾಜ್ಯದಾದ್ಯಂತ ಸಾಮಾಜಿಕ ಅಭಿವೃದ್ಧಿ ಉಪಕ್ರಮಗಳ ಪ್ರಭಾವವನ್ನು ಹೆಚ್ಚಿಸುತ್ತಿದ್ದಾರೆ.">{{ $directorName }}, our present Director &amp; Secretary, continues to lead {{ $ngo->name }} with exceptional vision and unwavering commitment. As the State President of FEVOURD-K, he coordinates and leads voluntary organizations across Karnataka, fostering collaboration and amplifying the impact of social development initiatives throughout the state.</p>
                            <div class="leadership-highlights">
                                @foreach($directorHighlights as $h)
                                    <div class="highlight-item">
                                        <i class="fas {{ $h['icon'] }}"></i>
                                        <div class="highlight-content">
                                            <strong data-kn="{{ $h['strong_kn'] ?? '' }}">{{ $h['strong'] }}</strong>
                                            <span data-kn="{{ $h['span_kn'] ?? '' }}">{{ $h['span'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tribute-qualities">
                                @foreach($directorQualities as $q)
                                    <div class="quality-item">
                                        <i class="fas {{ $q['icon'] }}"></i>
                                        <span data-kn="{{ $q['label_kn'] ?? '' }}">{{ $q['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tribute-impact">
                            <h4 data-kn="FEVOURD-K ಮೂಲಕ ರಾಜ್ಯವ್ಯಾಪಿ ಪ್ರಭಾವ">Statewide Impact Through FEVOURD-K</h4>
                            <p data-kn="FEVOURD-K ನಲ್ಲಿ ತಮ್ಮ ನಾಯಕತ್ವದ ಮೂಲಕ, {{ $directorName }} ಅವರು ಕರ್ನಾಟಕದಾದ್ಯಂತ ನಗರ ಮತ್ತು ಗ್ರಾಮೀಣ ಅಭಿವೃದ್ಧಿಗಾಗಿ ಸಾಮರಸ್ಯದಿಂದ ಕೆಲಸ ಮಾಡುವ ಸ್ವಯಂಸೇವಾ ಸಂಸ್ಥೆಗಳ ಪ್ರಬಲ ಜಾಲವನ್ನು ರಚಿಸಿದ್ದಾರೆ. ಅವರ ಕಾರ್ಯತಂತ್ರ ದೂರದೃಷ್ಟಿ ಮತ್ತು ಸಹಯೋಗದ ವಿಧಾನವು ಸ್ವಯಂಸೇವಾ ವಲಯವನ್ನು ಬಲಪಡಿಸಿ, ಸಾವಿರಾರು ಸಂಸ್ಥೆಗಳು ಸಮುದಾಯಗಳಿಗೆ ಹೆಚ್ಚು ಪರಿಣಾಮಕಾರಿಯಾಗಿ ಸೇವೆ ಸಲ್ಲಿಸಲು ಅನುವು ಮಾಡಿಕೊಟ್ಟಿದೆ.">Through his leadership at FEVOURD-K, {{ $directorName }} has created a powerful network of voluntary organizations working in harmony for urban and rural development across Karnataka. His strategic vision and collaborative approach have strengthened the NGO sector, enabling thousands of organizations to serve communities more effectively.</p>
                        </div>
                    </div>
                </div>
                <div class="tribute-footer">
                    <blockquote data-kn="&quot;ನಿಜವಾದ ನಾಯಕತ್ವವೆಂದರೆ ವಿನಯದಿಂದ ಇತರರಿಗೆ ಸೇವೆ ಸಲ್ಲಿಸುವುದು, ಸಕಾರಾತ್ಮಕ ಬದಲಾವಣೆಯನ್ನು ಪ್ರೇರೇಪಿಸುವುದು ಮತ್ತು ಹೆಚ್ಚಿನ ಸಾಮೂಹಿಕ ಪ್ರಭಾವಕ್ಕಾಗಿ ಸಂಸ್ಥೆಗಳ ನಡುವೆ ಸೇತುವೆ ನಿರ್ಮಿಸುವುದು.&quot;">"True leadership is about serving others with humility, inspiring positive change, and building bridges between organizations for greater collective impact."</blockquote>
                </div>
            </div>

            <div class="presence-section">
                <h3 data-kn="ಕರ್ನಾಟಕದಾದ್ಯಂತ ನಮ್ಮ ಉಪಸ್ಥಿತಿ">Our Presence Across Karnataka</h3>
                <div class="karnataka-map-container">
                    <div class="karnataka-map">
                        <div id="karnataka-map-container" class="geojson-map">
                            <svg id="karnataka-svg" class="karnataka-svg" xmlns="http://www.w3.org/2000/svg">
                                <g id="karnataka-path"></g>
                                <g id="district-markers"></g>
                            </svg>
                        </div>
                        <div class="map-legend">
                            <div class="legend-item">
                                <span class="legend-dot active"></span>
                                <span data-kn="ಸಕ್ರಿಯ ಜಿಲ್ಲೆಗಳು">Active districts</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot hq"></span>
                                <span data-kn="ಕೇಂದ್ರ ಕಚೇರಿ">Headquarters</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot upcoming"></span>
                                <span data-kn="ಮುಂಬರುವ ವಿಸ್ತರಣೆ">Upcoming expansion</span>
                            </div>
                        </div>
                    </div>
                    <div class="districts-panel">
                        <h4 data-kn="ನಾವು ಕೆಲಸ ಮಾಡುವ ಸ್ಥಳ">Where we work</h4>
                        <div class="districts-list">
                            @php
                                $presenceDistricts = $microsite['districts'] ?? ['Mandya (HQ)', 'Mysuru', 'Bengaluru Urban', 'Bengaluru Rural', 'Hassan', 'Tumakuru', 'Kodagu', 'Chamarajanagar'];
                            @endphp
                            @foreach($presenceDistricts as $d)
                                <div class="district-chip active">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $d }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="districts-stats">
                            <div class="stats-row">
                                <span class="stat-value">{{ count($presenceDistricts) }}</span>
                                <span class="stat-desc" data-kn="ತೊಡಗಿಸಿಕೊಂಡ ಜಿಲ್ಲೆಗಳು">Districts engaged</span>
                            </div>
                            <div class="stats-row">
                                <span class="stat-value">{{ $ngo->city?->name ?? 'Karnataka' }}</span>
                                <span class="stat-desc" data-kn="ಮೂಲ ಸ್ಥಳ">Base location</span>
                            </div>
                            <div class="stats-row">
                                <span class="stat-value" data-kn="ಭಾರತ">India</span>
                                <span class="stat-desc" data-kn="ಪ್ರದೇಶ">Region</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="activities" class="activities">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="ನಮ್ಮ ಚಟುವಟಿಕೆಗಳು">Our activities</h2>
                <p class="section-subtitle" data-kn="ನಮ್ಮ ಕಾರ್ಯಕ್ರಮ ಕ್ಷೇತ್ರಗಳು">Programme areas — customise titles and descriptions in Digitalization</p>
            </div>
            <div class="activities-grid">
                @foreach($microsite['programs'] as $prog)
                    <div class="activity-card">
                        <div class="activity-icon">
                            <i class="fas {{ $prog['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <h3 data-kn="{{ $prog['title_kn'] ?? '' }}">{{ $prog['title'] }}</h3>
                            <p data-kn="{{ $prog['body_kn'] ?? '' }}">{{ $prog['body'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="leadership" class="leadership">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="ನಮ್ಮ ನಾಯಕತ್ವ">Our Leadership</h2>
                <p class="section-subtitle" data-kn="{{ $ngo->name }} ಯಶಸ್ಸಿನ ಹಿಂದಿರುವ ಸಮರ್ಪಿತ ತಂಡವನ್ನು ಭೇಟಿಯಾಗಿ">Meet the dedicated team behind {{ $ngo->name }}'s success</p>
            </div>
            <div class="leadership-team">
                <h3 data-kn="ನಮ್ಮ ನಾಯಕತ್ವ ತಂಡ">Our Leadership Team</h3>
                <div class="team-grid-enhanced">
                    @foreach($leaders as $member)
                        <div class="team-member-card {{ $member['featured'] ? 'featured' : '' }}">
                            <div class="member-image">
                                <img src="{{ asset('assets/Themes/assets/images/leadership/'.$member['img']) }}" alt="{{ $member['name'] }}">
                                @if($member['featured'])
                                    <div class="featured-badge"><i class="fas fa-star"></i><span data-kn="ನಿರ್ದೇಶಕ ಮತ್ತು ಕಾರ್ಯದರ್ಶಿ">Director &amp; Secretary</span></div>
                                @endif
                            </div>
                            <div class="member-info">
                                <h4>{{ $member['name'] }}</h4>
                                <p class="member-role" data-kn="{{ $member['role_kn'] ?? '' }}">{{ $member['role'] }}</p>
                                <p data-kn="{{ $member['body_kn'] ?? '' }}">{{ $member['body'] }}</p>
                                <div class="member-qualities">
                                    @foreach($member['tags'] as $ti => $tag)
                                        <span class="quality-tag" data-kn="{{ $member['tags_kn'][$ti] ?? '' }}">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="impact" class="impact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="ನಮ್ಮ ಪರಿಣಾಮ">Our impact</h2>
                <p class="section-subtitle" data-kn="ಸಮರ್ಪಿತ ಸೇವೆಯ ಮೂಲಕ ಜೀವನ ಮತ್ತು ಸಮುದಾಯಗಳ ಪರಿವರ್ತನೆ">Transforming lives and communities through dedicated service</p>
            </div>
            <div class="impact-stats">
                @foreach($impactCards as $ic)
                    <div class="impact-card">
                        <div class="impact-icon">
                            <i class="fas {{ $ic['icon'] }}"></i>
                        </div>
                        <div class="impact-number" data-target="{{ $ic['target'] }}">{{ $ic['display'] }}</div>
                        <div class="impact-label" data-kn="{{ $ic['label_kn'] ?? '' }}">{{ $ic['label'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="impact-stories">
                <h3 data-kn="ಯಶೋಗಾಥೆಗಳು">Success Stories</h3>
                <div class="stories-grid">
                    @foreach($successStories as $ss)
                        <div class="story-card">
                            <div class="story-image">
                                <img src="{{ asset($ss['img']) }}" alt="{{ $ss['title'] }}" loading="lazy">
                            </div>
                            <div class="story-content">
                                <h4 data-kn="{{ $ss['title_kn'] ?? '' }}">{{ $ss['title'] }}</h4>
                                <p class="story-meta"><i class="fas fa-calendar"></i> <span data-kn="{{ $ss['date_kn'] ?? '' }}">{{ $ss['date'] }}</span></p>
                                <p data-kn="{{ $ss['body_kn'] ?? '' }}">{{ $ss['body'] }}</p>
                                <div class="story-stats">
                                    @foreach($ss['stats'] as $sstat)
                                        <span class="stat-item"><i class="fas {{ $sstat['icon'] }}"></i> <span data-kn="{{ $sstat['label_kn'] ?? '' }}">{{ $sstat['label'] }}</span></span>
                                    @endforeach
                                </div>
                                <a href="#contact" class="story-link" data-kn="ನಮ್ಮನ್ನು ಸಂಪರ್ಕಿಸಿ">Connect with us</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="impact-testimonials">
                <h3 data-kn="ನಮ್ಮ ಕೆಲಸ ಏಕೆ ಮುಖ್ಯ">Why our work matters</h3>
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"{{ $microsite['about']['vision_quote'] }}"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar"><i class="fas fa-quote-right"></i></div>
                            <div class="author-info">
                                <h5>{{ $ngo->name }}</h5>
                                <p data-kn="ನಮ್ಮ ಮಾರ್ಗದರ್ಶಿ ದೃಷ್ಟಿಕೋನ">Our guiding vision</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($ngo->description ?? 'Committed to lasting, community-led change across Karnataka.'), 180) }}</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar"><i class="fas fa-users"></i></div>
                            <div class="author-info">
                                <h5 data-kn="ಸಮುದಾಯ ಮೊದಲು">Community first</h5>
                                <p data-kn="ನಾವು ಹೇಗೆ ಕೆಲಸ ಮಾಡುತ್ತೇವೆ">How we work</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p data-kn="&quot;FEVOURD-K ನಲ್ಲಿ ಪಾರದರ್ಶಕ, ಜವಾಬ್ದಾರಿಯುತ ಮತ್ತು ಪರಿಶೀಲಿತ — ಪ್ರತಿ ಕೊಡುಗೆಯನ್ನು ಟ್ರ್ಯಾಕ್ ಮಾಡಿ ವರದಿ ಮಾಡಲಾಗುತ್ತದೆ.&quot;">"Transparent, accountable, and verified on FEVOURD-K — every contribution is tracked and reported."</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar"><i class="fas fa-shield-alt"></i></div>
                            <div class="author-info">
                                <h5 data-kn="ವಿಶ್ವಾಸ ಮತ್ತು ಪಾರದರ್ಶಕತೆ">Trust &amp; transparency</h5>
                                <p data-kn="ನಮ್ಮ ಬದ್ಧತೆ">Our commitment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="gallery" class="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="ಚಿತ್ರಸಂಪುಟ">Gallery</h2>
                <p class="section-subtitle" data-kn="ನಮ್ಮ ಕೆಲಸ ಮತ್ತು ಪರಿಣಾಮದ ದೃಶ್ಯ ಕಥೆಗಳು">Visual stories of our work and impact</p>
            </div>
            <div class="gallery-grid">
                @foreach($galleryItems as $g)
                    <div class="gallery-item {{ $g['size'] }}">
                        <div class="gallery-image">
                            <img src="{{ asset($g['img']) }}" alt="{{ $g['title'] }}" loading="lazy">
                            <div class="gallery-overlay">
                                <div class="gallery-content">
                                    <h4 data-kn="{{ $g['title_kn'] ?? '' }}">{{ $g['title'] }}</h4>
                                    <p data-kn="{{ $g['caption_kn'] ?? '' }}">{{ $g['caption'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="ಸಂಪರ್ಕದಲ್ಲಿರಿ">Get in touch</h2>
                <p class="section-subtitle" data-kn="{{ $microsite['contact_intro_kn'] ?? '' }}">{{ $microsite['contact_intro'] }}</p>
            </div>
            <div class="contact-enhanced">
                <div class="contact-left">
                    <div class="contact-info-card">
                        <h3 data-kn="ಸಂಪರ್ಕ ಮಾಹಿತಿ">Contact information</h3>
                        <div class="contact-items">
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="contact-details">
                                    <h4 data-kn="ವಿಳಾಸ">Address</h4>
                                    <p>{{ $ngo->address }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                                <div class="contact-details">
                                    <h4 data-kn="ದೂರವಾಣಿ">Phone</h4>
                                    <p>{{ $ngo->phone }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div class="contact-details">
                                    <h4 data-kn="ಇಮೇಲ್">Email</h4>
                                    <p><a href="mailto:{{ $ngo->email }}">{{ $ngo->email }}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="contact-social">
                            @if($ngo->facebook_url)
                                <a href="{{ $ngo->facebook_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                            @endif
                            @if($ngo->instagram_url)
                                <a href="{{ $ngo->instagram_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="contact-right">
                    <form id="contactForm" class="contact-form-card">
                        @csrf
                        <h3 data-kn="ಸಂದೇಶ ಕಳುಹಿಸಿ">Send a message</h3>
                        <p data-kn="ಈ ಫಾರ್ಮ್ ಒಂದು ಮುನ್ನೋಟ — ಸಂಸ್ಥೆಯನ್ನು ತಲುಪಲು ದಯವಿಟ್ಟು ಇಮೇಲ್ ಅಥವಾ ದೂರವಾಣಿ ಬಳಸಿ.">This form is a preview — please use email or phone to reach the organisation.</p>
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your name" data-kn-placeholder="ನಿಮ್ಮ ಹೆಸರು" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" data-kn-placeholder="ಇಮೇಲ್" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="4" placeholder="Message" data-kn-placeholder="ಸಂದೇಶ" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" data-kn="ಕಳುಹಿಸಿ">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="donate" class="donate">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" data-kn="{{ $microsite['donate']['title_kn'] ?? '' }}">{{ $microsite['donate']['title'] }}</h2>
                <p class="section-subtitle" data-kn="{{ $microsite['donate']['subtitle_kn'] ?? '' }}">{{ $microsite['donate']['subtitle'] }}</p>
            </div>
            <div class="donate-enhanced">
                <div class="donate-impact">
                    <div class="impact-header">
                        <div class="impact-icon"><i class="fas fa-heart"></i></div>
                        <h3 data-kn="ನಿಮ್ಮ ಪರಿಣಾಮ">Your impact</h3>
                        <p data-kn="{{ $microsite['donate']['impact_blurb_kn'] ?? '' }}">{{ $microsite['donate']['impact_blurb'] }}</p>
                    </div>
                    <div class="impact-stats">
                        <div class="stat-card">
                            <div class="stat-number">100%</div>
                            <div class="stat-label" data-kn="ಪಾರದರ್ಶಕ ವರದಿ">Transparent reporting</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">FEVOURD-K</div>
                            <div class="stat-label" data-kn="ಪರಿಶೀಲಿತ ವೇದಿಕೆ">Verified platform</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">₹</div>
                            <div class="stat-label" data-kn="ಸುರಕ್ಷಿತ ಪಾವತಿ">Secure checkout</div>
                        </div>
                    </div>
                </div>
                <div class="donate-payment">
                    <div class="payment-header">
                        <h3 data-kn="ಅಭಿಯಾನಗಳ ಮೂಲಕ ದೇಣಿಗೆ ನೀಡಿ">Donate via campaigns</h3>
                        <p data-kn="FEVOURD-K ನಲ್ಲಿ ಲೈವ್ ಅಭಿಯಾನಗಳನ್ನು ವೀಕ್ಷಿಸಿ ಮತ್ತು ನಮ್ಮ ವಿಶ್ವಾಸಾರ್ಹ ಪಾಲುದಾರರೊಂದಿಗೆ ಪಾವತಿಯನ್ನು ಪೂರ್ಣಗೊಳಿಸಿ.">Browse live campaigns on FEVOURD-K and complete payment with our trusted partners.</p>
                        <div class="secure-badge"><i class="fas fa-lock"></i><span data-kn="ವೇದಿಕೆ ಪಾವತಿ">Platform checkout</span></div>
                    </div>
                    <a href="{{ route('campaigns.index') }}" class="donate-btn" style="display:inline-flex;align-items:center;justify-content:center;width:100%;margin-top:1rem;text-decoration:none;">
                        <i class="fas fa-heart"></i>
                        <span data-kn="ದೇಣಿಗೆ ನೀಡಲು ಅಭಿಯಾನಗಳನ್ನು ವೀಕ್ಷಿಸಿ">View campaigns to donate</span>
                    </a>
                    @if($ngo->slug)
                        <p style="margin-top:1rem;font-size:0.9rem;color:#64748b;text-align:center;">
                            <span data-kn="ಕೋಶ:">Directory:</span> <a href="{{ url('/ngos/'.$ngo->slug) }}" data-kn="ಸಾರ್ವಜನಿಕ ಪಟ್ಟಿ">public listing</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <img src="{{ $logoUrl }}" alt="{{ $ngo->name }}">
                        </div>
                        <h4>{{ $ngo->name }}</h4>
                    </div>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($ngo->description ?? ''), 200) }}</p>
                    <div class="footer-certifications">
                        <div class="cert-item"><i class="fas fa-certificate"></i><span data-kn="{{ $ngo->verification_status === 'verified' ? 'ಪರಿಶೀಲಿತ ಎನ್‌ಜಿಒ' : 'ನೋಂದಾಯಿತ' }}">{{ $ngo->verification_status === 'verified' ? 'Verified NGO' : 'Registered' }}</span></div>
                    </div>
                </div>
                <div class="footer-section">
                    <h4 data-kn="ತ್ವರಿತ ಕೊಂಡಿಗಳು">Quick links</h4>
                    <ul class="footer-links">
                        <li><a href="#about" data-kn="ನಮ್ಮ ಬಗ್ಗೆ">About</a></li>
                        <li><a href="#activities" data-kn="ಚಟುವಟಿಕೆಗಳು">Activities</a></li>
                        <li><a href="#contact" data-kn="ಸಂಪರ್ಕ">Contact</a></li>
                        <li><a href="{{ route('campaigns.index') }}" data-kn="ಅಭಿಯಾನಗಳು">Campaigns</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4 data-kn="ಸಂಪರ್ಕ">Contact</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> {{ $ngo->address }}</li>
                        <li><i class="fas fa-phone"></i> {{ $ngo->phone }}</li>
                        <li><i class="fas fa-envelope"></i> {{ $ngo->email }}</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4 data-kn="ಅನುಸರಿಸಿ">Follow</h4>
                    <div class="footer-social">
                        @if($ngo->facebook_url)
                            <a href="{{ $ngo->facebook_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if($ngo->instagram_url)
                            <a href="{{ $ngo->instagram_url }}" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="footer-bottom fk-footer-bottom">
                <p class="fk-copy" data-kn="&copy; {{ date('Y') }} {{ $ngo->name }}. ಎಲ್ಲಾ ಹಕ್ಕುಗಳನ್ನು ಕಾಯ್ದಿರಿಸಲಾಗಿದೆ.">&copy; {{ date('Y') }} {{ $ngo->name }}. All rights reserved.</p>
                <div class="fk-powered">
                    <span class="fk-powered__label" data-kn="ಚಾಲಿತ">Powered by</span>
                    <span class="fk-powered__badge">
                        <i class="fas fa-cube"></i> NGO&nbsp;OS
                    </span>
                    <span class="fk-powered__dot">·</span>
                    <span class="fk-powered__by">Crafted by <strong>Vikas&nbsp;Guru</strong></span>
                </div>
            </div>
        </div>
    </footer>

    @if($ngo->tawk_property_id && $ngo->tawk_widget_id)
        <script>
            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = "https://embed.tawk.to/{{ $ngo->tawk_property_id }}/{{ $ngo->tawk_widget_id }}";
                s1.charset = "UTF-8";
                s1.setAttribute("crossorigin", "*");
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    @endif

    <script>
        window.showNotification = window.showNotification || function (message) {
            if (window.console) console.log(message);
        };
    </script>
    <script src="{{ asset('assets/Themes/script.js') }}"></script>
    <script src="{{ asset('assets/Themes/scroll-animations.js') }}"></script>
    <script src="{{ asset('assets/Themes/karnataka-map.js') }}"></script>
    <script src="{{ asset('assets/Themes/i18n-kn.js') }}"></script>
</body>
</html>
