<?php

/**
 * The file that defines the core plugin class
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
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Get_Id_Verified_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GET_ID_VERIFIED_VERSION' ) ) {
			$this->version = GET_ID_VERIFIED_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'get-id-verified';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_rest();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Get_Id_Verified_Loader. Orchestrates the hooks of the plugin.
	 * - Get_Id_Verified_i18n. Defines internationalization functionality.
	 * - Get_Id_Verified_Admin. Defines all hooks for the admin area.
	 * - Get_Id_Verified_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-get-id-verified-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-get-id-verified-i18n.php';

		/**
		 * The class responsible for defining rest functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.get-id-verified-rest.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-get-id-verified-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-get-id-verified-public.php';

		$this->loader = new Get_Id_Verified_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Get_Id_Verified_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Get_Id_Verified_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Define the rest api for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_rest() {
		$plugin_rest = new Get_Id_Verified_Rest();

		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Get_Id_Verified_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'add_edit_user_profile_form' );
		$this->loader->add_action( 'profile_update', $plugin_admin, 'process_verification_user_profile' );
		$this->loader->add_action( 'manage_edit-shop_order_columns', $plugin_admin, 'add_to_column_header' );
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'add_order_column_content' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_meta_boxes' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$this->loader->add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ), $plugin_admin, 'add_action_links' );
	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Get_Id_Verified_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_public, 'add_upload_form_at_checkout' );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'process_checkout_validation' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'process_checkout_order' );
		$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'add_upload_form_in_account_details' );
		$this->loader->add_action( 'woocommerce_save_account_details', $plugin_public, 'process_account_edit_form' );
		$this->loader->add_action( 'woocommerce_checkout_before_customer_details', $plugin_public, 'add_notice_for_verified' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Get_Id_Verified_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
