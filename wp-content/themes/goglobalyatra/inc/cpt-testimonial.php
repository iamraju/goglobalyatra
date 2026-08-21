<?php
/**
 * Custom Post Type: Testimonial
 * Customer name = post title, testimonial text = post content,
 * cover photo = featured image, extra photos = custom meta box (media uploader).
 */

if (!defined('ABSPATH')) {
    exit;
}

function goglobalyatra_register_testimonial_cpt(): void
{
    register_post_type('testimonial', [
        'labels' => [
            'name' => __('Testimonials', 'goglobalyatra'),
            'singular_name' => __('Testimonial', 'goglobalyatra'),
            'add_new_item' => __('Add New Testimonial', 'goglobalyatra'),
            'edit_item' => __('Edit Testimonial', 'goglobalyatra'),
            'all_items' => __('Testimonials', 'goglobalyatra'),
            'search_items' => __('Search Testimonials', 'goglobalyatra'),
            'not_found' => __('No testimonials found', 'goglobalyatra'),
            'menu_name' => __('Testimonials', 'goglobalyatra'),
        ],
        'public' => true,
        'has_archive' => 'testimonials',
        'menu_icon' => 'dashicons-format-quote',
        'menu_position' => 6,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
        'rewrite' => ['slug' => 'testimonials', 'with_front' => false],
    ]);
}
add_action('init', 'goglobalyatra_register_testimonial_cpt');

/** "Additional Photos" meta box: stores comma-separated attachment IDs. */
function goglobalyatra_testimonial_photos_meta_box(): void
{
    add_meta_box(
        'goglobalyatra_testimonial_photos',
        __('Additional Photos', 'goglobalyatra'),
        'goglobalyatra_render_testimonial_photos_meta_box',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'goglobalyatra_testimonial_photos_meta_box');

function goglobalyatra_render_testimonial_photos_meta_box(WP_Post $post): void
{
    wp_nonce_field('goglobalyatra_save_testimonial_photos', 'goglobalyatra_testimonial_photos_nonce');
    $ids = get_post_meta($post->ID, '_testimonial_photo_ids', true);
    ?>
    <p class="description">Upload photos the traveler shared with you, or ones you took during the trip.</p>
    <div id="goglobalyatra-testimonial-photos" class="goglobalyatra-photo-grid" style="display:flex;flex-wrap:wrap;gap:10px;margin:12px 0;"></div>
    <input type="hidden" name="testimonial_photo_ids" id="testimonial_photo_ids" value="<?php echo esc_attr((string) $ids); ?>" />
    <button type="button" class="button" id="goglobalyatra-add-photos"><?php esc_html_e('Add Photos', 'goglobalyatra'); ?></button>
    <script>
    (function ($) {
      function renderPhotos() {
        var ids = ($('#testimonial_photo_ids').val() || '').split(',').filter(Boolean);
        var $grid = $('#goglobalyatra-testimonial-photos').empty();
        if (!ids.length) { return; }
        ids.forEach(function (id) {
          wp.media.attachment(id).fetch().then(function () {
            var url = wp.media.attachment(id).get('url');
            var thumb = (wp.media.attachment(id).get('sizes') || {}).thumbnail;
            $grid.append(
              '<div class="goglobalyatra-photo" data-id="' + id + '" style="position:relative;">' +
              '<img src="' + (thumb ? thumb.url : url) + '" style="width:100px;height:100px;object-fit:cover;border-radius:4px;" />' +
              '<button type="button" class="button-link goglobalyatra-remove-photo" style="position:absolute;top:2px;right:2px;background:#fff;border-radius:50%;padding:0 5px;">&times;</button>' +
              '</div>'
            );
          });
        });
      }

      $(document).on('click', '#goglobalyatra-add-photos', function (e) {
        e.preventDefault();
        var frame = wp.media({ title: 'Select Photos', multiple: true, library: { type: 'image' } });
        frame.on('select', function () {
          var selection = frame.state().get('selection');
          var ids = ($('#testimonial_photo_ids').val() || '').split(',').filter(Boolean);
          selection.each(function (attachment) {
            var id = String(attachment.id);
            if (ids.indexOf(id) === -1) { ids.push(id); }
          });
          $('#testimonial_photo_ids').val(ids.join(','));
          renderPhotos();
        });
        frame.open();
      });

      $(document).on('click', '.goglobalyatra-remove-photo', function () {
        var id = $(this).closest('.goglobalyatra-photo').data('id').toString();
        var ids = ($('#testimonial_photo_ids').val() || '').split(',').filter(function (v) { return v && v !== id; });
        $('#testimonial_photo_ids').val(ids.join(','));
        renderPhotos();
      });

      $(renderPhotos);
    })(jQuery);
    </script>
    <?php
}

function goglobalyatra_save_testimonial_photos(int $post_id): void
{
    if (!isset($_POST['goglobalyatra_testimonial_photos_nonce']) || !wp_verify_nonce($_POST['goglobalyatra_testimonial_photos_nonce'], 'goglobalyatra_save_testimonial_photos')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $ids = isset($_POST['testimonial_photo_ids']) ? sanitize_text_field(wp_unslash($_POST['testimonial_photo_ids'])) : '';
    $clean_ids = implode(',', array_filter(array_map('absint', explode(',', $ids))));
    update_post_meta($post_id, '_testimonial_photo_ids', $clean_ids);
}
add_action('save_post_testimonial', 'goglobalyatra_save_testimonial_photos');

function goglobalyatra_testimonial_photos_enqueue(string $hook): void
{
    global $post_type;
    if (in_array($hook, ['post.php', 'post-new.php'], true) && $post_type === 'testimonial') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'goglobalyatra_testimonial_photos_enqueue');

/** Helper: returns array of attachment IDs for a testimonial's extra photos. */
function goglobalyatra_testimonial_photo_ids(int $post_id): array
{
    $raw = (string) get_post_meta($post_id, '_testimonial_photo_ids', true);
    return array_values(array_filter(array_map('absint', explode(',', $raw))));
}

/** Admin list columns: cover photo + rating. */
add_filter('manage_testimonial_posts_columns', function (array $columns): array {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['photo'] = __('Cover Photo', 'goglobalyatra');
            $new['rating'] = __('Rating', 'goglobalyatra');
            $new['package'] = __('Package', 'goglobalyatra');
        }
    }
    return $new;
});

add_action('manage_testimonial_posts_custom_column', function (string $column, int $post_id): void {
    if ($column === 'photo') {
        echo has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, [50, 50], ['style' => 'object-fit:cover;border-radius:4px;']) : '&mdash;';
    }
    if ($column === 'rating') {
        $rating = (int) get_field('rating', $post_id);
        echo $rating ? esc_html(str_repeat('★', $rating) . str_repeat('☆', 5 - $rating)) : '&mdash;';
    }
    if ($column === 'package') {
        $package = get_field('related_package', $post_id);
        echo $package ? esc_html(get_the_title($package)) : '&mdash;';
    }
}, 10, 2);
