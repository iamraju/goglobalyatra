<?php
/**
 * Popular packages sidebar widget. Optional $args['exclude_id'].
 */
$exclude_id = $args['exclude_id'] ?? 0;

$popular = new WP_Query([
    'post_type' => 'travel',
    'posts_per_page' => 5,
    'post__not_in' => $exclude_id ? [$exclude_id] : [],
    'meta_query' => [
        [
            'key' => 'is_popular',
            'value' => '1',
        ],
    ],
]);
?>
<aside class="space-y-4">
  <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5">
    <h2 class="text-lg font-bold text-slate-900">Popular Packages</h2>
    <p class="text-sm text-slate-600 mt-1">Trending tours travelers ask for most.</p>
  </div>

  <?php while ($popular->have_posts()) : $popular->the_post(); ?>
    <article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
      <a href="<?php the_permalink(); ?>" class="block">
        <div class="h-36 overflow-hidden">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('goglobalyatra-card', ['class' => 'w-full h-full object-cover', 'alt' => get_the_title()]); ?>
          <?php endif; ?>
        </div>
        <div class="p-4">
          <h3 class="text-base font-bold text-slate-900"><?php the_title(); ?></h3>
          <p class="text-sm text-slate-600 mt-1"><?php echo esc_html((string) get_field('duration')); ?></p>
          <span class="inline-flex mt-3 px-3 py-1.5 rounded-lg brand-gradient text-white text-sm font-medium">View Details</span>
        </div>
      </a>
    </article>
  <?php endwhile; wp_reset_postdata(); ?>
</aside>
