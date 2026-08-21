<?php
/**
 * Single Travel Package template.
 */
get_header();

while (have_posts()) : the_post();
    $package_id = get_the_ID();
    $type = goglobalyatra_package_type($package_id);
    $duration = get_field('duration', $package_id);
    $highlights = goglobalyatra_field_lines('highlights', $package_id);
    $inclusions = goglobalyatra_field_lines('inclusions', $package_id);
    $exclusions = goglobalyatra_field_lines('exclusions', $package_id);

    $related = new WP_Query([
        'post_type' => 'travel',
        'posts_per_page' => 3,
        'post__not_in' => [$package_id],
        'tax_query' => [
            ['taxonomy' => 'package_type', 'field' => 'slug', 'terms' => $type],
        ],
    ]);

    $package_testimonials = new WP_Query([
        'post_type' => 'testimonial',
        'posts_per_page' => 3,
        'meta_query' => [['key' => 'related_package', 'value' => (string) $package_id]],
    ]);

    get_template_part('template-parts/page-hero', null, [
        'title' => get_the_title(),
        'subtitle' => get_the_excerpt(),
    ]);
    get_template_part('template-parts/flash');
    ?>

    <main>
      <section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-[7fr_3fr] gap-8 items-start">
        <article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
          <div class="h-72 md:h-96 overflow-hidden">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover', 'alt' => get_the_title()]); ?>
            <?php endif; ?>
          </div>

          <div class="p-7 md:p-8">
            <div class="flex flex-wrap items-center gap-2 mb-5">
              <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold"><?php echo esc_html(ucfirst($type)); ?> Package</span>
              <?php if ($duration) : ?>
                <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm font-medium"><?php echo esc_html($duration); ?></span>
              <?php endif; ?>
            </div>

            <h2 class="text-3xl font-extrabold text-slate-900"><?php the_title(); ?></h2>
            <?php if (get_the_excerpt()) : ?>
              <p class="text-slate-600 mt-3"><?php echo esc_html(get_the_excerpt()); ?></p>
            <?php endif; ?>

            <div class="mt-5 text-slate-700 leading-8 prose max-w-none">
              <?php the_content(); ?>
            </div>

            <section class="mt-10 grid md:grid-cols-3 gap-5">
              <?php if ($highlights !== []) : ?>
                <article class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                  <h3 class="text-lg font-bold text-slate-900">Highlights</h3>
                  <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    <?php foreach ($highlights as $item) : ?>
                      <li class="flex items-start gap-2"><span class="text-blue-700">&bull;</span><span><?php echo esc_html($item); ?></span></li>
                    <?php endforeach; ?>
                  </ul>
                </article>
              <?php endif; ?>

              <?php if ($inclusions !== []) : ?>
                <article class="rounded-xl border border-slate-200 bg-emerald-50 p-5">
                  <h3 class="text-lg font-bold text-slate-900">Inclusions</h3>
                  <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    <?php foreach ($inclusions as $item) : ?>
                      <li class="flex items-start gap-2"><span class="text-emerald-700">&bull;</span><span><?php echo esc_html($item); ?></span></li>
                    <?php endforeach; ?>
                  </ul>
                </article>
              <?php endif; ?>

              <?php if ($exclusions !== []) : ?>
                <article class="rounded-xl border border-slate-200 bg-rose-50 p-5">
                  <h3 class="text-lg font-bold text-slate-900">Exclusions</h3>
                  <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    <?php foreach ($exclusions as $item) : ?>
                      <li class="flex items-start gap-2"><span class="text-rose-700">&bull;</span><span><?php echo esc_html($item); ?></span></li>
                    <?php endforeach; ?>
                  </ul>
                </article>
              <?php endif; ?>
            </section>

            <?php if ($related->have_posts()) : ?>
              <section class="mt-10 border-t border-slate-200 pt-8">
                <div class="flex items-end justify-between gap-3 flex-wrap">
                  <h3 class="text-2xl font-bold text-slate-900">Related Packages</h3>
                  <a href="<?php echo esc_url(home_url('/' . $type . '-packages/')); ?>" class="text-blue-800 font-semibold">See all packages</a>
                </div>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5 mt-5">
                  <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <?php get_template_part('template-parts/package-card'); ?>
                  <?php endwhile; wp_reset_postdata(); ?>
                </div>
              </section>
            <?php endif; ?>

            <?php if ($package_testimonials->have_posts()) : ?>
              <section class="mt-10 border-t border-slate-200 pt-8">
                <h3 class="text-2xl font-bold text-slate-900">Traveler Stories from This Package</h3>
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5 mt-5">
                  <?php while ($package_testimonials->have_posts()) : $package_testimonials->the_post(); ?>
                    <?php get_template_part('template-parts/testimonial-card'); ?>
                  <?php endwhile; wp_reset_postdata(); ?>
                </div>
              </section>
            <?php endif; ?>
          </div>
        </article>

        <aside class="lg:sticky lg:top-28">
          <?php get_template_part('template-parts/booking-form', null, [
              'preselect_id' => $package_id,
              'title' => 'Book This Package',
              'intro' => 'Share your preferred travel dates and our team will contact you quickly.',
              'submit_label' => 'Submit Booking',
              'form_class' => 'bg-white rounded-2xl shadow-xl border border-slate-100 p-4 grid gap-3',
          ]); ?>
        </aside>
      </section>
    </main>
    <?php
endwhile;

get_footer();
