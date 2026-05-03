<?php
$heroTitle = $heroTitle ?? '';
$heroSubtitle = $heroSubtitle ?? '';
?>
<section class="page-hero text-white py-16">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-4xl md:text-5xl font-extrabold"><?= e($heroTitle) ?></h1>
    <?php if ($heroSubtitle !== ''): ?>
      <p class="text-blue-50 mt-3 max-w-2xl"><?= e($heroSubtitle) ?></p>
    <?php endif; ?>
  </div>
</section>
