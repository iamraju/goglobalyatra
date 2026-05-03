<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Inbound Packages | ' . $config['site']['name'];
$metaDescription = 'Inbound Nepal travel packages for international tourists including culture, nature, and adventure experiences.';
$pagePath = '/inbound-packages.php';
$ogImage = 'assets/images/nepal-inbound.jpg';
$currentPage = 'inbound';
$heroTitle = 'Inbound Packages';
$heroSubtitle = 'Nepal experiences for international tourists with authentic local support.';
$packages = active_packages($config, 'inbound');

include __DIR__ . '/includes/partials/head.php';
include __DIR__ . '/includes/partials/topbar.php';
include __DIR__ . '/includes/partials/header.php';
include __DIR__ . '/includes/partials/page-hero.php';
?>

<main>
  <section class="max-w-7xl mx-auto px-4 py-14">
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
      <?php foreach ($packages as $package): ?>
        <article class="package-card bg-white rounded-xl shadow-lg overflow-hidden border border-slate-100">
          <a href="<?= e(package_details_url((string) $package['slug'])) ?>" class="block">
            <div class="h-52 overflow-hidden"><img src="<?= e($package['image_url']) ?>" alt="<?= e($package['title']) ?> package" class="w-full h-full object-cover" /></div>
            <div class="p-5">
              <h2 class="text-xl font-bold"><?= e($package['title']) ?></h2>
              <p class="text-slate-600 mt-1"><?= e($package['duration']) ?></p>
              <p class="text-slate-600 mt-2 text-sm"><?= e($package['short_description']) ?></p>
              <span class="inline-flex mt-4 px-4 py-2 rounded-lg brand-gradient text-white font-medium">View Details</span>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
