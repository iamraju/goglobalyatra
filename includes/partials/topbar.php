<?php $config = $config ?? ($GLOBALS['config'] ?? []); ?>
<div class="brand-gradient text-white text-sm">
  <div class="max-w-7xl mx-auto px-4 py-2 flex flex-col md:flex-row justify-between gap-2">
    <p>Phone: <?= e($config['site']['topbar_phone']) ?></p>
    <p>Email: <?= e($config['site']['topbar_email']) ?></p>
  </div>
</div>
