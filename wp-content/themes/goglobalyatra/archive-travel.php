<?php
/**
 * Archive for the travel post type (fallback; the dedicated Inbound/Outbound
 * pages are the primary entry points).
 */
get_header();
get_template_part('template-parts/page-hero', null, ['title' => 'Travel Packages', 'subtitle' => 'Browse all inbound and outbound packages.']);
?>
<main>
  <section class="max-w-7xl mx-auto px-4 py-14">
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/package-card', null, ['show_description' => true]); ?>
      <?php endwhile; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
