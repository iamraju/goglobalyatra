<?php
/**
 * Page hero banner. Args: $args['title'], $args['subtitle'].
 * @var array $args
 */
$hero_title = $args['title'] ?? get_the_title();
$hero_subtitle = $args['subtitle'] ?? '';
?>
<section class="page-hero text-white py-16">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-4xl md:text-5xl font-extrabold"><?php echo esc_html($hero_title); ?></h1>
    <?php if ($hero_subtitle !== '') : ?>
      <p class="text-blue-50 mt-3 max-w-2xl"><?php echo esc_html($hero_subtitle); ?></p>
    <?php endif; ?>
  </div>
</section>
