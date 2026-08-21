<?php
/**
 * Template Name: Inbound Packages
 */
get_header();
get_template_part('template-parts/page-hero', null, [
    'title' => 'Inbound Packages',
    'subtitle' => 'Nepal tours crafted for international travelers with complete booking support.',
]);

$packages = new WP_Query([
    'post_type' => 'travel',
    'posts_per_page' => -1,
    'tax_query' => [['taxonomy' => 'package_type', 'field' => 'slug', 'terms' => 'inbound']],
]);
?>
<main>
  <section class="max-w-7xl mx-auto px-4 py-14">
    <?php if ($packages->have_posts()) : ?>
      <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php while ($packages->have_posts()) : $packages->the_post(); ?>
          <?php get_template_part('template-parts/package-card', null, ['show_description' => true]); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p class="text-slate-600">No inbound packages available right now. Please check back soon.</p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
