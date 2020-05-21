<h3><?= apply_filters('giv_upload_widget_header', __('Verification Id', $this->plugin_name)); ?></h3>

<?php if(Get_Id_Verified_User::verified()): ?>
  <p><?= __('You have been verified.', $this->plugin_name) ?></p>
<?php else:  
  $giv_image_upload_value = Get_Id_Verified_User::image();
  $giv_image_upload_src = Get_Id_Verified_Utils::get_image_url_path($giv_image_upload_value);
  include  'image_uploader.php';
?>
<?php endif; ?>
