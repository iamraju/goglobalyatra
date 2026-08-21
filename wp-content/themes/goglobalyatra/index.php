<?php
/**
 * Fallback template (blog index / generic loop).
 */
get_header();
?>
<main class="max-w-7xl mx-auto px-4 py-14">
  <?php if (have_posts()) : ?>
    <div class="grid gap-8">
      <?php while (have_posts()) : the_post(); ?>
        <article class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
          <h2 class="text-2xl font-bold"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="mt-3 text-slate-700"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p>Nothing found.</p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
