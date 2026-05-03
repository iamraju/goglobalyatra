<?php
$config = $config ?? ($GLOBALS['config'] ?? []);
$allPackages = $allPackages ?? active_packages($config);
$preselectedPackageSlug = $preselectedPackageSlug ?? old_value('package_slug');
$formAction = $formAction ?? 'booking.php';
$formClass = $formClass ?? 'bg-white rounded-2xl shadow-xl border border-slate-100 p-5 md:p-6 grid gap-3';
$submitLabel = $submitLabel ?? 'Submit Booking Request';
$formTitle = $formTitle ?? null;
$formIntro = $formIntro ?? null;
?>
<form class="<?= e($formClass) ?>" method="post" action="<?= e($formAction) ?>">
  <?= csrf_input() ?>

  <?php if (is_string($formTitle) && $formTitle !== ''): ?>
    <div>
      <h2 class="text-2xl font-bold text-slate-900"><?= e($formTitle) ?></h2>
      <?php if (is_string($formIntro) && $formIntro !== ''): ?>
        <p class="text-slate-600 mt-1"><?= e($formIntro) ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div>
    <label for="package-slug" class="block mb-2 text-sm font-medium">Package Name</label>
    <select data-package-name id="package-slug" name="package_slug" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
      <option value="">Select package</option>
      <?php foreach ($allPackages as $package):
          $slug = (string) $package['slug'];
          $selected = $preselectedPackageSlug === $slug || old_value('package_slug') === $slug; ?>
        <option value="<?= e($slug) ?>" <?= $selected ? 'selected' : '' ?>><?= e((string) $package['title']) ?></option>
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

  <div>
    <label for="message" class="block mb-2 text-sm font-medium">Message</label>
    <textarea id="message" name="message" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="Write any additional requirement..."><?= e(old_value('message')) ?></textarea>
  </div>

  <div>
    <button type="submit" class="rounded-lg brand-gradient text-white px-6 py-3 font-semibold"><?= e($submitLabel) ?></button>
  </div>
</form>
