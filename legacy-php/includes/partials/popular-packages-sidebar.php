<?php
$config = $config ?? ($GLOBALS['config'] ?? []);
$sidebarTitle = $sidebarTitle ?? 'Popular Packages';
$sidebarPopularPackages = $sidebarPopularPackages ?? array_slice(active_packages($config, null, true), 0, 5);
?>
<aside class="space-y-4">
  <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5">
    <h2 class="text-lg font-bold text-slate-900"><?= e($sidebarTitle) ?></h2>
    <p class="text-sm text-slate-600 mt-1">Trending tours travelers ask for most.</p>
  </div>

  <?php foreach ($sidebarPopularPackages as $package): ?>
    <article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
      <a href="<?= e(package_details_url((string) $package['slug'])) ?>" class="block">
        <div class="h-36 overflow-hidden">
          <img src="<?= e((string) $package['image_url']) ?>" alt="<?= e((string) $package['title']) ?>" class="w-full h-full object-cover" />
        </div>
        <div class="p-4">
          <h3 class="text-base font-bold text-slate-900"><?= e((string) $package['title']) ?></h3>
          <p class="text-sm text-slate-600 mt-1"><?= e((string) $package['duration']) ?></p>
          <span class="inline-flex mt-3 px-3 py-1.5 rounded-lg brand-gradient text-white text-sm font-medium">View Details</span>
        </div>
      </a>
    </article>
  <?php endforeach; ?>
</aside>
