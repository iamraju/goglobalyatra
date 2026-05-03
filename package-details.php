<?php
require __DIR__ . '/includes/bootstrap.php';

$packageSlug = trim((string) ($_GET['package'] ?? ''));
$package = package_by_slug($config, $packageSlug);

if ($package === null) {
    http_response_code(404);
    $pageTitle = 'Package Not Found | ' . $config['site']['name'];
    $metaDescription = 'The requested package could not be found.';
    $pagePath = '/package-details.php';
    $metaRobots = 'noindex, follow';
    $currentPage = '';
    $heroTitle = 'Package Not Found';
    $heroSubtitle = 'The requested package may have been removed or renamed.';

    include __DIR__ . '/includes/partials/head.php';
    include __DIR__ . '/includes/partials/topbar.php';
    include __DIR__ . '/includes/partials/header.php';
    include __DIR__ . '/includes/partials/page-hero.php';
    ?>
    <main>
      <section class="max-w-4xl mx-auto px-4 py-14">
        <article class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 text-center">
          <p class="text-slate-700">Please return to package listing and choose an available package.</p>
          <a href="outbound-packages.php" class="inline-flex mt-5 px-5 py-2.5 rounded-lg brand-gradient text-white font-medium">Browse Packages</a>
        </article>
      </section>
    </main>
    <?php
    include __DIR__ . '/includes/partials/footer.php';
    return;
}

$detailContent = package_detail_content($package);
$paragraphs = $detailContent['paragraphs'];
$highlights = $detailContent['highlights'];
$inclusions = $detailContent['inclusions'];
$exclusions = $detailContent['exclusions'];
$relatedPackages = related_packages($config, $package, 3);

$pageTitle = (string) $package['title'] . ' | Package Details | ' . $config['site']['name'];
$metaDescription = (string) $package['short_description'];
$pagePath = '/package-details.php?package=' . rawurlencode((string) $package['slug']);
$ogImage = (string) $package['image_url'];
$currentPage = (string) $package['type'] === 'inbound' ? 'inbound' : 'outbound';
$heroTitle = (string) $package['title'];
$heroSubtitle = (string) $package['short_description'];
$allPackages = active_packages($config);
$preselectedPackageSlug = (string) $package['slug'];

include __DIR__ . '/includes/partials/head.php';
include __DIR__ . '/includes/partials/topbar.php';
include __DIR__ . '/includes/partials/header.php';
include __DIR__ . '/includes/partials/page-hero.php';
include __DIR__ . '/includes/partials/flash.php';
?>

<main>
  <section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-[7fr_3fr] gap-8 items-start">
    <article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
      <div class="h-72 md:h-96 overflow-hidden">
        <img src="<?= e((string) $package['image_url']) ?>" alt="<?= e((string) $package['title']) ?>" class="w-full h-full object-cover" />
      </div>

      <div class="p-7 md:p-8">
        <div class="flex flex-wrap items-center gap-2 mb-5">
          <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold"><?= e(ucfirst((string) $package['type'])) ?> Package</span>
          <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm font-medium"><?= e((string) $package['duration']) ?></span>
        </div>

        <h2 class="text-3xl font-extrabold text-slate-900"><?= e((string) $package['title']) ?></h2>
        <p class="text-slate-600 mt-3"><?= e((string) $package['short_description']) ?></p>

        <?php foreach ($paragraphs as $paragraph): ?>
          <p class="mt-5 text-slate-700 leading-8"><?= e((string) $paragraph) ?></p>
        <?php endforeach; ?>

        <section class="mt-10 grid md:grid-cols-3 gap-5">
          <article class="rounded-xl border border-slate-200 bg-slate-50 p-5">
            <h3 class="text-lg font-bold text-slate-900">Highlights</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-700">
              <?php foreach ($highlights as $item): ?>
                <li class="flex items-start gap-2"><span class="text-blue-700">•</span><span><?= e((string) $item) ?></span></li>
              <?php endforeach; ?>
            </ul>
          </article>

          <article class="rounded-xl border border-slate-200 bg-emerald-50 p-5">
            <h3 class="text-lg font-bold text-slate-900">Inclusions</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-700">
              <?php foreach ($inclusions as $item): ?>
                <li class="flex items-start gap-2"><span class="text-emerald-700">•</span><span><?= e((string) $item) ?></span></li>
              <?php endforeach; ?>
            </ul>
          </article>

          <article class="rounded-xl border border-slate-200 bg-rose-50 p-5">
            <h3 class="text-lg font-bold text-slate-900">Exclusions</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-700">
              <?php foreach ($exclusions as $item): ?>
                <li class="flex items-start gap-2"><span class="text-rose-700">•</span><span><?= e((string) $item) ?></span></li>
              <?php endforeach; ?>
            </ul>
          </article>
        </section>

        <?php if ($relatedPackages !== []): ?>
          <section class="mt-10 border-t border-slate-200 pt-8">
            <div class="flex items-end justify-between gap-3 flex-wrap">
              <h3 class="text-2xl font-bold text-slate-900">Related Packages</h3>
              <a href="<?= e((string) $package['type'] === 'inbound' ? 'inbound-packages.php' : 'outbound-packages.php') ?>" class="text-blue-800 font-semibold">See all packages</a>
            </div>

            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5 mt-5">
              <?php foreach ($relatedPackages as $related): ?>
                <article class="rounded-xl border border-slate-100 bg-white shadow-md overflow-hidden">
                  <a href="<?= e(package_details_url((string) $related['slug'])) ?>" class="block">
                    <div class="h-32 overflow-hidden">
                      <img src="<?= e((string) $related['image_url']) ?>" alt="<?= e((string) $related['title']) ?>" class="w-full h-full object-cover" />
                    </div>
                    <div class="p-4">
                      <h4 class="font-bold text-slate-900"><?= e((string) $related['title']) ?></h4>
                      <p class="text-sm text-slate-600 mt-1"><?= e((string) $related['duration']) ?></p>
                      <span class="inline-flex mt-3 px-3 py-1.5 rounded-lg brand-gradient text-white text-sm font-medium">View Details</span>
                    </div>
                  </a>
                </article>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>
      </div>
    </article>

    <aside class="lg:sticky lg:top-28">
      <?php
      $formClass = 'bg-white rounded-2xl shadow-xl border border-slate-100 p-4 grid gap-3';
      $formTitle = 'Book This Package';
      $formIntro = 'Share your preferred travel dates and our team will contact you quickly.';
      $submitLabel = 'Submit Booking';
      include __DIR__ . '/includes/partials/booking-form.php';
      ?>
    </aside>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
