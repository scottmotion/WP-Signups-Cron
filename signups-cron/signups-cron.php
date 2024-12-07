<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/scottmotion/WP-Signups-Cron/
 * @since             1.0.0
 * @package           Signups_Cron
 * @author            Scott Winn
 * @copyright         2024 Scott Winn
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Signups Cron
 * Plugin URI:        https://github.com/scottmotion/WP-Signups-Cron/
 * Description:       Manage WordPress signups via WP-Cron.
 * Version:           1.0.0
 * Author:            Scott Winn
 * Author URI:        https://www.scottwinn.dev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       signups-cron
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SIGNUPS_CRON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-signups-cron-activator.php
 */
function activate_signups_cron() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-signups-cron-activator.php';
	Signups_Cron_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-signups-cron-deactivator.php
 */
function deactivate_signups_cron() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-signups-cron-deactivator.php';
	Signups_Cron_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_signups_cron' );
register_deactivation_hook( __FILE__, 'deactivate_signups_cron' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-signups-cron.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_signups_cron() {

	$plugin = new Signups_Cron();
	$plugin->run();

}
run_signups_cron();
