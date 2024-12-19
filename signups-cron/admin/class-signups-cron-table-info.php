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
	private $table_name;

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
        $table_name = esc_sql( $wpdb->prefix . 'signups' ); // Sanitize the table name.

        $signups_table_info = [];
    
        // $signups_table_info["signups_count_total"] = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name}" );
        $signups_table_info["signups_count_active"] = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name} WHERE active = 1" );
        $signups_table_info["signups_count_pending"] = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name} WHERE active = 0" );
        $signups_table_info["signups_count_total"] = $signups_table_info["signups_count_active"] + $signups_table_info["signups_count_pending"];

        $signups_table_size = 0;

        $results = $wpdb->get_results( "SHOW TABLE STATUS LIKE '{$table_name}'", ARRAY_A );

        if ( $results ) {
                $signups_table_size += round((($results[0]['Data_length'] + $results[0]['Index_length']) / 1024 / 1024), 2);
        }

        $signups_table_info["signups_table_size"] = $signups_table_size;
    
        return $signups_table_info;    

    }

}