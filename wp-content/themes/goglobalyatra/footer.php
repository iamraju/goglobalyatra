<footer class="bg-slate-900 text-slate-200">
  <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-3 gap-8">
    <section>
      <?php if (has_custom_logo()) : the_custom_logo(); else : ?>
        <span class="text-xl font-extrabold text-white"><?php bloginfo('name'); ?></span>
      <?php endif; ?>
      <p class="mt-4 text-slate-300 text-sm leading-7">
        <?php bloginfo('name'); ?> is a trusted travel company offering inbound Nepal packages and outbound global tours with personalized support, transparent pricing, and memorable experiences.
      </p>
    </section>

    <section>
      <h2 class="font-semibold text-lg text-white">Quick Links</h2>
      <?php
      wp_nav_menu([
          'theme_location' => 'footer',
          'container' => false,
          'menu_class' => 'mt-4 space-y-2 text-sm',
          'fallback_cb' => 'goglobalyatra_default_menu',
      ]);
      ?>
    </section>

    <section>
      <h2 class="font-semibold text-lg text-white">Contact Details</h2>
      <ul class="mt-4 space-y-2 text-sm">
        <li><strong>Company:</strong> <?php bloginfo('name'); ?></li>
        <li><strong>Address:</strong> <?php echo esc_html(goglobalyatra_option('site_address')); ?></li>
        <li><strong>Email:</strong> <?php echo esc_html(goglobalyatra_option('topbar_email')); ?></li>
        <li><strong>Mobile:</strong> <?php echo esc_html(goglobalyatra_option('topbar_phone')); ?></li>
        <li><strong>Landline:</strong> <?php echo esc_html(goglobalyatra_option('topbar_landline')); ?></li>
      </ul>
    </section>
  </div>

  <div class="border-t border-slate-800 py-4 text-center text-sm text-slate-400">
    &copy; <span data-year><?php echo esc_html((string) gmdate('Y')); ?></span> <?php bloginfo('name'); ?>. All rights reserved.
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
