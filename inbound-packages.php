<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Inbound Packages | ' . $config['site']['name'];
$metaDescription = 'Inbound Nepal travel packages for international tourists including culture, nature, and adventure experiences.';
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
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($packages as $package): ?>
        <article class="package-card bg-white rounded-xl shadow-lg overflow-hidden border border-slate-100">
          <div class="h-52 overflow-hidden"><img src="<?= e($package['image_url']) ?>" alt="<?= e($package['title']) ?> package" class="w-full h-full object-cover" /></div>
          <div class="p-5">
            <h2 class="text-xl font-bold"><?= e($package['title']) ?></h2>
            <p class="text-slate-600 mt-1"><?= e($package['duration']) ?></p>
            <p class="text-slate-600 mt-2 text-sm"><?= e($package['short_description']) ?></p>
            <a href="booking.php?package=<?= e($package['slug']) ?>" class="inline-flex mt-4 px-4 py-2 rounded-lg brand-gradient text-white font-medium">Book Now</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
