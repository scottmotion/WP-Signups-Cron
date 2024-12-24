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

}
