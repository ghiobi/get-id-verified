<h3><?= __('Government Id Verification') ?></h3>

<?php if(giv_user_is_verified()): ?>
  <p><?= __('You have been verified.') ?></p>
<?php else:
  $giv_image_upload_src = giv_get_image_url_path(giv_user_get_image_id());
  $giv_image_upload_value = giv_user_get_image_id();
  include __DIR__ . '/image_uploader.php';
?>
<?php endif; ?>
