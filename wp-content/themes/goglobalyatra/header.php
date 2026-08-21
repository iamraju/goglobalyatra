<?php
/**
 * Header: topbar + sticky nav.
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="brand-gradient text-white text-sm">
  <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-end gap-4">
    <a href="tel:<?php echo esc_attr(goglobalyatra_option('topbar_phone')); ?>" class="hover:underline inline-flex items-center gap-1.5">
      <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.24.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2Z"/></svg>
      <?php echo esc_html(goglobalyatra_option('topbar_phone')); ?>
    </a>
    <a href="mailto:<?php echo esc_attr(goglobalyatra_option('topbar_email')); ?>" class="hover:underline inline-flex items-center gap-1.5">
      <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm2 0 8 6 8-6H4Zm16 2.24-7.4 5.55a1 1 0 0 1-1.2 0L4 8.24V18h16V8.24Z"/></svg>
      <?php echo esc_html(goglobalyatra_option('topbar_email')); ?>
    </a>
  </div>
</div>

<header class="bg-white/95 border-b border-slate-200 sticky top-0 z-50 backdrop-blur">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
    <?php if (has_custom_logo()) : ?>
      <div class="flex-shrink-0"><?php the_custom_logo(); ?></div>
    <?php else : ?>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="flex-shrink-0 text-xl font-extrabold text-blue-900" aria-label="<?php bloginfo('name'); ?> home"><?php bloginfo('name'); ?></a>
    <?php endif; ?>

    <button data-menu-button class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded border border-slate-300" aria-label="Toggle menu">
      <span class="text-xl">&#9776;</span>
    </button>

    <nav class="hidden md:block" aria-label="Main navigation">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'menu_class' => 'flex items-center gap-7 font-semibold text-slate-700',
          'fallback_cb' => 'goglobalyatra_default_menu',
      ]);
      ?>
    </nav>
  </div>

  <nav data-mobile-menu class="hidden md:hidden border-t border-slate-200 bg-white" aria-label="Mobile navigation">
    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'px-4 py-3 space-y-3 font-medium text-slate-700',
        'fallback_cb' => 'goglobalyatra_default_menu',
    ]);
    ?>
  </nav>
</header>

<?php
/** Fallback nav when no menu is assigned in Appearance > Menus. */
function goglobalyatra_default_menu(): void
{
    $items = [
        ['url' => home_url('/'), 'label' => 'Home'],
        ['url' => home_url('/inbound-packages/'), 'label' => 'Inbound Packages'],
        ['url' => home_url('/outbound-packages/'), 'label' => 'Outbound Packages'],
        ['url' => home_url('/testimonials/'), 'label' => 'Testimonials'],
        ['url' => home_url('/about/'), 'label' => 'About'],
        ['url' => home_url('/contact/'), 'label' => 'Contact'],
    ];
    echo '<ul class="flex flex-wrap items-center gap-6 font-semibold text-slate-700">';
    foreach ($items as $item) {
        echo '<li><a class="hover:text-blue-700" href="' . esc_url($item['url']) . '">' . esc_html($item['label']) . '</a></li>';
    }
    echo '</ul>';
}
