<?php
$config = $config ?? ($GLOBALS['config'] ?? []);
$currentPage = $currentPage ?? 'home';
$navItems = nav_items();
?>
<header class="bg-white/95 border-b border-slate-200 sticky top-0 z-50 backdrop-blur">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
    <a href="/" class="flex items-center gap-3" aria-label="<?= e($config['site']['name']) ?> home">
      <img src="<?= e($config['site']['logo']) ?>" alt="<?= e($config['site']['name']) ?> logo" class="h-12 w-auto" />
    </a>

    <button data-menu-button class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded border border-slate-300" aria-label="Toggle menu">
      <span class="text-xl">&#9776;</span>
    </button>

    <nav class="hidden md:block" aria-label="Main navigation">
      <ul class="flex items-center gap-7 font-semibold text-slate-700">
        <?php foreach ($navItems as $key => $item): ?>
          <li>
            <a href="<?= e($item['url']) ?>" class="<?= $currentPage === $key ? 'text-blue-700' : 'hover:text-blue-700' ?>"><?= e($item['label']) ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>

  <nav data-mobile-menu class="hidden md:hidden border-t border-slate-200 bg-white" aria-label="Mobile navigation">
    <ul class="px-4 py-3 space-y-3 font-medium text-slate-700">
      <?php foreach ($navItems as $key => $item): ?>
        <li>
          <a href="<?= e($item['url']) ?>" class="<?= $currentPage === $key ? 'text-blue-700' : '' ?>"><?= e($item['label']) ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</header>
