<?php
require __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'full_name' => trim((string) ($_POST['full_name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'phone' => trim((string) ($_POST['phone'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];

    old_set($formData);

    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash_set('error', 'Invalid request token. Please try again.');
        redirect_to('contact.php');
    }

    if ($formData['full_name'] === '' || $formData['email'] === '' || $formData['phone'] === '' || $formData['message'] === '') {
        flash_set('error', 'Please complete all required fields.');
        redirect_to('contact.php');
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        flash_set('error', 'Please enter a valid email address.');
        redirect_to('contact.php');
    }

    $body = '<h2>New Contact Message</h2>'
        . '<p><strong>Full Name:</strong> ' . e($formData['full_name']) . '</p>'
        . '<p><strong>Email:</strong> ' . e($formData['email']) . '</p>'
        . '<p><strong>Phone:</strong> ' . e($formData['phone']) . '</p>'
        . '<p><strong>Message:</strong><br>' . nl2br(e($formData['message'])) . '</p>';

    [$sent, $errorMessage] = send_site_mail(
        $config,
        'Contact Message - ' . $config['site']['name'],
        $body,
        $formData['email']
    );

    if (!$sent) {
        flash_set('error', 'Your message could not be sent. ' . (string) $errorMessage);
        redirect_to('contact.php');
    }

    old_clear();
    flash_set('success', 'Your message has been sent successfully. We will contact you soon.');
    redirect_to('contact.php');
}

$pageTitle = 'Contact Us | ' . $config['site']['name'];
$metaDescription = 'Contact Goglobal Yatra for inbound Nepal and outbound international travel packages and booking support.';
$pagePath = '/contact.php';
$ogImage = 'assets/images/goglobal-yatra-logo.png';
$currentPage = 'contact';
$heroTitle = 'Contact Us';
$heroSubtitle = 'Reach out to plan your next inbound or outbound journey.';

include __DIR__ . '/includes/partials/head.php';
include __DIR__ . '/includes/partials/topbar.php';
include __DIR__ . '/includes/partials/header.php';
include __DIR__ . '/includes/partials/page-hero.php';
include __DIR__ . '/includes/partials/flash.php';
?>

<main>
  <section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-2 gap-8">
    <article class="bg-white rounded-2xl shadow-xl border border-slate-100 p-7">
      <h2 class="text-2xl font-bold text-slate-900">Contact Details</h2>
      <ul class="mt-5 space-y-3 text-slate-700 leading-7">
        <li><strong>Company Name:</strong> <?= e($config['site']['name']) ?></li>
        <li><strong>Address:</strong> <?= e($config['site']['address']) ?></li>
        <li><strong>Email:</strong> <?= e($config['site']['topbar_email']) ?></li>
        <li><strong>Phone:</strong> <?= e($config['site']['topbar_phone']) ?></li>
      </ul>
    </article>

    <article class="bg-white rounded-2xl shadow-xl border border-slate-100 p-7">
      <h2 class="text-2xl font-bold text-slate-900">Send a Message</h2>
      <form class="mt-5 grid gap-4" action="contact.php" method="post">
        <?= csrf_input() ?>
        <div>
          <label for="name" class="block mb-2 text-sm font-medium">Full Name</label>
          <input id="name" type="text" name="full_name" value="<?= e(old_value('full_name')) ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
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
          <label for="message" class="block mb-2 text-sm font-medium">Message</label>
          <textarea id="message" name="message" rows="5" class="w-full rounded-lg border border-slate-300 px-3 py-2" required><?= e(old_value('message')) ?></textarea>
        </div>
        <button type="submit" class="rounded-lg brand-gradient text-white px-6 py-3 font-semibold">Send Message</button>
      </form>
    </article>
  </section>
</main>

<?php include __DIR__ . '/includes/partials/footer.php'; ?>
