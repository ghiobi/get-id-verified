<?php

/**
 * The file that defines the rest plugin class
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
 * The reset plugin class.
 *
 * @since      1.0.0
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified_Rest {

  /**
   * Registers the routes for the controllers below.
   * 
   * @since     1.0.0
   */
  public function register_routes() {
    register_rest_route( 'giv/v1', 'image/(?P<id>[^\s]+)', [ 'methods' => 'GET', 'callback' => [$this, 'retrieve_id_image'] ]);
    register_rest_route( 'giv/v1', 'image', [ 'methods' => 'POST', 'callback' => [$this, 'upload_id_image'] ]);
  }

  /**
   * Rest controller for retrieving the image.
   * 
   * @since     1.0.0
   */
  public function retrieve_id_image($data) {
    if (!$data['id']) {
      return $this->throw_rest_error(403);
    }

    $tmp = $data->get_param('tmp') === 'true' ? true : false;

    /**
     * All temporary images don't need to be authenticated.
     */
    if (!$tmp) {
      if (!is_user_logged_in()) {
        return $this->throw_rest_error(401);
      }
      /**
       * When the use is not administrator and the user is accessing someone elses' 
       * image. If it's not their image, an authentication error is thrown.
       */
      if (!current_user_can('administrator') && Get_Id_Verified_User::image() !== $data['id']) {
        return $this->throw_rest_error(401);
      }
    }
    
    $path = $tmp ? 
      Get_Id_Verified_Utils::get_image_abs_temp_path($data['id']) : 
      Get_Id_Verified_Utils::get_image_abs_path($data['id']);
    
    if (!file_exists($path)) {
      return $this->throw_rest_error(404);
    }

    header('Content-Type: image/jpg');
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
    header('Expires: January 01, 1970');
    readfile($path);
    exit();
  }

  /**
   * Rest controller for uploading an image. Initially the image is stored in a
   * temporary folder. Once it is saved it will be moved to the upload folder
   * and only authenticated users may see their image.
   * 
   * @since     1.0.0
   */
  public function upload_id_image() {
    $target_dir = Get_Id_Verified_Utils::get_upload_temp_dir();

    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    } 

    $target_file = $target_dir . basename($_FILES["giv_upload_image"]["name"]);
    $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    if(!getimagesize($_FILES["giv_upload_image"]["tmp_name"])) {
      return $this->throw_rest_error(400);
    }
    
    if ($_FILES["giv_upload_image"]["size"] > 15000000) { // 15mb
      return $this->throw_rest_error(400, 'Image is too big, should be less than 15mbs.');
    }
    
    if(
      $ext !== "jpg" &&
      $ext !== "png" &&
      $ext !== "jpeg"
    ) {
      return $this->throw_rest_error(400, "Sorry, only JPG, JPEG, & PNG files are allowed.");
    }
    
    $id = wp_generate_password(64, false);

    if ($this->convert_n_compress_image(
      $_FILES["giv_upload_image"]["tmp_name"], 
      Get_Id_Verified_Utils::get_image_abs_temp_path($id), 
      $ext
    )) {
      return $id;
    }

    return $this->throw_rest_error(500, "Sorry, there was an error uploading your file.");
  }

  /**
   * Compresses the image down to 500kb and moves the original image 
   * to the output location.
   * 
   * @since     1.0.0
   */
  private function convert_n_compress_image($original, $output, $ext){
    $max = 500000; // 500kb

    if (filesize($original) <= $max) {
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
  
  /**
   * Throws a rest error.
   * 
   * @since     1.0.0
   */
  private function throw_rest_error($code, $message = '') {
    return new WP_Error( 'rest_invalid', $message, ['status' => $code ]);
  }

}
