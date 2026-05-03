<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle = $config['site']['name'] . ' | ' . $config['site']['tagline'];
$metaDescription = 'Goglobal Yatra offers outbound tours from Nepal and inbound Nepal travel packages for international tourists.';
$pagePath = '/';
$ogImage = 'assets/images/europe.jpg';
$currentPage = 'home';
$banners = active_banners($config);
$popularPackages = active_packages($config, null, true);
$destinationOptions = $config['search_filters']['destinations'];

include __DIR__ . '/includes/partials/head.php';
include __DIR__ . '/includes/partials/topbar.php';
include __DIR__ . '/includes/partials/header.php';
?>

<main id="main-content">
  <section class="relative overflow-hidden">
    <h1 class="sr-only"><?= e($config['site']['name']) ?> Travel Packages</h1>

    <?php foreach ($banners as $index => $banner): ?>
      <article data-slide class="banner-slide <?= $index === 0 ? '' : 'hidden' ?>" style="background-image: url('<?= e($banner['image_url']) ?>');">
        <div class="banner-overlay min-h-[460px] flex items-center">
          <div class="max-w-7xl mx-auto px-4 text-white">
            <p class="text-blue-100 mb-3 font-medium">Outbound Highlight</p>
            <h2 class="text-4xl md:text-5xl font-extrabold max-w-xl leading-tight"><?= e($banner['title']) ?></h2>
            <a href="<?= e($banner['button_url']) ?>" class="inline-flex mt-6 bg-white text-blue-900 font-semibold px-6 py-3 rounded-full"><?= e($banner['button_text']) ?></a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-wrap justify-center gap-2 px-2">
      <?php foreach ($banners as $index => $banner): ?>
        <button data-slide-dot class="w-3 h-3 rounded-full <?= $index === 0 ? 'bg-white' : 'bg-white/45' ?>" aria-label="Go to slide <?= $index + 1 ?>"></button>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 -mt-14 relative z-20">
    <div class="glass-card rounded-2xl shadow-xl p-6 md:p-8 border border-white/40">
      <h2 class="text-2xl font-bold text-slate-900">Find Your Perfect Package</h2>
      <p class="text-slate-600 mt-1">Search outbound or inbound packages with your preferred dates and duration.</p>

      <form data-home-search-form class="mt-6 grid md:grid-cols-2 lg:grid-cols-3 gap-4" action="outbound-packages.php" method="get">
        <div>
          <label class="block mb-2 text-sm font-medium" for="persons">Packs / Number of Persons</label>
          <select id="persons" name="persons" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
            <option value="">Select persons</option>
            <?php foreach ($config['search_filters']['persons'] as $person): ?>
              <option value="<?= e($person) ?>"><?= e($person) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="destination">Destination</label>
          <select id="destination" name="destination" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
            <option value="">Choose destination</option>
            <?php foreach ($destinationOptions as $destination): ?>
              <option value="<?= e($destination) ?>"><?= e($destination) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="duration">Duration</label>
          <select id="duration" name="duration" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
            <option value="">Select duration</option>
            <?php foreach ($config['search_filters']['durations'] as $duration): ?>
              <option value="<?= e($duration) ?>"><?= e($duration) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="start-date">Start Date</label>
          <input data-start-date id="start-date" type="date" name="start_date" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="end-date">End Date</label>
          <input data-end-date id="end-date" type="date" name="end_date" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
        </div>

        <div class="flex items-end">
          <button type="submit" class="w-full rounded-lg brand-gradient text-white py-2.5 font-semibold">Search Packages</button>
        </div>
      </form>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-end justify-between gap-3 flex-wrap">
      <div>
        <h2 class="text-3xl font-extrabold text-slate-900">Popular Packages</h2>
        <p class="text-slate-600 mt-1">Most-loved tours chosen by Nepali and international travelers.</p>
      </div>
      <a href="outbound-packages.php" class="text-blue-800 font-semibold">See all outbound tours</a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
      <?php foreach ($popularPackages as $package): ?>
        <article class="package-card bg-white rounded-xl shadow-lg overflow-hidden border border-slate-100">
          <div class="h-48 overflow-hidden"><img src="<?= e($package['image_url']) ?>" alt="<?= e($package['title']) ?>" class="w-full h-full object-cover" /></div>
          <div class="p-5">
            <h3 class="font-bold text-xl"><?= e($package['title']) ?></h3>
            <p class="text-slate-600 mt-1"><?= e($package['duration']) ?></p>
            <a href="booking.php?package=<?= e($package['slug']) ?>" class="inline-flex mt-4 px-4 py-2 rounded-lg brand-gradient text-white font-medium">Book Now</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
