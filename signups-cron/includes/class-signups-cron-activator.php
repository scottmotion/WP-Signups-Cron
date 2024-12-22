<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Activator {

	/**
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		add_option(
			'signups_cron_settings',
			'',
			'',
			'off'
		);
		// will call sanitize_option( $option, $value )
		// maybe we can set sanitization add_filter once before adding the option here?
		// add_filter( "sanitize_option_{$option_name}", $args['sanitize_callback'] ) // some wp provided function as callback

	}

}
