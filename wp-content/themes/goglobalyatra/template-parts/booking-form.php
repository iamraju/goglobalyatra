<?php
/**
 * Reusable booking form. Optional $args: preselect_id, title, intro, submit_label, form_class.
 */
$preselect_id = $args['preselect_id'] ?? (isset($_GET['package']) ? absint($_GET['package']) : 0);
$form_title = $args['title'] ?? null;
$form_intro = $args['intro'] ?? null;
$submit_label = $args['submit_label'] ?? 'Submit Booking Request';
$form_class = $args['form_class'] ?? 'bg-white rounded-2xl shadow-xl border border-slate-100 p-5 md:p-6 grid gap-3';

$packages = get_posts([
    'post_type' => 'travel',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);
?>
<form class="<?php echo esc_attr($form_class); ?>" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
  <input type="hidden" name="action" value="goglobalyatra_booking" />
  <?php wp_nonce_field('goglobalyatra_booking', 'goglobalyatra_booking_nonce'); ?>

  <?php if ($form_title) : ?>
    <div>
      <h2 class="text-2xl font-bold text-slate-900"><?php echo esc_html($form_title); ?></h2>
      <?php if ($form_intro) : ?>
        <p class="text-slate-600 mt-1"><?php echo esc_html($form_intro); ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div>
    <label for="package-id" class="block mb-2 text-sm font-medium">Package Name</label>
    <select id="package-id" name="package_id" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
      <option value="">Select package</option>
      <?php foreach ($packages as $package) : ?>
        <option value="<?php echo esc_attr((string) $package->ID); ?>" <?php selected($preselect_id, $package->ID); ?>><?php echo esc_html($package->post_title); ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="travel-date" class="block mb-2 text-sm font-medium">Date</label>
    <input id="travel-date" type="date" name="travel_date" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
  </div>

  <div>
    <label for="persons" class="block mb-2 text-sm font-medium">Number of Packs / Persons</label>
    <select id="persons" name="persons" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
      <option value="">Select persons</option>
      <?php for ($i = 1; $i <= 10; $i++) : ?>
        <option value="<?php echo esc_attr((string) $i); ?>"><?php echo esc_html((string) $i); ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div>
    <label for="full-name" class="block mb-2 text-sm font-medium">Full Name</label>
    <input id="full-name" type="text" name="full_name" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
  </div>

  <div>
    <label for="email" class="block mb-2 text-sm font-medium">Email</label>
    <input id="email" type="email" name="email" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
  </div>

  <div>
    <label for="phone" class="block mb-2 text-sm font-medium">Phone</label>
    <input id="phone" type="tel" name="phone" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
  </div>

  <div>
    <label for="address" class="block mb-2 text-sm font-medium">Full Address</label>
    <input id="address" type="text" name="address" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
  </div>

  <div>
    <label for="message" class="block mb-2 text-sm font-medium">Message</label>
    <textarea id="message" name="message" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="Write any additional requirement..."></textarea>
  </div>

  <div>
    <button type="submit" class="rounded-lg brand-gradient text-white px-6 py-3 font-semibold"><?php echo esc_html($submit_label); ?></button>
  </div>
</form>
