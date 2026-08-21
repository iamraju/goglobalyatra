<?php
/**
 * Theme setup: supports, menus, image sizes, enqueue.
 */

if (!defined('ABSPATH')) {
    exit;
}

function goglobalyatra_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('align-wide');
    add_theme_support('custom-logo', [
        'height' => 60,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    set_post_thumbnail_size(800, 600, true);
    add_image_size('goglobalyatra-card', 640, 420, true);
    add_image_size('goglobalyatra-banner', 1600, 700, true);

    register_nav_menus([
        'primary' => __('Primary Navigation', 'goglobalyatra'),
        'footer' => __('Footer Navigation', 'goglobalyatra'),
    ]);
}
add_action('after_setup_theme', 'goglobalyatra_setup');

function goglobalyatra_assets(): void
{
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();
    $css_path = '/assets/dist/style.css';
    $css_ver = file_exists($theme_dir . $css_path) ? filemtime($theme_dir . $css_path) : '1.0.0';
    $js_ver = file_exists($theme_dir . '/assets/js/main.js') ? filemtime($theme_dir . '/assets/js/main.js') : '1.0.0';

    wp_enqueue_style('goglobalyatra-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap', [], null);
    wp_enqueue_style('goglobalyatra-style', $theme_uri . $css_path, [], $css_ver);
    wp_enqueue_script('goglobalyatra-main', $theme_uri . '/assets/js/main.js', [], $js_ver, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'goglobalyatra_assets');

/** Default excerpt length / more marker */
add_filter('excerpt_length', fn() => 30);
add_filter('excerpt_more', fn() => '&hellip;');

/** Remove default WP block library CSS in favor of Tailwind build (keep it lean) */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('classic-theme-styles');
}, 20);
