<?php if(!Get_Id_Verified_User::image()): ?>
  <div id="giv_checkout">
    <h3>
      <?= __('Upload Government Id', $this->plugin_name) ?>
    </h3>

    <?php include 'image_uploader.php'; ?>
  </div>
<?php endif; ?>