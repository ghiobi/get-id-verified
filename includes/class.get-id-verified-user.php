<?php

/**
 * The file that defines the user plugin class
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
 * The user plugin class.
 *
 * @since      1.0.0
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified_User {

  /**
   * Checks if the user is verfied.
   *
   * @since      1.0.0
   */
  public static function verified($user_id = null) {
    $user_id = $user_id ?: get_current_user_id();

    if (!$user_id) {
      return false;
    }

    $verified = get_user_meta($user_id, GIV_USER_IS_VERIFIED, true);
    return $verified ?: false;
  }

  /**
   * Returns the image id.
   *
   * @since      1.0.0
   */
  public static function image($user_id = null) {
    $user_id =  $user_id ?: get_current_user_id();

    if (!$user_id) {
      return null;
    }

    return get_user_meta($user_id, GIV_IMAGE_UPLOADED_ID, true);
  }

  /**
   * Sets the image id.
   *
   * @since      1.0.0
   */
  public static function set_image($image, $user_id = null) {
		update_user_meta($user_id ?? get_current_user_id(), GIV_IMAGE_UPLOADED_ID, $image);
  }

  /**
   * Sets if the user is verified.
   *
   * @since      1.0.0
   */
  public static function set_verified($verified, $user_id) {
    update_user_meta($user_id, GIV_USER_IS_VERIFIED, $verified);
  }
  
}
