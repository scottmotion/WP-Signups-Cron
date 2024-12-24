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

        // Set the table name because it is not accessible in $wpdb if is_multisite() === false.
        $table_name = $wpdb->prefix . 'signups';
        
        $signups_table_info = [];
    
        // Get the signups table count from the database.
        // TODO: Use of a direct database call is discouraged.
        // TODO: Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete().	
        $signups_table_info["signups_count_active"] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE active = %d", $table_name, 1) );
        $signups_table_info["signups_count_pending"] = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM %i WHERE active = %d", $table_name, 0) );
        $signups_table_info["signups_count_total"] = $signups_table_info["signups_count_active"] + $signups_table_info["signups_count_pending"];

        $signups_table_size = 0;

        // Get the signups table status
        // TODO: Use of a direct database call is discouraged.
        // TODO: Direct database call without caching detected. Consider using wp_cache_get() / wp_cache_set() or wp_cache_delete().	
        $results = $wpdb->get_results( $wpdb->prepare( "SHOW TABLE STATUS LIKE %s", $table_name ), ARRAY_A );

        // Calculate the signups table size in MB
        if ( $results ) {
                $signups_table_size += round((($results[0]['Data_length'] + $results[0]['Index_length']) / 1024 / 1024), 2);
        }

        $signups_table_info["signups_table_size"] = $signups_table_size;
    
        return $signups_table_info;    

    }

}