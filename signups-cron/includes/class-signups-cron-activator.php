<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Activator {

	/**
	 * The code that runs during plugin activation.
	 * 
	 * Add plugin options during activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( !is_multisite() ) {
			add_option(
				'signups_cron_settings',
				'',
				'',
				false
			);
		}

	}


}
