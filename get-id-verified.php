<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              laurendylam.com
 * @since             1.0.0
 * @package           Get_Id_Verified
 *
 * @wordpress-plugin
 * Plugin Name:       Get Id Verified
 * Description:       This plugin contains implementations to get government id verification with woocommerce.
 * Version:           1.0.0
 * Author:            Laurendy Lam
 * Author URI:        laurendylam.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       get-id-verified
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GET_ID_VERIFIED_VERSION', '1.0.0' );

/**
 * Plugin Constants
 */

define('GIV_IMAGE_FOLDER', 'giv_uploads');
define('GIV_IMAGE_UPLOAD_NAME', 'giv_upload_image_name');
define('GIV_IMAGE_UPLOADED_ID', 'giv_uploaded_image_id');
define('GIV_USER_IS_VERIFIED', 'giv_user_is_verified');
define('GIV_USER_IS_VERIFIED_CHECKBOX', 'giv_verified_checkbox');
define('GIV_CRON', 'giv_cron_hook');

/**
 * The code for utils shared across all code in this plugin.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class.get-id-verified-utils.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class.get-id-verified-user.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-get-id-verified-activator.php
 */
function activate_get_id_verified() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-get-id-verified-activator.php';
	Get_Id_Verified_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-get-id-verified-deactivator.php
 */
function deactivate_get_id_verified() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-get-id-verified-deactivator.php';
	Get_Id_Verified_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_get_id_verified' );
register_deactivation_hook( __FILE__, 'deactivate_get_id_verified' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-get-id-verified.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_get_id_verified() {

	$plugin = new Get_Id_Verified();
	$plugin->run();

}
run_get_id_verified();
