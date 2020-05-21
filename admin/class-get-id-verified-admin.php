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

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/get-id-verified-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Register the meta box in the shop order page.
	 *
	 * @since    1.0.0
	 */
	public function register_meta_boxes() {

		add_meta_box( 'giv-government-id-verification', __('Government Id'), [ $this, 'add_user_order_verification_meta_box'], 'shop_order', 'side', 'low' );

	}

	/**
	 * Displays the partial profile form for the user.
	 *
	 * @since    1.0.0
	 */
	public function add_edit_user_profile_form($profileuser) {
		include 'partials/edit_user_profile.php';
	}

	/**
	 * Process the profile form for the user.
	 *
	 * @since    1.0.0
	 */
	public function process_verification_user_profile($user_id) {
		if (!is_admin()) {
			return;
		}

		Get_Id_Verified_User::set_verified(isset($_POST[GIV_USER_IS_VERIFIED_CHECKBOX]), $user_id);
	}
		
	/**
	 * Displays the column header on the orders page.
	 */
	/**
	 * Register the meta box in the shop order.
	 *
	 * @since    1.0.0
	 */
	public function add_to_column_header($columns) {
		$columns['giv_column'] = __('Government Id');

		return $columns;
	}

	/**
	 * Displays the verified or not in the column on the orders page.
	 *
	 * @since    1.0.0
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
		
		include 'partials/user_is_verified.php';
	}

	/**
	 * Displays the meta box in the shop order.
	 *
	 * @since    1.0.0
	 */
	public function add_user_order_verification_meta_box() {
		global $post;
		$order = wc_get_order($post->ID);
		
		include 'partials/order_verification_meta_box.php';
	}

}
