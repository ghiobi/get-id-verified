<?php if ($order->get_user() === false):
  $image = $order->get_meta(GIV_IMAGE_UPLOADED_ID);
  $url = Get_Id_Verified_Utils::get_image_url_path($image) ?>
  <?php include 'image_preview.php'; ?>
<?php else: ?>
  <?php include 'user_is_verified.php'; ?>
<?php endif; ?>