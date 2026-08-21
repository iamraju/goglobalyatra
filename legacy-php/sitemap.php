<?php
require __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/xml; charset=UTF-8');

$staticUrls = [
    ['loc' => absolute_url('/'), 'changefreq' => 'weekly', 'priority' => '1.0'],
    ['loc' => absolute_url('/about.php'), 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => absolute_url('/outbound-packages.php'), 'changefreq' => 'weekly', 'priority' => '0.9'],
    ['loc' => absolute_url('/inbound-packages.php'), 'changefreq' => 'weekly', 'priority' => '0.9'],
    ['loc' => absolute_url('/contact.php'), 'changefreq' => 'monthly', 'priority' => '0.7'],
];

$packageUrls = [];
foreach (active_packages($config) as $package) {
    $slug = (string) ($package['slug'] ?? '');
    if ($slug === '') {
        continue;
    }

    $packageUrls[] = [
        'loc' => absolute_url('/' . package_details_url($slug)),
        'changefreq' => 'weekly',
        'priority' => '0.8',
    ];
}

$allUrls = array_merge($staticUrls, $packageUrls);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($allUrls as $url) {
    echo "  <url>\n";
    echo '    <loc>' . e((string) $url['loc']) . "</loc>\n";
    echo '    <changefreq>' . e((string) $url['changefreq']) . "</changefreq>\n";
    echo '    <priority>' . e((string) $url['priority']) . "</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
