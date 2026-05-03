<?php
$config = $config ?? ($GLOBALS['config'] ?? []);
$pageTitle = $pageTitle ?? $config['site']['name'];
$metaDescription = $metaDescription ?? $config['site']['tagline'];
$siteName = (string) ($config['site']['name'] ?? 'Website');
$pagePath = $pagePath ?? '/';
$canonicalUrl = absolute_url($pagePath);
$metaRobots = $metaRobots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
$ogType = $ogType ?? 'website';
$ogTitle = $ogTitle ?? $pageTitle;
$ogDescription = $ogDescription ?? $metaDescription;
$ogImage = $ogImage ?? ($config['site']['logo'] ?? '');
$ogImageUrl = absolute_url((string) $ogImage);
$twitterCard = $twitterCard ?? 'summary_large_image';

$organizationData = [
    '@context' => 'https://schema.org',
    '@type' => 'TravelAgency',
    'name' => $siteName,
    'url' => absolute_url('/'),
    'logo' => absolute_url((string) ($config['site']['logo'] ?? '')),
    'telephone' => (string) ($config['site']['topbar_phone'] ?? ''),
    'email' => (string) ($config['site']['topbar_email'] ?? ''),
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => (string) ($config['site']['address'] ?? ''),
        'addressCountry' => 'NP',
    ],
];

$webSiteData = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $siteName,
    'url' => absolute_url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => absolute_url('outbound-packages.php') . '?destination={destination}',
        'query-input' => 'required name=destination',
    ],
];

$webPageData = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $pageTitle,
    'description' => $metaDescription,
    'url' => $canonicalUrl,
];

$structuredData = $structuredData ?? [$organizationData, $webSiteData, $webPageData];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>" />
  <meta name="robots" content="<?= e($metaRobots) ?>" />
  <link rel="canonical" href="<?= e($canonicalUrl) ?>" />

  <meta property="og:locale" content="en_US" />
  <meta property="og:site_name" content="<?= e($siteName) ?>" />
  <meta property="og:type" content="<?= e($ogType) ?>" />
  <meta property="og:title" content="<?= e($ogTitle) ?>" />
  <meta property="og:description" content="<?= e($ogDescription) ?>" />
  <meta property="og:url" content="<?= e($canonicalUrl) ?>" />
  <meta property="og:image" content="<?= e($ogImageUrl) ?>" />

  <meta name="twitter:card" content="<?= e($twitterCard) ?>" />
  <meta name="twitter:title" content="<?= e($ogTitle) ?>" />
  <meta name="twitter:description" content="<?= e($ogDescription) ?>" />
  <meta name="twitter:image" content="<?= e($ogImageUrl) ?>" />

  <?php foreach ($structuredData as $schema): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
  <?php endforeach; ?>

  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="256x256" href="assets/images/favicon-256.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
