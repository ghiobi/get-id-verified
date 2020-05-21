<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       laurendylam.com
 * @since      1.0.0
 *
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/admin
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/get-id-verified-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/get-id-verified-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_edit_user_profile_form($profileuser) {
		include __DIR__ . '/partials/edit_user_profile.php';
	}

	public function process_verification_user_profile($user_id) {
		if (!is_admin()) {
			return;
		}

		if (!isset($_POST[GIV_USER_IS_VERIFIED_CHECKBOX])) {
			update_user_meta($user_id, GIV_USER_IS_VERIFIED, false);
		} else {
			update_user_meta($user_id, GIV_USER_IS_VERIFIED, true);
		}
	}
		
	/**
	 * Add ID Verified Column to Orders Page
	 */
	public function add_to_column_header($columns) {
		$columns['giv_column'] = __('Government Id');

		return $columns;
	}

	/**
	 * Add Verified or Not Verified to ID Verified Column
	 */
	public function add_order_column_content($column) {
		if ('giv_column' !== $column) {
			return;
		}

		global $post;
		$order = wc_get_order($post->ID);

		if ($order->get_user() === false) {
			echo __('Guest Checkout');
			return;
		}
		
		$this->display_user_is_verified($order->get_user_id());
	}

	public function add_user_is_verified_on_order_details($order) {
		echo '<h3>' .__('Government Id') . '</h3>';

		if ($order->get_user() === false) {
			$image = $order->get_meta(GIV_IMAGE_UPLOADED_ID);
			echo '<img style="max-width: 100%" src="' . Get_Id_Verified_Utils::get_image_url_path($image) . '">';
			return;
		}

		$this->display_user_is_verified($order->get_user_id());
	}

	private function display_user_is_verified($user_id) {
		if (Get_Id_Verified_Utils::user_is_verified($user_id)) {
			echo '<div class="giv_badge giv_badge_success">' . __('Verified') . '</div>';
		} else {
			echo '<a href="' . get_edit_user_link($user_id) . '#giv_edit_profile" target="_new">' . __('Verify User') . '</a>';
		}
	}
	
}
