<?php
/**
 * Theme Customizer: site-wide contact/brand settings (replaces the old
 * static config.php 'site' array).
 */

if (!defined('ABSPATH')) {
    exit;
}

function goglobalyatra_customize_register(WP_Customize_Manager $wp_customize): void
{
    $wp_customize->add_section('goglobalyatra_contact', [
        'title' => __('Site Contact Details', 'goglobalyatra'),
        'priority' => 30,
    ]);

    $fields = [
        'topbar_phone' => ['label' => 'Mobile Phone', 'default' => '+977-9851453377'],
        'topbar_landline' => ['label' => 'Landline', 'default' => '+977-1-4508777'],
        'topbar_email' => ['label' => 'Contact Email', 'default' => 'info@goglobalyatra.com'],
        'site_address' => ['label' => 'Office Address', 'default' => 'Thamel, Kathmandu, Nepal'],
    ];

    foreach ($fields as $id => $field) {
        $wp_customize->add_setting('goglobalyatra_' . $id, [
            'default' => $field['default'],
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('goglobalyatra_' . $id, [
            'label' => __($field['label'], 'goglobalyatra'),
            'section' => 'goglobalyatra_contact',
            'type' => 'text',
        ]);
    }
}
add_action('customize_register', 'goglobalyatra_customize_register');

function goglobalyatra_option(string $key, string $default = ''): string
{
    return (string) get_theme_mod('goglobalyatra_' . $key, $default);
}
