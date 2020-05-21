<?php if(!Get_Id_Verified_User::image()): ?>
  <div id="giv_checkout">
    <h3>
      <?= __('Upload Government Id') ?>
    </h3>

    <?php include __DIR__ . '/image_uploader.php'; ?>
  </div>
<?php endif; ?>