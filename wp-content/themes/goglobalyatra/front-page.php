<?php
/**
 * Front page: hero banner slider, search widget, popular packages grid.
 */
get_header();

$popular_query = new WP_Query([
    'post_type' => 'travel',
    'posts_per_page' => 6,
    'meta_query' => [['key' => 'is_popular', 'value' => '1']],
]);

$durations = ['2-5 days', '5-7 days', 'More than a week', 'More than 10 days'];

$outbound_destinations = get_posts([
    'post_type' => 'travel',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'tax_query' => [['taxonomy' => 'package_type', 'field' => 'slug', 'terms' => 'outbound']],
]);
$inbound_destinations = get_posts([
    'post_type' => 'travel',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'tax_query' => [['taxonomy' => 'package_type', 'field' => 'slug', 'terms' => 'inbound']],
]);

$testimonials_query = new WP_Query([
    'post_type' => 'testimonial',
    'posts_per_page' => 6,
    'meta_query' => [['key' => 'is_featured', 'value' => '1']],
]);
if (!$testimonials_query->have_posts()) {
    $testimonials_query = new WP_Query(['post_type' => 'testimonial', 'posts_per_page' => 6]);
}
?>

<main id="main-content">
  <?php get_template_part('template-parts/banner-slider'); ?>

  <section class="max-w-7xl mx-auto px-4 -mt-14 relative z-20">
    <div class="glass-card rounded-2xl shadow-xl p-6 md:p-8 border border-white/40">
      <h2 class="text-2xl font-bold text-slate-900">Find Your Perfect Package</h2>
      <p class="text-slate-600 mt-1">Search outbound or inbound packages with your preferred dates and duration.</p>

      <div class="mt-5 inline-flex rounded-lg border border-slate-200 bg-slate-100 p-1" role="tablist" aria-label="Package type">
        <button type="button" data-search-tab="outbound" class="px-5 py-2 rounded-md text-sm font-semibold brand-gradient text-white" aria-selected="true">Outbound</button>
        <button type="button" data-search-tab="inbound" class="px-5 py-2 rounded-md text-sm font-semibold text-slate-700" aria-selected="false">Inbound</button>
      </div>

      <form data-home-search-form class="mt-6 grid md:grid-cols-2 lg:grid-cols-3 gap-4" action="<?php echo esc_url(home_url('/outbound-packages/')); ?>" method="get">
        <div>
          <label class="block mb-2 text-sm font-medium" for="destination">Destination</label>
          <select id="destination" name="destination" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
            <option value="">Choose destination</option>
            <?php foreach ($outbound_destinations as $destination) : ?>
              <option value="<?php echo esc_attr($destination->post_title); ?>"><?php echo esc_html($destination->post_title); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="duration">Duration</label>
          <select id="duration" name="duration" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
            <option value="">Select duration</option>
            <?php foreach ($durations as $duration) : ?>
              <option value="<?php echo esc_attr($duration); ?>"><?php echo esc_html($duration); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="persons">Packs / Number of Persons</label>
          <select id="persons" name="persons" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
            <option value="">Select persons</option>
            <?php for ($i = 1; $i <= 10; $i++) : ?>
              <option value="<?php echo esc_attr((string) $i); ?>"><?php echo esc_html((string) $i); ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="start-date">Start Date</label>
          <input data-start-date id="start-date" type="date" name="start_date" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium" for="end-date">End Date</label>
          <input data-end-date id="end-date" type="date" name="end_date" class="w-full rounded-lg border border-slate-300 px-3 py-2" required />
        </div>

        <div class="flex items-end">
          <button type="submit" class="w-full rounded-lg brand-gradient text-white py-2.5 font-semibold">Search Packages</button>
        </div>
      </form>
    </div>
  </section>

  <script>
    (function () {
      var destinations = {
        outbound: <?php echo wp_json_encode(wp_list_pluck($outbound_destinations, 'post_title')); ?>,
        inbound: <?php echo wp_json_encode(wp_list_pluck($inbound_destinations, 'post_title')); ?>
      };
      var urls = { outbound: '<?php echo esc_js(home_url('/outbound-packages/')); ?>', inbound: '<?php echo esc_js(home_url('/inbound-packages/')); ?>' };
      var form = document.querySelector('[data-home-search-form]');
      var destinationSelect = document.getElementById('destination');
      var tabs = document.querySelectorAll('[data-search-tab]');

      var setTab = function (type) {
        form.setAttribute('action', urls[type]);
        destinationSelect.innerHTML = '<option value="">Choose destination</option>';
        destinations[type].forEach(function (title) {
          var option = document.createElement('option');
          option.value = title;
          option.textContent = title;
          destinationSelect.appendChild(option);
        });
        tabs.forEach(function (tab) {
          var active = tab.getAttribute('data-search-tab') === type;
          tab.classList.toggle('brand-gradient', active);
          tab.classList.toggle('text-white', active);
          tab.classList.toggle('text-slate-700', !active);
          tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });
      };

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          setTab(tab.getAttribute('data-search-tab'));
        });
      });
    })();
  </script>

  <section class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-end justify-between gap-3 flex-wrap">
      <div>
        <h2 class="text-3xl font-extrabold text-slate-900">Popular Packages</h2>
        <p class="text-slate-600 mt-1">Most-loved tours chosen by Nepali and international travelers.</p>
      </div>
      <a href="<?php echo esc_url(home_url('/outbound-packages/')); ?>" class="text-blue-800 font-semibold">See all outbound tours</a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
      <?php while ($popular_query->have_posts()) : $popular_query->the_post(); ?>
        <?php get_template_part('template-parts/package-card'); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </section>

  <?php if ($testimonials_query->have_posts()) : ?>
    <section class="bg-slate-50 border-y border-slate-100 py-16">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between gap-3 flex-wrap">
          <div>
            <h2 class="text-3xl font-extrabold text-slate-900">What Our Travelers Say</h2>
            <p class="text-slate-600 mt-1">Stories and photos shared by customers after their trips.</p>
          </div>
          <a href="<?php echo esc_url(home_url('/testimonials/')); ?>" class="text-blue-800 font-semibold">Read all stories</a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
          <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post(); ?>
            <?php get_template_part('template-parts/testimonial-card'); ?>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
