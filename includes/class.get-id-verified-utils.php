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

  /**
   * Geths the upload directory path.
   *
   * @since      1.0.0
   */
  public static function get_upload_dir() {
    return wp_upload_dir()['basedir'] . '/'. GIV_IMAGE_FOLDER;
  }

  /**
   * Gets the upload temp directory path.
   *
   * @since      1.0.0
   */
  public static function get_upload_temp_dir() {
    return Get_Id_Verified_Utils::get_upload_dir() . "/.tmp";
  }

  /**
   * Gets the image absolute path by id.
   *
   * @since      1.0.0
   */
  public static function get_image_abs_path($id) {
    return Get_Id_Verified_Utils::get_upload_dir() . "/{$id}.jpg";
  }

  /**
   * Gets the image absolute temporary path by id.
   *
   * @since      1.0.0
   */
  public static function get_image_abs_temp_path($id) {
    return Get_Id_Verified_Utils::get_upload_temp_dir() . "/{$id}.jpg";
  }

  /**
   * Gets the image url path with image id parameter.
   *
   * @since      1.0.0
   */
  public static function get_image_url_path($id, $tmp = false) {
    if (!$id) {
      return '';
    }

    return Get_Id_Verified_Utils::get_image_rest_url($id, [
      '_wpnonce' => wp_create_nonce( 'wp_rest' ),
      'tmp' => $tmp ? 'true' : ''
    ]);
  }

  /**
   * Gets the base image url path.
   *
   * @since      1.0.1
   */
  public static function get_image_rest_url($url = '', $params = []) {
    $base = get_rest_url(null, 'giv/v1/image' . ($url ? "/${url}" : ''));

    if (empty($params)) {
      return $base;
    }

    $symbol = strpos($base, '?rest_route=') === false ? '?' : '&';
    $paramsMap = [];

    foreach ($params as $key => $value) {
      if ($value !== '') {
        $keyEncoded = urlencode($key);
        $valueEncoded = urlencode($value);
        
        array_push($paramsMap, "${keyEncoded}=${valueEncoded}");
      }
    }

    return $base .= $symbol . implode("&", $paramsMap);
  }

  /**
   * Recursively remove a directory.
   *
   * @since      1.0.0
   */
	public static function rimraf($dir) {
		$files = array_diff(scandir($dir), ['.','..']);

		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}

		return rmdir($dir);
  }

  /**
   * Removes the temporary upload directory.
   *
   * @since      1.0.0
   */
  public static function remove_upload_temp_dir() {
    if (file_exists(Get_Id_Verified_Utils::get_upload_temp_dir())) {
      Get_Id_Verified_Utils::rimraf(Get_Id_Verified_Utils::get_upload_temp_dir());
		}
  }

  /**
   * Creates the upload directory and access a .htaccess file to
   * deny all direct access.
   *
   * @since      1.0.0
   */
  public static function create_upload_dir() {
    $upload_dir = Get_Id_Verified_Utils::get_upload_dir();

		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
    }
    
    /**
     * Don't remove this.
     */
		file_put_contents($upload_dir . '/.htaccess', 'deny from all');
  }
}
