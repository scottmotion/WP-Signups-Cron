<?php

/**
 * Get the signups table info.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Table_Info {

    /**
	 * The name of the signups table.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $table_name The name of the database table for signups.
	 */
	// private $table_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

    /**
	 * Get the signups table from the WordPress database.
	 *
	 * @since   1.0.0
     * @return  array   $signups_table_info     Info requested about the signups table.
	 */
    public function get_signups_table_info() {

        global $wpdb;

        // Set the table name to 'signups'
        $table_name = $wpdb->prefix . 'signups';
        
        $signups_table_info = [];

        // Get the active signups count from the database.
        // Must use direct database call since WP does not provide a function to count signups.
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $signups_table_info["signups_count_active"] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE active = %d", $table_name, 1) );
        
        // Get the pending signups count from the database.
        // Must use direct database call since WP does not provide a function to count signups.
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $signups_table_info["signups_count_pending"] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE active = %d", $table_name, 0) );
        
        // Calculate the total signups count.
        $signups_table_info["signups_count_total"] = $signups_table_info["signups_count_active"] + $signups_table_info["signups_count_pending"];

        $signups_table_size = 0;

        // Get the signups table status from the database.
        // Must use direct database call since WP does not provide a function to SHOW TABLE STATUS.
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $signups_table_status = $wpdb->get_results( $wpdb->prepare( "SHOW TABLE STATUS LIKE %s",  $wpdb->esc_like($table_name) ), ARRAY_A );

        // Calculate the signups table size in MB.
        if ( $signups_table_status ) {
                $signups_table_size += round((($signups_table_status[0]['Data_length'] + $signups_table_status[0]['Index_length']) / 1024 / 1024), 2);
        }

        $signups_table_info["signups_table_size"] = $signups_table_size;
    
        return $signups_table_info;    

    }

}