<?php

/**
 * Fired during plugin activation
 *
 * @link       laurendylam.com
 * @since      1.0.0
 *
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Get_Id_Verified
 * @subpackage Get_Id_Verified/includes
 * @author     Laurendy Lam <lamlaurendy@gmail.com>
 */
class Get_Id_Verified_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_action(GIV_CRON, [get_called_class(), 'cron_exec']);

		Get_Id_Verified_Utils::create_upload_dir();
		Get_Id_Verified_Activator::activate_cron();
	}

	/**
	 * Schedules the next cron session.
	 *
	 * @since    1.0.0
	 */
	public static function activate_cron() {
		if( !wp_next_scheduled( GIV_CRON ) ) {
			wp_schedule_event(time(), 'daily', GIV_CRON);
		}
	}

	/**
	 * Removes the temp upload directory and activates the next cron session.
	 *
	 * @since    1.0.0
	 */
	public static function cron_exec() {
		Get_Id_Verified_Utils::remove_upload_temp_dir();
		Get_Id_Verified_Activator::activate_cron();
	}

}
