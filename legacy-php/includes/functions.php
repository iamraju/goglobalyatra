<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function app_url(string $path = ''): string
{
    return absolute_url($path);
}

function site_base_url(): string
{
    $config = $GLOBALS['config'] ?? [];
    $baseUrl = trim((string) ($config['site']['base_url'] ?? ''));

    if ($baseUrl !== '') {
        return rtrim($baseUrl, '/');
    }

    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
    if ($host === '') {
        return '';
    }

    $https = (string) ($_SERVER['HTTPS'] ?? '');
    $forwardedProto = (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
    $isSecure = $https === 'on' || $https === '1' || strtolower($forwardedProto) === 'https';
    $scheme = $isSecure ? 'https' : 'http';

    return $scheme . '://' . $host;
}

function absolute_url(string $path = ''): string
{
    if ($path === '') {
        return site_base_url() !== '' ? site_base_url() . '/' : '/';
    }

    if (preg_match('/^https?:\/\//i', $path) === 1) {
        return $path;
    }

    $normalizedPath = '/' . ltrim($path, '/');
    $baseUrl = site_base_url();

    if ($baseUrl === '') {
        return $normalizedPath;
    }

    return $baseUrl . $normalizedPath;
}

function nav_items(): array
{
    return [
        'home' => ['label' => 'Home', 'url' => '/'],
        'about' => ['label' => 'About Us', 'url' => 'about.php'],
        'outbound' => ['label' => 'Outbound Packages', 'url' => 'outbound-packages.php'],
        'inbound' => ['label' => 'Inbound Packages', 'url' => 'inbound-packages.php'],
        'contact' => ['label' => 'Contact Us', 'url' => 'contact.php'],
    ];
}

function active_banners(array $config): array
{
    return array_values(array_filter(
        $config['banners'],
        static fn(array $banner): bool => (int) ($banner['status'] ?? 0) === 1
    ));
}

function active_packages(array $config, ?string $type = null, ?bool $popularOnly = null): array
{
    $packages = array_filter($config['packages'], static function (array $package) use ($type, $popularOnly): bool {
        if ((int) ($package['status'] ?? 0) !== 1) {
            return false;
        }

        if ($type !== null && ($package['type'] ?? '') !== $type) {
            return false;
        }

        if ($popularOnly !== null && (bool) ($package['is_popular'] ?? false) !== $popularOnly) {
            return false;
        }

        return true;
    });

    return array_values($packages);
}

function package_by_slug(array $config, string $slug): ?array
{
    foreach ($config['packages'] as $package) {
        if (($package['slug'] ?? '') === $slug && (int) ($package['status'] ?? 0) === 1) {
            return $package;
        }
    }

    return null;
}

function package_details_url(string $slug): string
{
    return 'package-details.php?package=' . rawurlencode($slug);
}

function package_detail_content(array $package): array
{
    $slug = (string) ($package['slug'] ?? '');
    $type = (string) ($package['type'] ?? 'outbound');

    $contentMap = [
        'europe-tour' => [
            'paragraphs' => [
                'This Europe itinerary is designed for travelers who want to experience iconic cities without feeling rushed. From riverside evenings and historic old towns to museum districts and charming street cafes, each stop is selected to balance sightseeing with time to actually enjoy the place.',
                'Our team helps you manage visas, flight timing, hotel coordination, and practical day-wise planning so your journey runs smoothly from departure in Nepal to your return. It is a strong choice for first-time Europe visitors and families who want a reliable, well-paced multi-country plan.',
            ],
        ],
        'thailand-tour' => [
            'paragraphs' => [
                'Thailand is perfect for travelers who want variety in a short holiday. This plan combines vibrant city life, local markets, beach relaxation, and optional nightlife so you can shape the trip around your comfort level.',
                'Whether you are traveling as a couple, with friends, or with family, the package keeps transfers simple and practical. We guide you on the best season, activity options, and local etiquette so you enjoy more and worry less throughout the journey.',
            ],
        ],
        'bali-tour' => [
            'paragraphs' => [
                'Our Bali package focuses on scenic experiences, not just checklists. You will explore temple landscapes, rice terraces, and coastal viewpoints while still having flexible time for spa, cafe, or resort leisure based on your travel style.',
                'This trip works especially well for honeymooners and slow travelers who want a peaceful rhythm. From airport pickup to stay planning and day tours, every element is arranged to keep the island experience comfortable, culturally rich, and photogenic.',
            ],
        ],
        'malaysia-tour' => [
            'paragraphs' => [
                'Malaysia offers an excellent mix of modern city energy and multicultural heritage. This package includes key urban highlights, shopping avenues, and leisure windows so travelers can enjoy both planned activities and personal exploration.',
                'We keep the route practical for Nepali travelers by optimizing flight timings, hotel location, and daily movement time. The result is a clean, value-driven itinerary suitable for families and first-time Southeast Asia visitors.',
            ],
        ],
        'dubai-tour' => [
            'paragraphs' => [
                'Dubai is a destination where luxury, architecture, and entertainment come together in a very accessible way. This itinerary blends city icons with optional desert experiences, making it ideal for travelers who want both comfort and excitement.',
                'Our planning support covers visa guidance, stay recommendations, transport logic, and activity sequencing so you can maximize your days without unnecessary travel fatigue. It is a smart short-haul holiday with premium feel.',
            ],
        ],
        'china-tour' => [
            'paragraphs' => [
                'This China tour combines historical depth with modern city life. You can experience major landmarks, local culinary culture, and contemporary urban districts in one coherent journey that highlights the contrast China is known for.',
                'Because travel logistics in China can be complex for first-time visitors, we provide route clarity, hotel coordination, and planning support before departure. The package is crafted for travelers who want cultural value with dependable structure.',
            ],
        ],
        'singapore-tour' => [
            'paragraphs' => [
                'Singapore is ideal for clean, efficient, and family-friendly travel. This package includes curated city highlights, modern attractions, and flexible free slots so both adults and children can enjoy the trip at their own pace.',
                'We design the itinerary around convenience, minimizing transit time and maximizing useful experiences. It is a strong option for short holidays, first international trips, and travelers who prefer organized, low-stress city breaks.',
            ],
        ],
        'vietnam-tour' => [
            'paragraphs' => [
                'Vietnam offers a beautiful blend of history, food culture, and diverse landscapes. This package introduces travelers to atmospheric old quarters, local flavor trails, and scenic day experiences with a good balance between guided and free time.',
                'From trip planning to destination flow, we focus on keeping your movement smooth and your experience authentic. It is a great fit for curious travelers who want culture-rich travel without sacrificing comfort and convenience.',
            ],
        ],
        'sri-lanka-tour' => [
            'paragraphs' => [
                'Sri Lanka is compact yet surprisingly diverse, making it ideal for medium-length holidays. This itinerary connects heritage sites, hill-country scenery, and relaxing coastal moments so the trip feels complete without being hectic.',
                'We help personalize the pace based on your interests, whether you prefer culture, nature, or a mix of both. The package is crafted for travelers who want scenic variety, warm hospitality, and simple logistics from start to finish.',
            ],
        ],
        'maldives-tour' => [
            'paragraphs' => [
                'The Maldives package is crafted for pure relaxation with premium island ambiance. It is especially suitable for honeymooners, couples, and travelers who want quiet ocean views, clean beaches, and stress-free resort time.',
                'We support you with resort category selection, transfer planning, and practical budgeting guidance so your holiday remains smooth and transparent. This plan is about comfort, privacy, and memorable island downtime.',
            ],
        ],
        'kathmandu-pokhara-highlights' => [
            'paragraphs' => [
                'This inbound Nepal route is designed for visitors who want both heritage and mountain scenery in one easy itinerary. You get a strong introduction to Nepal through temple squares, cultural neighborhoods, and tranquil lakeside experiences.',
                'It works well for first-time international guests because the pace is friendly and the transitions are simple. We coordinate accommodations, transport, and local guidance to ensure guests can focus on discovery rather than logistics.',
            ],
        ],
        'nepal-culture-heritage-tour' => [
            'paragraphs' => [
                'Our culture and heritage package highlights Nepal beyond postcard views. Travelers explore living traditions, historical architecture, spiritual landmarks, and local stories that reveal the deeper character of the country.',
                'The itinerary is arranged to provide context at each stop while keeping the travel flow comfortable. It is a meaningful option for guests who value history, authenticity, and immersive local experiences.',
            ],
        ],
        'scenic-nepal-retreat' => [
            'paragraphs' => [
                'Scenic Nepal Retreat is built for travelers who want calm, natural beauty, and restorative pacing. Instead of rushing between locations, this plan creates space to appreciate mountain vistas, local hospitality, and peaceful downtime.',
                'It is especially suitable for mature travelers, couples, and guests seeking a soft-adventure holiday. Our team handles the practical details so visitors can enjoy Nepal in a relaxed, high-comfort way.',
            ],
        ],
    ];

    $defaultParagraphs = [
        (string) ($package['short_description'] ?? ''),
        'Our team will help you with planning, travel coordination, and booking support to make this package smooth and memorable from start to finish.',
    ];

    $defaultHighlights = [
        'Well-planned day-wise itinerary with practical pacing',
        'Balanced mix of guided activities and free personal time',
        'Dedicated support before departure and during travel',
    ];

    $defaultInclusions = [
        'Hotel accommodation as per itinerary category',
        'Airport transfers and intercity transportation support',
        'Selected sightseeing and coordination assistance',
    ];

    $defaultExclusions = [
        'International airfare unless explicitly stated',
        'Personal expenses, tips, and optional activities',
        'Travel insurance and costs outside itinerary scope',
    ];

    $highlightsBySlug = [
        'europe-tour' => ['Historic city centers and landmark visits', 'Scenic riverfront and old-town exploration', 'Multi-city route optimized for first-time Europe travel'],
        'thailand-tour' => ['City plus beach combination itinerary', 'Family-friendly and couple-friendly activity options', 'Flexible nightlife and leisure windows'],
        'bali-tour' => ['Temple and rice terrace sightseeing', 'Island leisure with curated day tours', 'Strong honeymoon and couple travel appeal'],
        'malaysia-tour' => ['Urban highlights with shopping districts', 'Multicultural culinary and city experiences', 'Comfortable pacing for short holidays'],
        'dubai-tour' => ['Modern skyline landmarks and premium districts', 'Optional desert and entertainment add-ons', 'Convenient short-haul outbound experience'],
        'china-tour' => ['Historic and modern city contrast experiences', 'Cultural landmarks with practical route planning', 'Ideal for travelers seeking high-value discovery'],
        'singapore-tour' => ['Clean, efficient city-break structure', 'Family-friendly and couple-friendly attractions', 'Minimal transit stress with organized scheduling'],
        'vietnam-tour' => ['Culture-rich old quarter and city experiences', 'Food and history-focused travel flow', 'Balanced free time and guided exploration'],
        'sri-lanka-tour' => ['Heritage sites and scenic countryside mix', 'Flexible pace for culture and relaxation', 'Compact route with diverse destination feel'],
        'maldives-tour' => ['Island relaxation with premium ambiance', 'Excellent option for honeymoon and anniversaries', 'Resort-focused downtime and ocean activities'],
        'kathmandu-pokhara-highlights' => ['UNESCO heritage and lakeside highlights', 'Great first-time Nepal orientation route', 'Easy pace with curated city-to-city flow'],
        'nepal-culture-heritage-tour' => ['Deep focus on local history and traditions', 'Temple architecture and cultural neighborhoods', 'Immersive storytelling and heritage emphasis'],
        'scenic-nepal-retreat' => ['Relaxed mountain-view and nature rhythm', 'Soft-adventure pacing for comfort travelers', 'Well-suited for longer restorative holidays'],
    ];

    $inclusionsByType = [
        'outbound' => [
            'Visa guidance and document checklist support',
            'Hotel stay with itinerary-level room category',
            'Airport transfer and selected local transport arrangements',
        ],
        'inbound' => [
            'Airport meet-and-greet and local transfer support',
            'Hotel accommodation with local tour coordination',
            'City sightseeing and activity planning assistance',
        ],
    ];

    $exclusionsByType = [
        'outbound' => [
            'Visa fee and embassy charges unless mentioned',
            'Lunch, dinner, and optional paid experiences',
            'Personal shopping, tips, and unforeseen expenses',
        ],
        'inbound' => [
            'International airfare to and from Nepal',
            'Personal meals not listed in itinerary',
            'Personal insurance and emergency evacuation costs',
        ],
    ];

    $selected = $contentMap[$slug] ?? [];

    return [
        'paragraphs' => $selected['paragraphs'] ?? $defaultParagraphs,
        'highlights' => $highlightsBySlug[$slug] ?? $defaultHighlights,
        'inclusions' => $inclusionsByType[$type] ?? $defaultInclusions,
        'exclusions' => $exclusionsByType[$type] ?? $defaultExclusions,
    ];
}

function related_packages(array $config, array $package, int $limit = 3): array
{
    $currentSlug = (string) ($package['slug'] ?? '');
    $type = (string) ($package['type'] ?? '');

    $related = array_values(array_filter(
        active_packages($config, $type),
        static fn(array $item): bool => (string) ($item['slug'] ?? '') !== $currentSlug
    ));

    if (count($related) < $limit) {
        $fallback = array_values(array_filter(
            active_packages($config),
            static fn(array $item): bool => (string) ($item['slug'] ?? '') !== $currentSlug
        ));
        $related = array_merge($related, $fallback);
    }

    $unique = [];
    foreach ($related as $item) {
        $slug = (string) ($item['slug'] ?? '');
        if ($slug === '' || isset($unique[$slug])) {
            continue;
        }
        $unique[$slug] = $item;
        if (count($unique) >= $limit) {
            break;
        }
    }

    return array_values($unique);
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_input(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool
{
    if (!is_string($token) || !isset($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function flash_get(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function old_set(array $data): void
{
    $_SESSION['old'] = $data;
}

function old_clear(): void
{
    unset($_SESSION['old']);
}

function old_value(string $key, string $default = ''): string
{
    return (string) ($_SESSION['old'][$key] ?? $default);
}

function redirect_to(string $url): void
{
    header('Location: ' . $url);
    exit;
}
