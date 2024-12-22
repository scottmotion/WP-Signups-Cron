<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 * @author     Your Name <email@example.com>
 */
class Signups_Cron_Deactivator {

	/**
	 * The code that runs during plugin deactivation.
	 * 
	 * Remove plugin data and cron events during deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Disable cron in options
		$options = get_option( 'signups_cron_settings');
		if ( is_array($options) && ( isset($options['signups_cron_field_active_enabled'] ) || isset( $options['signups_cron_field_pending_enabled'] ) ) ) {
			unset($options['signups_cron_field_active_enabled'], $options['signups_cron_field_pending_enabled']);
			update_option( 'signups_cron_settings', $options );
		}

		// Unschedule all events attached to the hook.
		wp_unschedule_hook('signups_cron_event_hook');

	}

}
