<h3><?= __('Government Id Verification') ?></h3>

<?php if(Get_Id_Verified_Utils::user_is_verified()): ?>
  <p><?= __('You have been verified.') ?></p>
<?php else:
  $giv_image_upload_value = Get_Id_Verified_Utils::user_get_image_id();
  $giv_image_upload_src = Get_Id_Verified_Utils::get_image_url_path($giv_image_upload_value);
  include __DIR__ . '/image_uploader.php';
?>
<?php endif; ?>
