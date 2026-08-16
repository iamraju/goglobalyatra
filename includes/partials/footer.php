<?php $config = $config ?? ($GLOBALS['config'] ?? []); ?>
<footer class="bg-slate-900 text-slate-200">
  <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-3 gap-8">
    <section>
      <img src="<?= e($config['site']['logo']) ?>" alt="<?= e($config['site']['name']) ?> logo" class="h-12 w-auto bg-white rounded p-1" />
      <p class="mt-4 text-slate-300 text-sm leading-7">
        <?= e($config['site']['name']) ?> is a trusted travel company offering inbound Nepal packages and outbound global tours with personalized support, transparent pricing, and memorable experiences.
      </p>
    </section>

    <section>
      <h2 class="font-semibold text-lg text-white">Quick Links</h2>
      <ul class="mt-4 space-y-2 text-sm">
        <?php foreach (nav_items() as $item): ?>
          <li><a href="<?= e($item['url']) ?>" class="hover:text-white"><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section>
      <h2 class="font-semibold text-lg text-white">Contact Details</h2>
      <ul class="mt-4 space-y-2 text-sm">
        <li><strong>Company:</strong> <?= e($config['site']['name']) ?></li>
        <li><strong>Address:</strong> <?= e($config['site']['address']) ?></li>
        <li><strong>Email:</strong> <?= e($config['site']['topbar_email']) ?></li>
        <li><strong>Mobile:</strong> <?= e($config['site']['topbar_phone']) ?></li>
        <li><strong>Landline:</strong> <?= e($config['site']['topbar_landline']) ?></li>
      </ul>
    </section>
  </div>

  <div class="border-t border-slate-800 py-4 text-center text-sm text-slate-400">
    &copy; <span data-year></span> <?= e($config['site']['name']) ?>. All rights reserved.
  </div>
</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
