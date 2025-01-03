<?php

/**
 * The file that defines the Plugin Row class.
 * 
 * A class definition that creates action links and row meta.
 * 
 * @link       https://github.com/scottmotion/WP-Signups-Cron/
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 */

/**
 * The Plugin Row class.
 * 
 * Define the Plugins List Table functionality for the plugin's row.
 * Creates action links to appear under the plugin title.
 *
 * @since      1.0.0
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Plugin_Row {

	/**
	 * Creates a 'Settings' link below the plugin name that takes the users directly to 'Users > Signups Cron'.
	 *
	 * @since    1.0.0
	 * 
	 * @param array $links The list of links to appear under the plugin title.
	 * @return array $links The updated array of links including the 'Settings' link.
	 */
	public function signups_cron_add_action_links( $actions ) {
		
		$settings_link = array('settings' => '<a href="'. esc_url( get_admin_url(null, 'users.php?page=signups-cron') ) .'">' . __('Settings', 'signups-cron') . '</a>');
		
		$actions = array_merge($settings_link, $actions);
		
		return $actions;

	}

	// public function signups_cron_add_multisite_warning( $data, $response ) {
	// 	if ( !is_multisite() ) {
	// 		printf(
	// 			'<div class="update-message"><p><strong>%s</strong></p></div>',
	// 			__( 'Signups Cron is not designed for use on multisite installations.', 'signups-cron' )
	// 		);	
	// 	}
	// }

	public function signups_cron_add_multisite_warning( $plugin_file, $plugin_data ) {
		if ( is_multisite() && $plugin_file == 'signups-cron/signups-cron.php' ) {
			printf(
				'<div class="notice inline notice-warning notice-alt"><p>%s</p></div>',
				__( 'Signups Cron is not designed for use on multisite installations.', 'signups-cron' )
			);	
		}
	}


}
