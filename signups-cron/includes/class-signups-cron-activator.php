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
 * @author     Your Name <email@example.com>
 */
class Signups_Cron_Activator {

	/**
	 * The array of default options set during plugin activation.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $default_options    The array of default options set during plugin activation.
	 */
	// protected $default_options;

	/**
	 * Initialize the collection used to set the default options during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// $this->default_options = array(
		// 	'__construct key'      => '__construct value'
		// );

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		add_option(
			'signups_cron_settings',				// Name of the option to add.
			array(									// Option value. 
				'active_enabled'		=> false,
				'active_threshold'		=> 365,
				'pending_enabled'		=> false,
				'pending_threshold'		=> 365,
				'send_email_report'		=> true,
				'cron_schedule'			=> 'daily'
			),
			'',										// Description (Deprecated).
			'off'									// Whether to load the option when WordPress starts up.
		);

	}

}
