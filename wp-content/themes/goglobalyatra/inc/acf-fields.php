<?php
/**
 * ACF field group for the Travel Package CPT (registered in PHP so it works
 * without needing to import/sync in wp-admin).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_travel_package',
        'title' => 'Package Details',
        'fields' => [
            [
                'key' => 'field_tp_duration',
                'label' => 'Duration',
                'name' => 'duration',
                'type' => 'text',
                'instructions' => 'E.g. "5 nights / 6 days"',
                'required' => 1,
            ],
            [
                'key' => 'field_tp_is_popular',
                'label' => 'Featured / Popular Package',
                'name' => 'is_popular',
                'type' => 'true_false',
                'instructions' => 'Show this package in "Popular Packages" sections.',
                'ui' => 1,
            ],
            [
                'key' => 'field_tp_highlights',
                'label' => 'Highlights',
                'name' => 'highlights',
                'type' => 'textarea',
                'instructions' => 'One highlight per line.',
                'rows' => 4,
                'new_lines' => '',
            ],
            [
                'key' => 'field_tp_inclusions',
                'label' => 'Inclusions',
                'name' => 'inclusions',
                'type' => 'textarea',
                'instructions' => 'One inclusion per line.',
                'rows' => 4,
                'new_lines' => '',
            ],
            [
                'key' => 'field_tp_exclusions',
                'label' => 'Exclusions',
                'name' => 'exclusions',
                'type' => 'textarea',
                'instructions' => 'One exclusion per line.',
                'rows' => 4,
                'new_lines' => '',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'travel',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
    ]);
});

/** Small helper: split a textarea field into a clean array of lines. */
function goglobalyatra_field_lines(string $field_name, int $post_id): array
{
    $raw = (string) get_field($field_name, $post_id);
    $lines = array_filter(array_map('trim', explode("\n", $raw)), fn($line) => $line !== '');
    return array_values($lines);
}

add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_testimonial',
        'title' => 'Testimonial Details',
        'fields' => [
            [
                'key' => 'field_ts_location',
                'label' => 'Customer Location',
                'name' => 'customer_location',
                'type' => 'text',
                'instructions' => 'E.g. "Kathmandu, Nepal" or "Sydney, Australia"',
            ],
            [
                'key' => 'field_ts_rating',
                'label' => 'Rating',
                'name' => 'rating',
                'type' => 'select',
                'choices' => ['5' => '5 Stars', '4' => '4 Stars', '3' => '3 Stars', '2' => '2 Stars', '1' => '1 Star'],
                'default_value' => '5',
                'allow_null' => 0,
            ],
            [
                'key' => 'field_ts_package',
                'label' => 'Related Package',
                'name' => 'related_package',
                'type' => 'post_object',
                'post_type' => ['travel'],
                'instructions' => 'Which package did this customer travel on?',
                'allow_null' => 1,
                'ui' => 1,
            ],
            [
                'key' => 'field_ts_featured',
                'label' => 'Feature on Homepage',
                'name' => 'is_featured',
                'type' => 'true_false',
                'ui' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'testimonial',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
    ]);
});
