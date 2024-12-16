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

		// Remove this pungin's cron event

		// Check if event with our hook is scheduled and unschedule it.
		$timestamp = wp_next_scheduled( 'signups_cron_event_hook' );
    	wp_unschedule_event( $timestamp, 'signups_cron_event_hook' );

		// Clear all events with our hook
		// wp_clear_scheduled_hook('signups_cron_event_hook'),

	}

}
