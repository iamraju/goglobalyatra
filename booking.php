<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'package_slug' => trim((string) ($_POST['package_slug'] ?? '')),
        'travel_date' => trim((string) ($_POST['travel_date'] ?? '')),
        'persons' => trim((string) ($_POST['persons'] ?? '')),
        'full_name' => trim((string) ($_POST['full_name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'phone' => trim((string) ($_POST['phone'] ?? '')),
        'address' => trim((string) ($_POST['address'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];

    old_set($formData);

    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash_set('error', 'Invalid request token. Please try submitting the form again.');
        redirect_to('booking.php');
    }

    if ($formData['package_slug'] === '' || $formData['travel_date'] === '' || $formData['persons'] === '' || $formData['full_name'] === '' || $formData['email'] === '' || $formData['phone'] === '' || $formData['address'] === '') {
        flash_set('error', 'Please complete all required fields.');
        redirect_to('booking.php');
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        flash_set('error', 'Please enter a valid email address.');
        redirect_to('booking.php');
    }

    $selectedPackage = package_by_slug($config, $formData['package_slug']);
    if ($selectedPackage === null) {
        flash_set('error', 'Selected package is invalid. Please choose a valid package.');
        redirect_to('booking.php');
    }

    $body = '<h2>New Booking Request</h2>'
        . '<p><strong>Package:</strong> ' . e($selectedPackage['title']) . '</p>'
        . '<p><strong>Travel Date:</strong> ' . e($formData['travel_date']) . '</p>'
        . '<p><strong>Persons:</strong> ' . e($formData['persons']) . '</p>'
        . '<p><strong>Full Name:</strong> ' . e($formData['full_name']) . '</p>'
        . '<p><strong>Email:</strong> ' . e($formData['email']) . '</p>'
        . '<p><strong>Phone:</strong> ' . e($formData['phone']) . '</p>'
        . '<p><strong>Address:</strong> ' . e($formData['address']) . '</p>'
        . '<p><strong>Message:</strong><br>' . nl2br(e($formData['message'])) . '</p>';

    [$sent, $errorMessage] = send_site_mail(
        $config,
        'Booking Request - ' . $selectedPackage['title'],
        $body,
        $formData['email']
    );

    if (!$sent) {
        flash_set('error', 'Booking request could not be sent. ' . (string) $errorMessage);
        redirect_to('booking.php');
    }

    old_clear();
    flash_set('success', 'Your booking request has been submitted successfully. We will contact you soon.');
    redirect_to('booking.php');
}

$pageTitle = 'Book Package | ' . $config['site']['name'];
$metaDescription = 'Book your inbound or outbound travel package with Goglobal Yatra using our secure booking request form.';
$pagePath = '/booking.php';
$metaRobots = 'noindex, follow';
$ogImage = 'assets/images/goglobal-yatra-logo.png';
$currentPage = '';
$heroTitle = 'Booking Form';
$heroSubtitle = 'Send your travel booking request and our team will contact you quickly.';
$packageSlug = trim((string) ($_GET['package'] ?? old_value('package_slug')));
$preselectedPackage = package_by_slug($config, $packageSlug);
$allPackages = active_packages($config);

include __DIR__ . '/includes/partials/head.php';
include __DIR__ . '/includes/partials/topbar.php';
include __DIR__ . '/includes/partials/header.php';
include __DIR__ . '/includes/partials/page-hero.php';
include __DIR__ . '/includes/partials/flash.php';
?>

<main>
  <section class="max-w-4xl mx-auto px-4 py-14">
    <form class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 md:p-8 grid md:grid-cols-2 gap-5" method="post" action="booking.php">
      <?= csrf_input() ?>

      <div class="md:col-span-2">
        <label for="package-slug" class="block mb-2 text-sm font-medium">Package Name</label>
        <select data-package-name id="package-slug" name="package_slug" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
          <option value="">Select package</option>
          <?php foreach ($allPackages as $package):
              $selected = ($preselectedPackage && $preselectedPackage['slug'] === $package['slug']) || old_value('package_slug') === $package['slug']; ?>
            <option value="<?= e($package['slug']) ?>" <?= $selected ? 'selected' : '' ?>><?= e($package['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="travel-date" class="block mb-2 text-sm font-medium">Date</label>
        <input id="travel-date" type="date" name="travel_date" value="<?= e(old_value('travel_date')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
      </div>

      <div>
        <label for="persons" class="block mb-2 text-sm font-medium">Number of Packs / Persons</label>
        <select id="persons" name="persons" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
          <option value="">Select persons</option>
          <?php foreach ($config['search_filters']['persons'] as $person): ?>
            <option value="<?= e($person) ?>" <?= old_value('persons') === $person ? 'selected' : '' ?>><?= e($person) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="full-name" class="block mb-2 text-sm font-medium">Full Name</label>
        <input id="full-name" type="text" name="full_name" value="<?= e(old_value('full_name')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
      </div>

      <div>
        <label for="email" class="block mb-2 text-sm font-medium">Email</label>
        <input id="email" type="email" name="email" value="<?= e(old_value('email')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
      </div>

      <div>
        <label for="phone" class="block mb-2 text-sm font-medium">Phone</label>
        <input id="phone" type="tel" name="phone" value="<?= e(old_value('phone')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
      </div>

      <div>
        <label for="address" class="block mb-2 text-sm font-medium">Full Address</label>
        <input id="address" type="text" name="address" value="<?= e(old_value('address')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
      </div>

      <div class="md:col-span-2">
        <label for="message" class="block mb-2 text-sm font-medium">Message</label>
        <textarea id="message" name="message" rows="5" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="Write any additional requirement..."><?= e(old_value('message')) ?></textarea>
      </div>

      <div class="md:col-span-2">
        <button type="submit" class="rounded-lg brand-gradient text-white px-6 py-3 font-semibold">Submit Booking Request</button>
      </div>
    </form>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
