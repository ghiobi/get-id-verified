<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       laurendylam.com
 * @since      1.0.0
 *
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/public
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Get_Id_Verified_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Get_Id_Verified_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/get-id-verified-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Get_Id_Verified_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Get_Id_Verified_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/get-id-verified-public.js', array( 'jquery' ), $this->version, false );

	}

	public function add_upload_form_at_checkout( $checkout ) {
			include 'partials/upload_at_checkout.php';
	}

	public function process_checkout_validation() {
		if ($this->is_verified_or_checking()) {
			return;
		}

		$image = $_POST[GIV_IMAGE_UPLOAD_NAME];

		if (!$image || !file_exists(Get_Id_Verified_Utils::get_image_abs_temp_path($image))) {
			wc_add_notice( __( 'Please upload a government id.' ), 'error' );
		}
	}

	public function process_checkout_order( $order_id ) {
		if ($this->is_verified_or_checking()) {
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

		rename(
			Get_Id_Verified_Utils::get_image_abs_temp_path($image), 
			Get_Id_Verified_Utils::get_image_abs_path($image)
		);
	}

	private function is_verified_or_checking() {
		return Get_Id_Verified_Utils::user_is_verified() || Get_Id_Verified_Utils::user_get_image_id();
	}

	public function add_upload_form_in_account_details() {
		include 'partials/account_details.php';
	}

	public function process_account_edit_form($id) {
		if (Get_Id_Verified_Utils::user_is_verified()) {
			return;
		}
		
		$image = sanitize_text_field($_POST[GIV_IMAGE_UPLOAD_NAME]);

		if ($image === Get_Id_Verified_Utils::user_get_image_id()) {
			return;
		}

		if (!$image || !file_exists(Get_Id_Verified_Utils::get_image_abs_temp_path($image))) {
			wc_add_notice( __( 'Please upload a government id.' ), 'error' );
			return;
		}

		update_user_meta(get_current_user_id(), GIV_IMAGE_UPLOADED_ID, $image);
		rename(
			Get_Id_Verified_Utils::get_image_abs_temp_path($image), 
			Get_Id_Verified_Utils::get_image_abs_path($image)
		);
	}
		
	/**
	 * Displays a notice at the checkout to ensure the user get verified.
	 */
	public function add_notice_for_verified() {
		if (!Get_Id_Verified_Utils::user_is_verified()) {
			wc_add_notice(__("Please allow 24 hours for government id verification to be complete. And your order will be processed."), 'notice');
		}
	}

}
