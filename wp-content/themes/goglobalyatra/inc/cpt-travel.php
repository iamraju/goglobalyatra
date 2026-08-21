<?php
/**
 * Custom Post Type: Travel Package (travel)
 * Taxonomy: Package Type (package_type) -> inbound / outbound
 */

if (!defined('ABSPATH')) {
    exit;
}

function goglobalyatra_register_travel_cpt(): void
{
    $labels = [
        'name' => __('Travel Packages', 'goglobalyatra'),
        'singular_name' => __('Travel Package', 'goglobalyatra'),
        'add_new' => __('Add New', 'goglobalyatra'),
        'add_new_item' => __('Add New Travel Package', 'goglobalyatra'),
        'edit_item' => __('Edit Travel Package', 'goglobalyatra'),
        'new_item' => __('New Travel Package', 'goglobalyatra'),
        'view_item' => __('View Travel Package', 'goglobalyatra'),
        'search_items' => __('Search Travel Packages', 'goglobalyatra'),
        'not_found' => __('No travel packages found', 'goglobalyatra'),
        'not_found_in_trash' => __('No travel packages found in trash', 'goglobalyatra'),
        'all_items' => __('All Travel Packages', 'goglobalyatra'),
        'menu_name' => __('Travel Packages', 'goglobalyatra'),
    ];

    register_post_type('travel', [
        'labels' => $labels,
        'public' => true,
        'has_archive' => 'travel-packages',
        'menu_icon' => 'dashicons-airplane',
        'menu_position' => 5,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'rewrite' => ['slug' => 'travel-packages', 'with_front' => false],
    ]);

    register_taxonomy('package_type', 'travel', [
        'labels' => [
            'name' => __('Package Type', 'goglobalyatra'),
            'singular_name' => __('Package Type', 'goglobalyatra'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'package-type', 'with_front' => false],
    ]);
}
add_action('init', 'goglobalyatra_register_travel_cpt');

/** Ensure the Inbound / Outbound terms exist. */
function goglobalyatra_seed_package_type_terms(): void
{
    if (!term_exists('inbound', 'package_type')) {
        wp_insert_term('Inbound', 'package_type', ['slug' => 'inbound']);
    }
    if (!term_exists('outbound', 'package_type')) {
        wp_insert_term('Outbound', 'package_type', ['slug' => 'outbound']);
    }
}
add_action('init', 'goglobalyatra_seed_package_type_terms', 11);

/** is_popular checkbox column in admin list for quick reference. */
add_filter('manage_travel_posts_columns', function (array $columns): array {
    $columns['is_popular'] = __('Popular', 'goglobalyatra');
    $columns['duration'] = __('Duration', 'goglobalyatra');
    return $columns;
});

add_action('manage_travel_posts_custom_column', function (string $column, int $post_id): void {
    if ($column === 'is_popular') {
        echo get_field('is_popular', $post_id) ? '&#9733; Popular' : '&mdash;';
    }
    if ($column === 'duration') {
        echo esc_html((string) get_field('duration', $post_id));
    }
}, 10, 2);

/** Helper: get the package_type slug ('inbound' | 'outbound') for a travel post. */
function goglobalyatra_package_type(int $post_id): string
{
    $terms = get_the_terms($post_id, 'package_type');
    if (is_array($terms) && $terms !== []) {
        return (string) $terms[0]->slug;
    }
    return 'outbound';
}
