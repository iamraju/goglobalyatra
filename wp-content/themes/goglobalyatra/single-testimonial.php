<?php
/**
 * Single testimonial: full story, photo gallery, rating, related package.
 */
get_header();

while (have_posts()) : the_post();
    $testimonial_id = get_the_ID();
    $rating = (int) get_field('rating', $testimonial_id);
    $location = (string) get_field('customer_location', $testimonial_id);
    $related_package = get_field('related_package', $testimonial_id);
    $photo_ids = goglobalyatra_testimonial_photo_ids($testimonial_id);

    get_template_part('template-parts/page-hero', null, [
        'title' => get_the_title(),
        'subtitle' => $location,
    ]);
    ?>
    <main>
      <section class="max-w-4xl mx-auto px-4 py-14">
        <article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
          <?php if (has_post_thumbnail()) : ?>
            <div class="h-72 md:h-96 overflow-hidden">
              <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover', 'alt' => get_the_title()]); ?>
            </div>
          <?php endif; ?>

          <div class="p-7 md:p-8">
            <?php if ($rating > 0) : ?>
              <div class="text-amber-500 text-xl" aria-label="<?php echo esc_attr($rating . ' out of 5 stars'); ?>">
                <?php echo str_repeat('&#9733;', $rating) . str_repeat('&#9734;', 5 - $rating); ?>
              </div>
            <?php endif; ?>

            <div class="mt-4 text-slate-700 leading-8 prose max-w-none">
              <?php the_content(); ?>
            </div>

            <?php if ($related_package instanceof WP_Post) : ?>
              <p class="mt-6 text-sm">
                <span class="text-slate-500">Traveled on:</span>
                <a href="<?php echo esc_url(get_permalink($related_package)); ?>" class="font-semibold text-blue-700 hover:underline"><?php echo esc_html($related_package->post_title); ?></a>
              </p>
            <?php endif; ?>

            <?php if ($photo_ids !== []) : ?>
              <section class="mt-8 border-t border-slate-200 pt-6">
                <h2 class="text-lg font-bold text-slate-900">Trip Photos</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4" data-lightbox-gallery>
                  <?php foreach ($photo_ids as $photo_id) : ?>
                    <button type="button" class="block h-32 w-full rounded-lg overflow-hidden" data-lightbox-trigger data-lightbox-src="<?php echo esc_url((string) wp_get_attachment_image_url($photo_id, 'full')); ?>" data-lightbox-alt="<?php echo esc_attr(get_the_title()); ?>">
                      <?php echo wp_get_attachment_image($photo_id, 'goglobalyatra-card', false, ['class' => 'w-full h-full object-cover']); ?>
                    </button>
                  <?php endforeach; ?>
                </div>
              </section>
            <?php endif; ?>
          </div>
        </article>
      </section>
    </main>

    <div data-lightbox class="hidden fixed inset-0 z-[100] bg-slate-900/90 items-center justify-center p-4" role="dialog" aria-modal="true" aria-label="Photo viewer">
      <button type="button" data-lightbox-close class="absolute top-4 right-4 text-white text-3xl leading-none w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10" aria-label="Close">&times;</button>
      <button type="button" data-lightbox-prev class="absolute left-2 md:left-6 text-white text-4xl leading-none w-12 h-12 flex items-center justify-center rounded-full hover:bg-white/10" aria-label="Previous photo">&#8249;</button>
      <img data-lightbox-image src="" alt="" class="max-h-[85vh] max-w-full rounded-lg shadow-2xl" />
      <button type="button" data-lightbox-next class="absolute right-2 md:right-6 text-white text-4xl leading-none w-12 h-12 flex items-center justify-center rounded-full hover:bg-white/10" aria-label="Next photo">&#8250;</button>
    </div>
    <?php
endwhile;

get_footer();
