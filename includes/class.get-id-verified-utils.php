<?php

/**
 * The file that defines the utils plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       laurendylam.com
 * @since      1.0.0
 *
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 */

/**
 * The utils plugin class.
 *
 * @since      1.0.0
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified_Utils {
    
  public static function get_upload_dir() {
    return wp_upload_dir()['basedir'] . '/'. GIV_IMAGE_FOLDER;
  }

  public static function get_upload_temp_dir() {
    return Get_Id_Verified_Utils::get_upload_dir() . "/.tmp";
  }

  public static function get_image_abs_path($id) {
    return Get_Id_Verified_Utils::get_upload_dir() . "/{$id}.jpg";
  }

  public static function get_image_abs_temp_path($id) {
    return Get_Id_Verified_Utils::get_upload_temp_dir() . "/{$id}.jpg";
  }

  public static function get_image_url_path($id, $tmp = false) {
    if (!$id) {
      return '';
    }
    return get_site_url() . "/wp-json/giv/v1/image/${id}?_wpnonce=" . wp_create_nonce( 'wp_rest' ) . ($tmp ? 'tmp=true' : '');
  }

  public static function user_is_verified($user_id = null) {
    $user_id = $user_id ? $user_id : get_current_user_id();

    if (!$user_id) {
      return false;
    }

    $verified = get_user_meta($user_id, GIV_USER_IS_VERIFIED, true);
    return $verified ? $verified : false;
  }

  public static function user_get_image_id($user_id = null) {
    $user_id =  $user_id ? $user_id : get_current_user_id();

    if (!$user_id) {
      return null;
    }

    return get_user_meta($user_id, GIV_IMAGE_UPLOADED_ID, true);
  }
  
	public static function rimraf($dir) {
		$files = array_diff(scandir($dir), ['.','..']);

		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}

		return rmdir($dir);
  }
  
  public static function remove_upload_temp_dir() {
    if (file_exists(Get_Id_Verified_Utils::get_upload_temp_dir())) {
      Get_Id_Verified_Utils::rimraf(Get_Id_Verified_Utils::get_upload_temp_dir());
		}
  }

  public static function create_upload_dir() {
    $upload_dir = Get_Id_Verified_Utils::get_upload_dir();

		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
    }
    
		file_put_contents($upload_dir . '/.htaccess', 'deny from all');
  }
}
