<?php

/**
 * Schedule the cron event.
 *
 * @since       1.0.0
 * @package     Signups_Cron
 * @subpackage  Signups_Cron/admin
 * @author      Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Scheduler {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

    /**
     * Function that gets executed when singups cron options are changed.
     * 
     * Hooked to update_option_{$option}
     * 
     * @since   1.0.0
     */
    public function schedule_cron_event() {

        $options = get_option( 'signups_cron_settings' );
        $next_timestamp = wp_next_scheduled( 'signups_cron_event_hook' );

        if ( !isset($options['signups_cron_field_active_enabled']) && !isset($options['signups_cron_field_pending_enabled']) ) {
            // Cron NOT enabled. Check if event is scheduled.
            if ( $next_timestamp ) {
                // Next event IS scheduled. Unschedule/clear event(s)
                wp_unschedule_hook( 'signups_cron_event_hook' ); // Unschedules all events attached to the hook.
            } // else { // Next event NOT scheduled. // Everything is ok! }
        } else {
            // Cron IS enabled. Check if event is scheduled.
            if ( !$next_timestamp ) {
                // Next event NOT scheduled. Schedule new event.
                wp_schedule_event( time(), $options['signups_cron_field_cron_schedule'], 'signups_cron_event_hook' );
            } else {
                // Next event IS scheduled. Check if schedule recurrence has changed.
                // Get next event object
                $next_event_obj = wp_get_scheduled_event( 'signups_cron_event_hook' );

                if ( $next_event_obj->schedule != $options['signups_cron_field_cron_schedule'] ) {
                    // Schedule recurrence IS changed. Reschedule event
                    wp_unschedule_hook('signups_cron_event_hook'); // Unschedules all events attached to the hook.
                    wp_schedule_event( time(), $options['signups_cron_field_cron_schedule'], 'signups_cron_event_hook' );
                } // else { // Schedule recurrence NOT changed // Everything is ok! }
            }
        } // end if

    }



}
