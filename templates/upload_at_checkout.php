<?php if(!giv_user_get_image_id()): ?>
  <div id="giv_checkout">
    <h3>
      <?= __('Upload Government Id') ?>
    </h3>

    <?php include __DIR__ . '/image_uploader.php'; ?>
  </div>
<?php endif; ?>