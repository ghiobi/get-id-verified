<?php

/**
 * Plugin Name: Get Id Verified
 * Description: This plugin contains implementations to get government id verification with woocommerce.
 * Author: Ghiobi
 * Version: 0.1
 */

define('GIV_IMAGE_FOLDER', 'giv_uploads');
define('GIV_IMAGE_UPLOAD_NAME', 'giv_upload_image_name');
define('GIV_IMAGE_UPLOADED_ID', 'giv_uploaded_image_id');
define('GIV_USER_IS_VERIFIED', 'giv_user_is_verified');
define('GIV_USER_IS_VERIFIED_CHECKBOX', 'giv_verified_checkbox');
define('GIV_CRON', 'giv_cron_hook');

function giv_get_upload_dir() {
  return wp_upload_dir()['basedir'] . '/'. GIV_IMAGE_FOLDER;
}

function giv_get_upload_temp_dir() {
  return giv_get_upload_dir() . "/.tmp";
}

function giv_get_image_abs_path($id) {
  return giv_get_upload_dir() . "/{$id}.jpg";
}

function giv_get_image_abs_temp_path($id) {
  return giv_get_upload_temp_dir() . "/{$id}.jpg";
}

function giv_get_image_url_path($id, $tmp = false) {
  if (!$id) {
    return '';
  }
  return get_site_url() . "/wp-json/giv/v1/image/${id}?_wpnonce=" . wp_create_nonce( 'wp_rest' ) . ($tmp ? 'tmp=true' : '');
}

function giv_user_is_verified($user_id = null) {
  $user_id = $user_id ? $user_id : get_current_user_id();

  if (!$user_id) {
    return false;
  }

  $verified = get_user_meta($user_id, GIV_USER_IS_VERIFIED, true);
  return $verified ? $verified : false;
}

function giv_user_get_image_id($user_id = null) {
  $user_id =  $user_id ? $user_id : get_current_user_id();

  if (!$user_id) {
    return null;
  }

  return get_user_meta($user_id, GIV_IMAGE_UPLOADED_ID, true);
}

function giv_add_upload_form_at_checkout( $checkout ) {
    include __DIR__ . '/templates/upload_at_checkout.php';
}
add_action( 'woocommerce_after_order_notes', 'giv_add_upload_form_at_checkout' );

function giv_process_checkout_validation() {
  if (giv_user_is_verified()) {
    return;
  }

  $image = $_POST[GIV_IMAGE_UPLOAD_NAME];

  if (!$image || !file_exists(giv_get_image_abs_temp_path($image))) {
    wc_add_notice( __( 'Please upload a government id.' ), 'error' );
  }
}
add_action('woocommerce_checkout_process', 'giv_process_checkout_validation');

function giv_process_checkout_order( $order_id ) {
  if (giv_user_is_verified()) {
    return;
  }

  $order = wc_get_order($order_id);
  $image = sanitize_text_field($_POST[GIV_IMAGE_UPLOAD_NAME]);

  if (is_user_logged_in() || (($_POST['createaccount'] ?? 0) === '1')) {
    update_user_meta($order->get_user_id(), GIV_IMAGE_UPLOADED_ID, $image);
  } else {
    $order->update_meta_data(GIV_IMAGE_UPLOADED_ID, $image);
    $order->save();
  }

  rename(giv_get_image_abs_temp_path($image), giv_get_image_abs_path($image));
}
add_action( 'woocommerce_checkout_update_order_meta', 'giv_process_checkout_order' );

function giv_add_to_account_edit_form() {
  include __DIR__ . '/templates/account_details.php';
}
add_action('woocommerce_edit_account_form', 'giv_add_to_account_edit_form');

function giv_process_account_edit_form($id) {
  $image = sanitize_text_field($_POST[GIV_IMAGE_UPLOAD_NAME]);

  if ($image === giv_user_get_image_id()) {
    return;
  }

  if (!$image || !file_exists(giv_get_image_abs_temp_path($image))) {
    wc_add_notice( __( 'Please upload a government id.' ), 'error' );
    return;
  }

  update_user_meta(get_current_user_id(), GIV_IMAGE_UPLOADED_ID, $image);
  rename(giv_get_image_abs_temp_path($image), giv_get_image_abs_path($image));
}
add_action('woocommerce_save_account_details', 'giv_process_account_edit_form');

function giv_add_edit_user_profile_form($profileuser) {
  include __DIR__ . '/templates/edit_user_profile.php';
}
add_action('edit_user_profile', 'giv_add_edit_user_profile_form');

function giv_process_verification_user_profile($user_id) {
  if (!is_admin()) {
    return;
  }

  if (!isset($_POST[GIV_USER_IS_VERIFIED_CHECKBOX])) {
    update_user_meta($user_id, GIV_USER_IS_VERIFIED, false);
  } else {
    update_user_meta($user_id, GIV_USER_IS_VERIFIED, true);
  }
}
add_action('profile_update', 'giv_process_verification_user_profile');

/**
 * Add ID Verified Column to Orders Page
 */
function giv_add_to_column_header($columns) {
  $columns['giv_column'] = __('Government Id');

  return $columns;
}
add_filter('manage_edit-shop_order_columns', 'giv_add_to_column_header', 20);

/**
 * Add Verified or Not Verified to ID Verified Column
 */
function giv_add_order_column_content($column) {
  if ('giv_column' !== $column) {
    return;
  }

  global $post;
  $order = wc_get_order($post->ID);

  if ($order->get_user() === false) {
    echo 'Guest Checkout';
    return;
  }
  
  if (giv_user_is_verified($order->get_user_id())) {
    echo '<div class="giv_badge giv_badge_success">' . __('Verified') . '</div>';
  } else {
    echo '<a href="' . get_edit_user_link($order->get_user_id()) . '#giv_edit_profile" target="_new">Verify User</a>';
  }
}
add_action('manage_shop_order_posts_custom_column', 'giv_add_order_column_content');

/**
 * Displays a notice at the checkout to ensure the user get verified.
 */
function giv_add_notice_for_verified() {
  if (!giv_user_is_verified()) {
    wc_add_notice(__("Please allow 24 hours for government id verification to be complete. And your order will be processed."), 'notice');
  }
}
add_action( 'woocommerce_checkout_before_customer_details', 'giv_add_notice_for_verified');

function giv_add_user_is_verified_on_order_details($order) {
  echo '<h3>Government Id</h3>';
  if ($order->get_user() === false) {
    $image = $order->get_meta(GIV_IMAGE_UPLOADED_ID);
    echo '<img style="max-width: 100%" src="' . giv_get_image_url_path($image) . '">';
    return;
  }
  if (giv_user_is_verified($order->get_user_id())) {
    echo '<div class="giv_badge giv_badge_success">' . __('Verified') . '</div>';
  } else {
    echo '<div><a href="' . get_edit_user_link($order->get_user_id()) . '#giv_edit_profile" target="_new">' . __('Verify User') . '</a></div>';
  }
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'giv_add_user_is_verified_on_order_details');

function giv_throw_rest_error($code, $message = '') {
  return new WP_Error( 'rest_invalid', $message, ['status' => $code ]);
}

/**
 *  To get the image.
 */
function giv_retrieve_id_image($data) {
  if (!$data['id']) {
    return giv_throw_rest_error(403);
  }

  $tmp = $data->get_param('tmp') ? true : false;

  if (!$tmp) {
    if (!is_user_logged_in()) {
      return giv_throw_rest_error(401);
    }
    if (!current_user_can('administrator') && giv_user_get_image_id() !== $data['id']) {
      return giv_throw_rest_error(401);
    }
  }
  
  $path = $tmp ? 
    giv_get_image_abs_temp_path($data['id']) : 
    giv_get_image_abs_path($data['id']);
  
  if (!file_exists($path)) {
    return giv_throw_rest_error(404);
  }

  header('Content-Type: image/jpg');
  header('Content-Length: ' . filesize($path));
  header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
  header('Expires: January 01, 1970');
  readfile($path);
  exit();
}

function giv_convert_n_compress_image($original, $output, $ext){
  $max = 500000; // 500kb

  if (filesize($original) <= $max && $ext === 'ext') {
    return move_uploaded_file($original, $output);
  }

  $temp = null;

  if (preg_match('/jpg|jpeg/i', $ext))
    $temp = imagecreatefromjpeg($original);
  else if (preg_match('/png/i', $ext))
    $temp = imagecreatefrompng($original);

  $quality = 100;

  do {
    $quality -= 5;
    $stream = fopen('php://temp', 'w+');
    imagejpeg($temp, $stream, $quality);

    rewind($stream);
    $fstat = fstat($stream);
    fclose($stream);

    $size = $fstat['size'];
  }
  while (($size > $max) && ($quality >= 0));

  imagejpeg($temp, $output, $quality + 5);
  imagedestroy($temp);

  return 1;
}

function giv_upload_id_image() {
  $target_dir = giv_get_upload_temp_dir();

  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  } 

  $target_file = $target_dir . basename($_FILES["giv_upload_image"]["name"]);
  $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  
  if(!getimagesize($_FILES["giv_upload_image"]["tmp_name"])) {
    return giv_throw_rest_error(400);
  }
  
  if ($_FILES["giv_upload_image"]["size"] > 15000000) { // 15mb
    return giv_throw_rest_error(400, 'Image is too big, should be less than 15mbs.');
  }
  
  if(
    $ext !== "jpg" &&
    $ext !== "png" &&
    $ext !== "jpeg"
  ) {
    return giv_throw_rest_error(400, "Sorry, only JPG, JPEG, & PNG files are allowed.");
  }
  
  $id = wp_generate_uuid4();

  if (giv_convert_n_compress_image(
    $_FILES["giv_upload_image"]["tmp_name"], 
    giv_get_image_abs_temp_path($id), 
    $ext
  )) {
    return $id;
  }

  return giv_throw_rest_error(500, "Sorry, there was an error uploading your file.");
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'giv/v1', 'image/(?P<id>[^\s]+)', [ 'methods' => 'GET', 'callback' => 'giv_retrieve_id_image' ]);
  register_rest_route( 'giv/v1', 'image', [ 'methods' => 'POST', 'callback' => 'giv_upload_id_image' ]);
});

function giv_load_assets() {
  wp_enqueue_style('giv_styles', plugins_url('style.css', __FILE__), false);
  wp_enqueue_script('giv_scripts', plugins_url('script.js', __FILE__), false);
}
add_action('wp_enqueue_scripts', 'giv_load_assets', 50);

function giv_load_admin_assets() {
  wp_enqueue_style('giv_styles', plugins_url('admin.css', __FILE__), false);
}
add_action('admin_enqueue_scripts', 'giv_load_admin_assets');

function giv_rimraf($dir) {
  $files = array_diff(scandir($dir), ['.','..']);

  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  }

  return rmdir($dir);
}

function giv_activate_cron() {
  if( !wp_next_scheduled( GIV_CRON ) ) {
    wp_schedule_event( time(), 'daily', GIV_CRON );
  }
}

function giv_activate() {
  giv_activate_cron();

  $upload_dir = giv_get_upload_dir();
  if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }

  file_put_contents($upload_dir . '/.htaccess', 'deny from all');
}
register_activation_hook(__FILE__, 'giv_activate' );

function giv_cron_exec() {
  if (file_exists(giv_get_upload_temp_dir())) {
    giv_rimraf(giv_get_upload_temp_dir());
  }
  giv_activate_cron();
}
add_action(GIV_CRON, 'giv_cron_exec');

function giv_deactivate() {
    $timestamp = wp_next_scheduled(GIV_CRON);
    wp_unschedule_event( $timestamp, GIV_CRON);
}
register_deactivation_hook( __FILE__, 'giv_deactivate' ); 
