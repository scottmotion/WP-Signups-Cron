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
 * @author     Your Name <email@example.com>
 */
class Signups_Cron_Activator {

	/**
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		add_option(
			'signups_cron_settings',	// Name of the option to add.
			'',							// Option value. 
			'',							// Description (Deprecated).
			'off'						// Whether to load the option when WordPress starts up.
		);

	}

}
