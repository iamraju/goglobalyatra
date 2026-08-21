<?php
/**
 * Archive: all testimonials.
 */
get_header();
get_template_part('template-parts/page-hero', null, [
    'title' => 'Traveler Stories',
    'subtitle' => 'Real experiences shared by customers who traveled with us.',
]);
?>
<main>
  <section class="max-w-7xl mx-auto px-4 py-14">
    <?php if (have_posts()) : ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/testimonial-card'); ?>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <p class="text-slate-600">No testimonials yet. Check back soon.</p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
