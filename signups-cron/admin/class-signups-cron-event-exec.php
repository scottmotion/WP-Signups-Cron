<?php

/**
 * Functionality for cron event.
 *
 * Delete 'active' and 'pending' signups.
 * Email a report to the admin.
 *
 * @since       1.0.0
 * @package     Signups_Cron
 * @subpackage  Signups_Cron/admin
 * @author      Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Event_Exec {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

    /**
     * Delete Signups from WordPress database
     * 
     * @since   1.0.0
     * @access  private
     * @param   int     $status                 From _signups['active'] Pending = 0, Active = 1
     * @param   int     $threshold              Number of days for delete threshold
     * @return  int     $count_deleted_signups  Number of signups deleted.
     */
    private function delete_signups($status, $threshold) {

        // Access Global database object
        global $wpdb;
        $table_name = $wpdb->prefix . 'signups';

        // Get signups from wp_signups table
        $chosen_signups = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM %i WHERE active = %d",
                $table_name,
                $status
            ), 
            ARRAY_A
        );

        $count_deleted_signups = 0;

        // Set threshold to delete signups
        $delete_threshold = DAY_IN_SECONDS * $threshold;

        // Get the current Unix timestamp
        $current_time = time();

        foreach ($chosen_signups as $signup) {

            $signup_date = '';

            if ($status == 0) {
                $signup_date = strtotime($signup['registered']); // pending
            } elseif ($status == 1) {
                $signup_date = strtotime($signup['activated']); // active
            }

            // Compare signup timestamp + threshold to current timestamp
            if (($signup_date + $delete_threshold) <= $current_time) {
                // Signup is older than threshold

                // Get the signup id
                $signup_id = $signup['signup_id'];

                // Remove old signups from signups table
                $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM %i WHERE signup_id = %d", $table_name, $signup_id
                    )
                );

                $count_deleted_signups++;
            }
        }

        return $count_deleted_signups;
    }

    /**
     * Function that gets executed when the scheduled cron event runs.
     * 
     * @since   1.0.0
     */
    public function cron_event_exec() {

		$options = get_option( 'signups_cron_settings' );

        $count_deleted_signups_active = 0;

        // Check if user wants to delete active signups
        if (isset($options['signups_cron_field_active_enabled']) && ($options['signups_cron_field_active_enabled'] == 1)) {
            // active signups cron is enabled
            if (isset($options['signups_cron_field_active_threshold'])) {
                // active signups threshold is set
                $active_status = 1;
                $active_threshold = $options['signups_cron_field_active_threshold'];
                // call delete function, returns number of deleted signups
                $count_deleted_signups_active = $this->delete_signups($active_status, $active_threshold);
            }
        }

        $count_deleted_signups_pending = 0;

        // Check if user wants to delete pending signups
        if (isset($options['signups_cron_field_pending_enabled']) && ($options['signups_cron_field_pending_enabled'] == 1)) {
            // pending signups cron is enabled
            if (isset($options['signups_cron_field_pending_threshold'])) {
                // pending signups threshold is set
                $pending_status = 0;
                $pending_threshold = $options['signups_cron_field_pending_threshold'];
                // call delete function, returns number of deleted signups
                $count_deleted_signups_pending = $this->delete_signups($pending_status, $pending_threshold);
            }
        }

        // Check if user wants to send admin email
        if (isset($options['signups_cron_field_send_email_report']) && ($options['signups_cron_field_send_email_report'] == 1)) {
            // send admin email is enabled

            // start building message
            $admin_email = get_option('admin_email');            
            $blog_name = get_option( 'blogname' );
            $event_date_now = wp_date('F j, Y, g:i a T');

            $email_recipient = $admin_email;
            $email_subject = "[$blog_name] Signups Cron Report";

            // $message = "Signups Cron successfully ran on {$event_date_now}.";
            $email_message = sprintf(
                /* translators: Date and time. */
                esc_html__( 'Signups Cron successfully ran on %s.', 'signups-cron'),
                esc_html($event_date_now)
            ) . "\n";

            if (isset($options['signups_cron_field_active_enabled']) && ($options['signups_cron_field_active_enabled'] == 1)) {
                // $message .= "\nDeleted {$count_deleted_signups_active} Active Signups older than {$options['signups_cron_field_active_threshold']} days.";
                $email_message .= sprintf(
                    /* translators: 1: Number of items. 2: Number of days. */
                    esc_html__( 'Deleted %1$s Active Signups older than %2$s days.', 'signups-cron'),
                    esc_html($count_deleted_signups_active),
                    esc_html($options['signups_cron_field_active_threshold'])
                ) . "\n";
            }

            if (isset($options['signups_cron_field_pending_enabled']) && ($options['signups_cron_field_pending_enabled'] == 1)) {
                // $message .= "\nDeleted {$count_deleted_signups_pending} Pending Signups older than {$options['signups_cron_field_pending_threshold']} days.";
                $email_message .= sprintf(
                    /* translators: 1: Number of items. 2: Number of days. */
                    esc_html__( 'Deleted %1$s Pending Signups older than %2$s days.', 'signups-cron'),
                    esc_html($count_deleted_signups_pending),
                    esc_html($options['signups_cron_field_pending_threshold'])
                ) . "\n";
            }

            // Send email
            wp_mail( $email_recipient, $email_subject, $email_message  );
        }
    }


}
