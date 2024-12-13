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

		/**
		 * TODO:
		 * [] set option defaults in Signups_Cron_Admin
		 * [?] Do we need to set empty ('0') values?
		 */
		add_option(
			'signups_cron_settings',				// Name of the option to add.
			array(									// Option value. 
				'signups_cron_field_active_enabled'			=> '0',		// Active Signups cron enabled.
				'signups_cron_field_active_threshold'		=> '365',	// Active Signups cron threshold.
				'signups_cron_field_pending_enabled'		=> '0',		// Pending Signups cron enabled.
				'signups_cron_field_pending_threshold'		=> '365',	// Pending Signups cron threshold.
				'signups_cron_field_send_email_report'		=> '0',		// Send cron email report enabled.
				'signups_cron_field_cron_schedule'			=> 'daily'	// Cron schedule recurrence.
			),
			'',										// Description (Deprecated).
			'off'									// Whether to load the option when WordPress starts up.
		);

	}

}
