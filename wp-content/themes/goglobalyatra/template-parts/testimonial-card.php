<?php
/**
 * Testimonial card. Uses current global $post (call inside a WP_Query loop).
 */
$testimonial_id = get_the_ID();
$rating = (int) get_field('rating', $testimonial_id);
$location = (string) get_field('customer_location', $testimonial_id);
$related_package = get_field('related_package', $testimonial_id);
?>
<article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
  <a href="<?php the_permalink(); ?>" class="block h-48 overflow-hidden">
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('goglobalyatra-card', ['class' => 'w-full h-full object-cover', 'alt' => get_the_title()]); ?>
    <?php else : ?>
      <div class="w-full h-full brand-gradient flex items-center justify-center text-white text-4xl font-bold"><?php echo esc_html(mb_substr(get_the_title(), 0, 1)); ?></div>
    <?php endif; ?>
  </a>
  <div class="p-5 flex-1 flex flex-col">
    <?php if ($rating > 0) : ?>
      <div class="text-amber-500 text-sm" aria-label="<?php echo esc_attr($rating . ' out of 5 stars'); ?>">
        <?php echo str_repeat('&#9733;', $rating) . str_repeat('&#9734;', 5 - $rating); ?>
      </div>
    <?php endif; ?>
    <p class="mt-2 text-slate-700 text-sm leading-6 flex-1">&ldquo;<?php echo esc_html(wp_trim_words(get_the_content(), 28)); ?>&rdquo;</p>
    <div class="mt-4 border-t border-slate-100 pt-3">
      <h3 class="font-bold text-slate-900"><?php the_title(); ?></h3>
      <?php if ($location !== '') : ?>
        <p class="text-xs text-slate-500"><?php echo esc_html($location); ?></p>
      <?php endif; ?>
      <?php if ($related_package instanceof WP_Post) : ?>
        <a href="<?php echo esc_url(get_permalink($related_package)); ?>" class="inline-flex mt-2 text-xs font-semibold text-blue-700 hover:underline">Traveled: <?php echo esc_html($related_package->post_title); ?></a>
      <?php endif; ?>
    </div>
  </div>
</article>
