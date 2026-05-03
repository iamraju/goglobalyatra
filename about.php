<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'About Us | ' . $config['site']['name'];
$metaDescription = 'Learn about Goglobal Yatra, a Nepal-based travel company offering inbound and outbound holiday packages.';
$currentPage = 'about';
$heroTitle = 'About Us';
$heroSubtitle = 'Your trusted travel partner connecting Nepal to the world and the world to Nepal.';

include __DIR__ . '/includes/partials/head.php';
include __DIR__ . '/includes/partials/topbar.php';
include __DIR__ . '/includes/partials/header.php';
include __DIR__ . '/includes/partials/page-hero.php';
?>

<main>
  <section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-12 gap-8 items-start">
    <article class="lg:col-span-8 bg-white rounded-2xl shadow-lg p-7 border border-slate-100 leading-8">
      <h2 class="text-2xl font-bold text-slate-900">Who We Are</h2>
      <p class="mt-4 text-slate-700"><?= e($config['site']['name']) ?> is a Kathmandu-based travel company specializing in both inbound and outbound travel experiences. We design carefully planned itineraries for international guests visiting Nepal and for Nepali travelers exploring global destinations.</p>
      <p class="mt-4 text-slate-700">Our mission is to make every journey seamless and memorable. From airport pickup, hotel reservations, and guided tours to visa support and flight assistance, we provide complete guidance at every step.</p>

      <h3 class="text-xl font-semibold mt-8 text-slate-900">Why Travel with <?= e($config['site']['name']) ?></h3>
      <ul class="mt-4 list-disc list-inside space-y-2 text-slate-700">
        <li>Expert local and international destination knowledge</li>
        <li>Transparent pricing and personalized service</li>
        <li>Flexible packages for families, groups, and corporate travelers</li>
        <li>Responsive support before, during, and after your trip</li>
      </ul>
    </article>

    <aside class="lg:col-span-4">
      <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-100">
        <img src="assets/images/about-nepal.jpg" alt="View of Nepal for inbound tourism" class="w-full h-full object-cover" />
      </div>
    </aside>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
