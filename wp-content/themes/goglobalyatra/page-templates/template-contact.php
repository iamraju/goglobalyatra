<?php
/**
 * Template Name: Contact Page
 */
get_header();
get_template_part('template-parts/page-hero', null, [
    'title' => 'Contact Us',
    'subtitle' => 'Reach out to plan your next inbound or outbound journey.',
]);
get_template_part('template-parts/flash');
?>
<main>
  <section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-[7fr_3fr] gap-8 items-start">
    <div class="space-y-8">
      <article class="bg-white rounded-2xl shadow-xl border border-slate-100 p-7">
        <h2 class="text-2xl font-bold text-slate-900">Contact Details</h2>
        <ul class="mt-5 space-y-3 text-slate-700 leading-7">
          <li><strong>Company Name:</strong> <?php bloginfo('name'); ?></li>
          <li><strong>Address:</strong> <?php echo esc_html(goglobalyatra_option('site_address')); ?></li>
          <li><strong>Email:</strong> <?php echo esc_html(goglobalyatra_option('topbar_email')); ?></li>
          <li><strong>Phone:</strong> <?php echo esc_html(goglobalyatra_option('topbar_phone')); ?></li>
        </ul>
      </article>

      <article class="bg-white rounded-2xl shadow-xl border border-slate-100 p-7">
        <h2 class="text-2xl font-bold text-slate-900">Send a Message</h2>
        <form class="mt-5 grid gap-4" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="goglobalyatra_contact" />
          <?php wp_nonce_field('goglobalyatra_contact', 'goglobalyatra_contact_nonce'); ?>
          <div>
            <label for="name" class="block mb-2 text-sm font-medium">Full Name</label>
            <input id="name" type="text" name="full_name" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
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
            <label for="message" class="block mb-2 text-sm font-medium">Message</label>
            <textarea id="message" name="message" rows="5" class="w-full rounded-lg border border-slate-300 px-3 py-2" required></textarea>
          </div>
          <button type="submit" class="rounded-lg brand-gradient text-white px-6 py-3 font-semibold">Send Message</button>
        </form>
      </article>
    </div>

    <div>
      <?php get_template_part('template-parts/popular-sidebar'); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
