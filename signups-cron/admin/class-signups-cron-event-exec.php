<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Event_Exec {

    /**
	 * The name of the signups table.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $table_name The name of the database table for signups.
	 */
	private $table_name;

    /**
	 * The Signups Cron options from WP options table.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		array		$options		The plugin options.
	 */
	private $options;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

        global $wpdb;
        $this->table_name = $wpdb->prefix . 'signups';
		$this->options = get_option( 'signups_cron_settings' );

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
        $table_name = esc_sql( $this->table_name ); // Sanitize the table name.

        // Get signups from wp_signups table
        $chosen_signups = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE active = $status", ARRAY_A );

        $count_deleted_signups = 0;

        // Set threshold to delete signups
        $one_day_in_seconds = 60 * 60 * 24;
        $delete_threshold = $one_day_in_seconds * $threshold;

        // Get the current Unix timestamp
        $current_time = time();

        // if ($status == 0) { // pending
        //     $chosen_signups_2 = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}signups WHERE active = $status AND UNIX_TIMESTAMP('registered' + $delete_threshold) <= $current_time", ARRAY_A );
        // } elseif ($status == 1) { // active
        //     $chosen_signups_2 = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}signups WHERE active = $status AND UNIX_TIMESTAMP('activated' + $delete_threshold) <= $current_time", ARRAY_A );
        //     // SELECT * FROM `wp_7iykh3_signups` WHERE active = 1 AND (UNIX_TIMESTAMP(`activated`) + 31536000) <= UNIX_TIMESTAMP();
        // }

        foreach ($chosen_signups as $x) {

            $signup_date = '';

            if ($status == 0) {
                $signup_date = strtotime($x['registered']); // pending
            } elseif ($status == 1) {
                $signup_date = strtotime($x['activated']); // active
            }

            // Compare signup timestamp + threshold to current timestamp
            if (($signup_date + $delete_threshold) <= $current_time) {
                // Signup is older than threshold

                // Get the signup id
                $signup_id = $x['signup_id'];

                // Remove old signups from signups table
                $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM {$table_name} WHERE signup_id = $signup_id")
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
     * @access  private
     */
    private function cron_event_exec() {

		$options = $this->options;

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
        if (isset($options['signups_cron_field_send_email']) && ($options['signups_cron_field_send_email'] == 1)) {
            // send admin email is enabled

            // start building message
            $admin_email = get_option('admin_email');
            $blog_name = get_option( 'blogname' );

            // $event_date_time = date('F j, Y, g:i a T', time());

            $event_date_now = date_format(date_create()->setTimezone(new DateTimeZone(wp_timezone_string())), 'F j, Y, g:i a T'); // TODO: Check if site options uses timezone_string or gmt_offset

            $message = "Signups Cron successfully ran on {$event_date_now}.";

            if (isset($options['signups_cron_field_active_enabled']) && ($options['signups_cron_field_active_enabled'] == 1)) {
                $message .= "\nDeleted {$count_deleted_signups_active} Active Signups older than {$options['signups_cron_field_active_threshold']} days.";
            }
            if (isset($options['signups_cron_field_pending_enabled']) && ($options['signups_cron_field_pending_enabled'] == 1)) {
                $message .= "\nDeleted {$count_deleted_signups_pending} Pending Signups older than {$options['signups_cron_field_pending_threshold']} days.";
            }

            // Send email
            wp_mail( $admin_email, "[$blog_name] Signups Cron Report", $message  );
        }
    }

    /**
     * See 'class-signups-cron.php' & 'class-signups-cron-loader.php' for how the plugin adds actions & filters
     * 
     * Example:
     * 
     * require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-signups-cron-loader.php';
     * $this->loader = new Signups_Cron_Loader();
     * $plugin_admin = new Signups_Cron_Admin( $this->get_signups_cron(), $this->get_version() );
     * $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_page' );
     * 
     * So...
     * 
     * $plugin_cron_event_exec = Signups_Cron_Event_Exec();
     * $this->loader->add_action( 'signups_cron_event_hook', $plugin_cron_event_exec, 'cron_event_exec' );
     * 
     * 
     * Others useful examples:
     * 
     * add_action( 'signups_cron_event_hook', 'cron_event_exec' ); // Standard way
     * add_action( 'signups_cron_event_hook', array( $this, 'cron_event_exec' ) ); // Inside $this class
     * 
     */

}
