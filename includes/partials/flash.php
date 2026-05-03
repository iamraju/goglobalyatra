<?php $flash = flash_get(); ?>
<?php if ($flash): ?>
  <section class="max-w-7xl mx-auto px-4 pt-6">
    <div class="rounded-lg px-4 py-3 text-sm <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-900 border border-green-200' : 'bg-red-100 text-red-900 border border-red-200' ?>">
      <?= e($flash['message']) ?>
    </div>
  </section>
<?php endif; ?>
