<?php
/**
 * Goglobal Yatra theme bootstrap.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('GOGLOBALYATRA_VERSION', '1.0.0');

require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/cpt-travel.php';
require get_template_directory() . '/inc/cpt-testimonial.php';
require get_template_directory() . '/inc/acf-fields.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/forms.php';
require get_template_directory() . '/inc/seo.php';

/** TravelAgency JSON-LD schema on the front page (Yoast handles per-page SEO meta/schema). */
function goglobalyatra_organization_schema(): void
{
    if (!is_front_page()) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'TravelAgency',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'telephone' => goglobalyatra_option('topbar_phone'),
        'email' => goglobalyatra_option('topbar_email'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => goglobalyatra_option('site_address'),
            'addressCountry' => 'NP',
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'goglobalyatra_organization_schema');

/** TouristTrip schema for single travel package pages. */
function goglobalyatra_travel_schema(): void
{
    if (!is_singular('travel')) {
        return;
    }

    $post_id = get_the_ID();
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristTrip',
        'name' => get_the_title($post_id),
        'description' => get_the_excerpt($post_id),
        'image' => get_the_post_thumbnail_url($post_id, 'full') ?: '',
        'url' => get_permalink($post_id),
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'goglobalyatra_travel_schema');

/** Review schema for single testimonial pages (helps SEO rich snippets). */
function goglobalyatra_testimonial_schema(): void
{
    if (!is_singular('testimonial')) {
        return;
    }

    $post_id = get_the_ID();
    $rating = (int) get_field('rating', $post_id);
    $related_package = get_field('related_package', $post_id);

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Review',
        'author' => ['@type' => 'Person', 'name' => get_the_title($post_id)],
        'reviewBody' => wp_strip_all_tags(get_the_content(null, false, $post_id)),
        'datePublished' => get_the_date('c', $post_id),
    ];

    if ($rating > 0) {
        $schema['reviewRating'] = ['@type' => 'Rating', 'ratingValue' => $rating, 'bestRating' => 5];
    }

    if ($related_package instanceof WP_Post) {
        $schema['itemReviewed'] = [
            '@type' => 'TouristTrip',
            'name' => $related_package->post_title,
            'url' => get_permalink($related_package),
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'goglobalyatra_testimonial_schema');

/** Register a fallback single-column footer widget area for quick links. */
function goglobalyatra_widgets_init(): void
{
    register_sidebar([
        'name' => __('Footer Contact', 'goglobalyatra'),
        'id' => 'footer-contact',
        'before_widget' => '<div class="mb-4">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="font-semibold text-lg text-white">',
        'after_title' => '</h2>',
    ]);
}
add_action('widgets_init', 'goglobalyatra_widgets_init');
