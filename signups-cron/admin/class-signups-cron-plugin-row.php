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
	 * Creates a 'Settings' link (below the plugin name) that takes the users directly to 'Users > Signups Cron'.
	 * 
	 * add_filter( 'plugin_action_links_signups-cron/signups-cron.php', 'signups_cron_add_action_links', 10, 1 );
	 *
	 * @since    1.0.0
	 * 
	 * @param	array	$actions	The list of links to appear under the plugin title.
	 * @return	array	$actions	The updated array of links including the 'Settings' link.
	 */
	public function signups_cron_add_action_links( $actions ) {

		// check for multisite and return default actions
		if ( is_multisite() ) {
			return $actions;
		}

		$settings_url = get_admin_url(null, 'users.php?page=signups-cron');

		$settings_link = array('settings' => '<a href="'. esc_url( $settings_url ) .'">' . __('Settings', 'signups-cron') . '</a>');
		
		$actions = array_merge($settings_link, $actions);
		
		return $actions;

	}

	/**
	 * Creates a warning (below the plugin meta) about use on multisite installations.
	 * 
	 * add_action( 'after_plugin_row_meta', 'signups_cron_add_multisite_warning', 10, 2 );
	 *
	 * @since    1.0.0
	 * 
	 * @param	string	$plugin_file	Path to the plugin file relative to the plugins directory.
	 * @param	array	$plugin_data	An array of plugin data.
	 */
	public function signups_cron_add_multisite_warning( $plugin_file, $plugin_data ) {

		if ( is_multisite() && $plugin_file == 'signups-cron/signups-cron.php' ) {

			?>
			<div class="notice inline notice-warning notice-alt">
				<p>
					<?php esc_html_e( 'Signups Cron is not designed for use on multisite installations.', 'signups-cron' ); ?>
				</p>
			</div>
			<?php

		}

	}


}
