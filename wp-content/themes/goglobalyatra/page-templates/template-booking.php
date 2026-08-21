<?php
/**
 * Template Name: Booking Page
 */
get_header();
get_template_part('template-parts/page-hero', null, [
    'title' => 'Booking Form',
    'subtitle' => 'Send your travel booking request and our team will contact you quickly.',
]);
get_template_part('template-parts/flash');
?>
<main>
  <section class="max-w-7xl mx-auto px-4 py-14 grid lg:grid-cols-[7fr_3fr] gap-8 items-start">
    <div>
      <?php get_template_part('template-parts/booking-form', null, [
          'title' => 'Booking Form',
          'intro' => 'Submit your details and we will confirm itinerary options quickly.',
      ]); ?>
    </div>
    <div>
      <?php get_template_part('template-parts/popular-sidebar'); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
