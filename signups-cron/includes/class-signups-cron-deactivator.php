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
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		$options = get_option( 'signups_cron_settings');
		unset($options['signups_cron_field_active_enabled'], $options['signups_cron_field_pending_enabled']);
		update_option( 'signups_cron_settings', $options );

		// $deactivate_options = array(
		// 	'signups_cron_field_active_enabled',
		// 	'signups_cron_field_pending_enabled'
		// );

		// $deactivate_options = wp_parse_args( $deactivate_options, get_option( 'signups_cron_settings') );
		// update_option( 'signups_cron_settings', $deactivate_options );
		// Unschedule all events attached to the hook.
		wp_unschedule_hook('signups_cron_event_hook');

	}

}
