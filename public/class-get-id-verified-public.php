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

	/**
	 * Displays the upload widget at the checkout form.
	 *
	 * @since    1.0.0
	 */
	public function add_upload_form_at_checkout( $checkout ) {
			include 'partials/upload_at_checkout.php';
	}

	/**
	 * Processes the checkout form validation of the id.
	 *
	 * @since    1.0.0
	 */
	public function process_checkout_validation() {
		if ($this->user_is_verified_or_has_image()) {
			return;
		}

		$image = $_POST[GIV_IMAGE_UPLOAD_NAME];
		$this->validate_temp($image);
	}

	/**
	 * Post process the checkout form and attaches the image to the
	 * user or order depending on if it's a express checkout.
	 *
	 * @since    1.0.0
	 */
	public function process_checkout_order( $order_id ) {
		if ($this->user_is_verified_or_has_image()) {
			return;
		}

		$order = wc_get_order($order_id);
		$image = sanitize_text_field($_POST[GIV_IMAGE_UPLOAD_NAME]);

		/**
		 * If the user is uploading the image for the first time
		 * or is creating an account, attach the image to their user.
		 */
		if ($order->get_user() !== false) {
			Get_Id_Verified_User::set_image($image, $order->get_user_id());
		} else {
			/**
			 * For express checkout, attach the image to the order.
			 */
			$order->update_meta_data(GIV_IMAGE_UPLOADED_ID, $image);
			$order->save();
		}

		rename(
			Get_Id_Verified_Utils::get_image_abs_temp_path($image), 
			Get_Id_Verified_Utils::get_image_abs_path($image)
		);
	}

	/**
	 * If the user is authenticated, and they are verified or already have an uploaded.
	 * Used for processing at checkout. They may only change the id in their account page.
	 *
	 * @since    1.0.0
	 */
	private function user_is_verified_or_has_image() {
		return Get_Id_Verified_User::verified() || Get_Id_Verified_User::image();
	}

	/**
	 * Display the upload widget in the account details page.
	 *
	 * @since    1.0.0
	 */
	public function add_upload_form_in_account_details() {
		include 'partials/account_details.php';
	}

	/**
	 * Processes the image in the account details page.
	 *
	 * @since    1.0.0
	 */
	public function process_account_edit_form($id) {
		if (Get_Id_Verified_User::verified()) {
			return;
		}
		
		$image = sanitize_text_field($_POST[GIV_IMAGE_UPLOAD_NAME] ?? '');

		if ($image === Get_Id_Verified_User::image()) {
			return;
		}

		if (!$this->validate_temp($image)) {
			return;
		}

		Get_Id_Verified_User::set_image($image);

		rename(
			Get_Id_Verified_Utils::get_image_abs_temp_path($image), 
			Get_Id_Verified_Utils::get_image_abs_path($image)
		);
	}

	/**
	 * Validates the temporary uploaded image.
	 *
	 * @since    1.0.0
	 */
	private function validate_temp($image) {
		if (!$image || !file_exists(Get_Id_Verified_Utils::get_image_abs_temp_path($image))) {

			$error = apply_filters('giv_add_validation_notice', __( 'Please upload a verification id.', $this->plugin_name));
			wc_add_notice($error, 'error');
			
			return 0;
		}
		return 1;
	}

	/**
	 * Displays a notice at the checkout to ensure the user get verified.
	 *
	 * @since    1.0.0
	 */
	public function add_notice_for_verified() {
		if (!Get_Id_Verified_User::verified()) {
			$notice = apply_filters('giv_add_checkout_notice', __("Please allow 24 hours for verification id to be complete and your order will be processed.", $this->plugin_name));
			
			if ($notice) {
				wc_add_notice($notice, 'notice');
			}
		}
	}

}
