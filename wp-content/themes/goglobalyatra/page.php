<?php
/**
 * Generic page template (About uses this via WYSIWYG unless a specific
 * page template is selected).
 */
get_header();

while (have_posts()) : the_post();
    get_template_part('template-parts/page-hero', null, ['title' => get_the_title(), 'subtitle' => '']);
    get_template_part('template-parts/flash');
    ?>
    <main>
      <section class="max-w-5xl mx-auto px-4 py-14">
        <article class="bg-white rounded-2xl shadow-lg p-7 border border-slate-100 leading-8 prose max-w-none">
          <?php the_content(); ?>
        </article>
      </section>
    </main>
    <?php
endwhile;

get_footer();
