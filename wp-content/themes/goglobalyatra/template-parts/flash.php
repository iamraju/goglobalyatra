<?php
/**
 * Displays a success/error banner after booking or contact form submission.
 */
$booking_status = isset($_GET['booking']) ? sanitize_text_field(wp_unslash($_GET['booking'])) : '';
$contact_status = isset($_GET['contact']) ? sanitize_text_field(wp_unslash($_GET['contact'])) : '';
$status = $booking_status ?: $contact_status;

if (!in_array($status, ['success', 'error'], true)) {
    return;
}

$is_success = $status === 'success';
$message = $is_success
    ? 'Your request has been submitted successfully. We will contact you soon.'
    : 'Something went wrong. Please check the form and try again.';
?>
<section class="max-w-7xl mx-auto px-4 pt-6">
  <div class="rounded-lg px-4 py-3 text-sm <?php echo $is_success ? 'bg-green-100 text-green-900 border border-green-200' : 'bg-red-100 text-red-900 border border-red-200'; ?>">
    <?php echo esc_html($message); ?>
  </div>
</section>
