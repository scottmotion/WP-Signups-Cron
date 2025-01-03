<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/scottmotion/WP-Signups-Cron/
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Signups_Cron
 * @subpackage Signups_Cron/includes
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Signups_Cron_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $signups_cron    The string used to uniquely identify this plugin.
	 */
	protected $signups_cron;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// If we are on multisite then deactivate and die.
		// if ( is_multisite() ) {
		// 	deactivate_plugins( 'signups-cron/signups-cron.php' );
		// 	die;	
		// }

		if ( defined( 'SIGNUPS_CRON_VERSION' ) ) {
			$this->version = SIGNUPS_CRON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->signups_cron = 'signups-cron';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Signups_Cron_Loader.		Orchestrates the hooks of the plugin.
	 * - Signups_Cron_i18n.			Defines internationalization functionality.
	 * - Signups_Cron_Plugin_Row.	Defines hooks for the plugins list table.
	 * - Signups_Cron_Admin.		Defines all hooks for the admin area.
	 * - Signups_Cron_Scheduler.	Schedules the cron event.
	 * - Signups_Cron_Event_Exec.	Functionality for cron event.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-signups-cron-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-signups-cron-i18n.php';

		/**
		 * The class responsible for defining the plugins list table functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-plugin-row.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-admin.php';

		/**
		 * The class responsible for scheduling the cron event.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-scheduler.php';

		/**
		 * The class responsible for defining all actions that occur in the cron event exec.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-event-exec.php';

		$this->loader = new Signups_Cron_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Signups_Cron_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Signups_Cron_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_plugin_row = new Signups_Cron_Plugin_Row();

		$this->loader->add_filter( 'plugin_action_links_signups-cron/signups-cron.php', $plugin_plugin_row, 'signups_cron_add_action_links', 10, 1 );
		$this->loader->add_action( 'after_plugin_row_meta', $plugin_plugin_row, 'signups_cron_add_multisite_warning', 10, 2 );

		$plugin_admin = new Signups_Cron_Admin( $this->get_signups_cron(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_settings_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_settings_fields' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'users_page_signups-cron', $plugin_admin, 'load_signups_cron_options', 9 );

		$plugin_cron_scheduler = new Signups_Cron_Scheduler();
		$this->loader->add_action( 'update_option_signups_cron_settings', $plugin_cron_scheduler, 'schedule_cron_event' );

		$plugin_cron_event_exec = new Signups_Cron_Event_Exec();
		$this->loader->add_action( 'signups_cron_event_hook', $plugin_cron_event_exec, 'cron_event_exec' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_signups_cron() {
		return $this->signups_cron;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Signups_Cron_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
