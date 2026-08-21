<?php
/**
 * Package card. Uses current global $post (call inside a WP_Query loop).
 * Optional $args['show_description'] = true to include excerpt.
 */
$show_description = $args['show_description'] ?? false;
$package_id = get_the_ID();
$duration = get_field('duration', $package_id);
?>
<article class="package-card bg-white rounded-xl shadow-lg overflow-hidden border border-slate-100">
  <a href="<?php the_permalink(); ?>" class="block">
    <div class="h-52 overflow-hidden">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('goglobalyatra-card', ['class' => 'w-full h-full object-cover', 'alt' => get_the_title()]); ?>
      <?php endif; ?>
    </div>
    <div class="p-5">
      <h2 class="text-xl font-bold"><?php the_title(); ?></h2>
      <?php if ($duration) : ?>
        <p class="text-slate-600 mt-1"><?php echo esc_html($duration); ?></p>
      <?php endif; ?>
      <?php if ($show_description) : ?>
        <p class="text-slate-600 mt-2 text-sm"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <span class="inline-flex mt-4 px-4 py-2 rounded-lg brand-gradient text-white font-medium">View Details</span>
    </div>
  </a>
</article>
