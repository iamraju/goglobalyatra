<?php
/**
 * Booking & Contact form handling (front-end POST via admin-post.php).
 * Uses WP nonces (CSRF), sanitizes input, sends mail via wp_mail (routed
 * through WP Mail SMTP), and redirects back with a flash-style query flag.
 */

if (!defined('ABSPATH')) {
    exit;
}

function goglobalyatra_handle_booking_form(): void
{
    $redirect = wp_get_referer() ?: home_url('/booking/');

    if (!isset($_POST['goglobalyatra_booking_nonce']) || !wp_verify_nonce($_POST['goglobalyatra_booking_nonce'], 'goglobalyatra_booking')) {
        wp_safe_redirect(add_query_arg('booking', 'error', $redirect));
        exit;
    }

    $package_id = absint($_POST['package_id'] ?? 0);
    $travel_date = sanitize_text_field($_POST['travel_date'] ?? '');
    $persons = sanitize_text_field($_POST['persons'] ?? '');
    $full_name = sanitize_text_field($_POST['full_name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $address = sanitize_text_field($_POST['address'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    $package = $package_id ? get_post($package_id) : null;

    if (!$package_id || !$travel_date || !$persons || !$full_name || !$email || !$phone || !$address || !is_email($email) || !$package) {
        wp_safe_redirect(add_query_arg('booking', 'error', $redirect));
        exit;
    }

    $to = get_option('admin_email');
    $subject = 'Booking Request - ' . $package->post_title;
    $body = '<h2>New Booking Request</h2>'
        . '<p><strong>Package:</strong> ' . esc_html($package->post_title) . '</p>'
        . '<p><strong>Travel Date:</strong> ' . esc_html($travel_date) . '</p>'
        . '<p><strong>Persons:</strong> ' . esc_html($persons) . '</p>'
        . '<p><strong>Full Name:</strong> ' . esc_html($full_name) . '</p>'
        . '<p><strong>Email:</strong> ' . esc_html($email) . '</p>'
        . '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>'
        . '<p><strong>Address:</strong> ' . esc_html($address) . '</p>'
        . '<p><strong>Message:</strong><br>' . nl2br(esc_html($message)) . '</p>';

    $headers = ['Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email];
    $sent = wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('booking', $sent ? 'success' : 'error', $redirect));
    exit;
}
add_action('admin_post_goglobalyatra_booking', 'goglobalyatra_handle_booking_form');
add_action('admin_post_nopriv_goglobalyatra_booking', 'goglobalyatra_handle_booking_form');

function goglobalyatra_handle_contact_form(): void
{
    $redirect = wp_get_referer() ?: home_url('/contact/');

    if (!isset($_POST['goglobalyatra_contact_nonce']) || !wp_verify_nonce($_POST['goglobalyatra_contact_nonce'], 'goglobalyatra_contact')) {
        wp_safe_redirect(add_query_arg('contact', 'error', $redirect));
        exit;
    }

    $full_name = sanitize_text_field($_POST['full_name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (!$full_name || !$email || !$phone || !$message || !is_email($email)) {
        wp_safe_redirect(add_query_arg('contact', 'error', $redirect));
        exit;
    }

    $to = get_option('admin_email');
    $subject = 'Contact Message - ' . get_bloginfo('name');
    $body = '<h2>New Contact Message</h2>'
        . '<p><strong>Full Name:</strong> ' . esc_html($full_name) . '</p>'
        . '<p><strong>Email:</strong> ' . esc_html($email) . '</p>'
        . '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>'
        . '<p><strong>Message:</strong><br>' . nl2br(esc_html($message)) . '</p>';

    $headers = ['Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email];
    $sent = wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('contact', $sent ? 'success' : 'error', $redirect));
    exit;
}
add_action('admin_post_goglobalyatra_contact', 'goglobalyatra_handle_contact_form');
add_action('admin_post_nopriv_goglobalyatra_contact', 'goglobalyatra_handle_contact_form');
