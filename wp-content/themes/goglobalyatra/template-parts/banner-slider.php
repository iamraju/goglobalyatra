<?php
/**
 * Homepage hero banner slider, built from "popular" travel packages.
 */
$banners = new WP_Query([
    'post_type' => 'travel',
    'posts_per_page' => 6,
    'meta_query' => [
        [
            'key' => 'is_popular',
            'value' => '1',
        ],
    ],
]);

if (!$banners->have_posts()) {
    $banners = new WP_Query(['post_type' => 'travel', 'posts_per_page' => 5]);
}

$index = 0;
?>
<section class="relative overflow-hidden">
  <h1 class="sr-only"><?php bloginfo('name'); ?> Travel Packages</h1>

  <?php while ($banners->have_posts()) : $banners->the_post();
      $image = get_the_post_thumbnail_url(get_the_ID(), 'goglobalyatra-banner');
  ?>
    <article data-slide class="banner-slide <?php echo $index === 0 ? '' : 'hidden'; ?>" style="background-image: url('<?php echo esc_url((string) $image); ?>');">
      <div class="banner-overlay min-h-[460px] flex items-center">
        <div class="max-w-7xl mx-auto px-4 text-white">
          <p class="text-blue-100 mb-3 font-medium"><?php echo goglobalyatra_package_type(get_the_ID()) === 'inbound' ? 'Inbound Highlight' : 'Outbound Highlight'; ?></p>
          <h2 class="text-4xl md:text-5xl font-extrabold max-w-xl leading-tight"><?php the_title(); ?></h2>
          <a href="<?php the_permalink(); ?>" class="inline-flex mt-6 bg-white text-blue-900 font-semibold px-6 py-3 rounded-full">View Package</a>
        </div>
      </div>
    </article>
  <?php $index++; endwhile; wp_reset_postdata(); ?>

  <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-wrap justify-center gap-2 px-2">
    <?php for ($i = 0; $i < $index; $i++) : ?>
      <button data-slide-dot class="w-3 h-3 rounded-full <?php echo $i === 0 ? 'bg-white' : 'bg-white/45'; ?>" aria-label="Go to slide <?php echo esc_attr((string) ($i + 1)); ?>"></button>
    <?php endfor; ?>
  </div>
</section>
