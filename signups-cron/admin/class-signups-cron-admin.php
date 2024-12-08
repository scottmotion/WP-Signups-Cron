<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 * @author     Your Name <email@example.com>
 */
class Signups_Cron_Admin {

    /**
	 * The name of the signups table.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		Signups_Cron_Table_Info		$table_info		Provides information about the signups table.
	 */
	private $signups_table_info;


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $signups_cron    The ID of this plugin.
	 */
	private $signups_cron;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $signups_cron      The name of this plugin.
	 * @param      string    $version    		The version of this plugin.
	 */
	public function __construct( $signups_cron, $version ) {

		$this->signups_cron = $signups_cron;
		$this->version = $version;
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-table-info.php';

	}

	/**
	 * Register the admin submenu item for 'Signups Cron' under 'Users'.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_page() {

		add_submenu_page(
			'users.php',								// $parent_slug string required - The slug name for the parent menu (or the file name of a standard WordPress admin page).
			__('Signups Cron Home', 'signups-cron'),	// $page_title string required - The text to be displayed in the title tags of the page when the menu is selected.
			__('Signups Cron', 'signups-cron'),			// $menu_title string required - The text to be used for the menu.
			'manage_options',							// $capability string required - The capability required for this menu to be displayed to the user.
			'signups-cron',								// $menu_slug string required - The slug name to refer to this menu by.
			array( $this, 'render_admin_page' ),		// $callback callable optional - The function to be called to output the content for this page.
			null										// $position int|float optional - The position in the menu order this item should appear.
		);
	
	}

	/**
	 * Render the admin page for Signups Cron.
	 *
	 * @since 1.0.0
	 */
	public function render_admin_page() {

		// check user capabilities
		// if ( ! current_user_can( 'manage_options' ) ) {
		// 	return;
		// }
	
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<hr>
			<?php
				// settings_fields( 'signups_cron_information' );
				do_settings_sections( 'signups_cron_information' );
			?>
			<hr>
			<form name="settings" action="options.php" method="post">
				<?php
				// output security fields for the registered setting "signups_cron"
				settings_fields( 'signups_cron_settings' );
				// output setting sections and their fields
				do_settings_sections( 'signups_cron_settings' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
			</form>
			<hr>
			<?php
				settings_fields( 'signups_cron_tools' );
				do_settings_sections( 'signups_cron_tools' );
			?>
		</div>
		<?php		

	}

	/**
	 * Register the settings (options groups and options names) for Signups Cron.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {

		/**
		 * register_setting( string $option_group, string $option_name, array $args = array() )
		 * 
		 * @param string $option_group  Setting group. (Page)
    	 * @param string $option_name   Setting name.
    	 * @param array  $args          Array of setting registration arguments.
		 */

		register_setting( 'signups_cron_information', 'signups_cron_information' );
		register_setting( 'signups_cron_settings', 'signups_cron_settings' );
		register_setting( 'signups_cron_tools', 'signups_cron_tools' );
	
	}

	/**
	 * Add settings sections for Signups Cron.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_sections() {

		/**
    	 * add_settings_section( string $id, string $title, callable $callback, string $page, array $args = array() )
		 * 
		 * @param string    $id
		 * @param string    &title
		 * @param callable  $callback
		 * @param string    $page
		 * @param array     $args
		 */
		
		 add_settings_section(
			'signups_cron_section_information',
			__( 'Table Information', 'signups-cron' ),
			array( $this, 'signups_cron_section_information_cb' ),
			'signups_cron_information',
			array(
				'before_section'        => '<div class="%s">',
				'section_class'         => 'my_cool_class',
				'after_section'         => '</div>',
			)
		);
	
		add_settings_section(
			'signups_cron_section_settings',
			__( 'Cron Settings', 'signups-cron' ),
			array( $this, 'signups_cron_section_settings_cb' ),
			'signups_cron_settings'
		);
	
		add_settings_section(
			'signups_cron_section_tools',
			__( 'Signups Tools', 'signups-cron' ),
			array( $this, 'signups_cron_section_tools_cb' ),
			'signups_cron_tools'
		);
		
	}

	/**
	 * Information section callback function.
	 *
	 * @param array $args  The settings array ($id, $title, $callback, $page, $args).
	 */
	public function signups_cron_section_information_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Signups table information.', 'signups-cron' ); ?></p>
		<?php
	}

	/**
	 * Settings section callback function.
	 *
	 * @param array $args  The settings array ($id, $title, $callback, $page, $args).
	 */
	public function signups_cron_section_settings_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Signups cron settings.', 'signups-cron' ); ?></p>
		<?php
	}

	/**
	 * Tools section callback function.
	 *
	 * @param array $args  The settings array ($id, $title, $callback, $page, $args).
	 */
	public function signups_cron_section_tools_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Signups tools.', 'signups-cron' ); ?></p>
		<?php
	}

	/**
	 * Add settings fields for Signups Cron.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_fields() {

		/**
		 * add_settings_field( string $id, string $title, callable $callback, string $page, string $section = ‘default’, array $args = array() )
		 * 
		 * @param string    $id
		 * @param string    &title
		 * @param callable  $callback
		 * @param string    $page
		 * @param string	$section
		 * @param array     $args
		 */

		// Register a new field in the "signups_cron_section_information" section, inside the "signups_cron_information" page.
		add_settings_field(
			'signups_cron_field_signups_information',  // As of WP 4.6 this value is used only internally.
			__( 'Signups Table Information', 'signups-cron' ),
			array( $this, 'signups_cron_field_signups_information_cb' ),
			'signups_cron_information',
			'signups_cron_section_information',
			array(
				'label_for'		=> 'signups_cron_field_signups_information' // Use $args' label_for to populate the id inside the callback.
			)

		);
		
		// Register a new field in the "signups_cron_section_settings" section, inside the "signups_cron_settings" page.
		add_settings_field(
			'signups_cron_field_active_enabled',
			__( 'Active Signups Cron', 'signups-cron' ),
			array( $this, 'signups_cron_field_active_enabled_cb' ),
			'signups_cron_settings',
			'signups_cron_section_settings',
			array(
				'label_for'		=> 'signups_cron_field_active_enabled' // Use $args' label_for to populate the id inside the callback.
			)
		);

		// Register a new field in the "signups_cron_section_information" section, inside the "signups_cron_information" page.
	    add_settings_field(
			'signups_cron_field_delete_signups_now',
			__( 'Delete Signups Now', 'signups-cron' ),
			array( $this, 'signups_cron_field_delete_signups_now_cb' ),
			'signups_cron_tools',
			'signups_cron_section_tools',
			array(
				'label_for'		=> 'signups_cron_field_delete_signups_now' // Use $args' label_for to populate the id inside the callback.
			)
		);
	
	}

	/**
	 * Signups Information field callback function.
	 *
	 * @param array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_signups_information_cb() {
		
		$signups_table_info = new Signups_Cron_Table_Info();
		$data = $signups_table_info->get_signups_table_info();

		?>
		<table class="signups-table-info">
			<tr>
                <td>Signups Table Size:</td>
                <td><?= number_format( $data["signups_table_size"], 2, '.' ); ?> MB</td>
            </tr>
            <tr>
                <td>Total Signups Count:</td>
                <td><?= number_format( $data["signups_count_total"] ); ?></td>
            </tr>
            <tr>
                <td>Active Signups Count:</td>
                <td><?= number_format( $data["signups_count_active"] ); ?></td>
            </tr>
            <tr>
                <td>Pending Signups Count:</td>
                <td><?= number_format( $data["signups_count_pending"] ); ?></td>
            </tr>
		</table>
		<?php

	}

	/**
	 * Active Signups Delete Enabled field callback function.
	 * 
	 * @param array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_active_enabled_cb() {

		?>
		<p>
			<?php esc_html_e( 'Enable Active Signups cron ', 'signups-cron' ); ?>
			<?php esc_html_e( '<CHECKBOX>', 'signups-cron' ); ?>
		</p>
		<?php

	}

	/**
	 * Delete Signups Now field callback function.
	 *
	 * @param array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_delete_signups_now_cb() {

		?>
		<div class="delete-signups-now_wrapper">
			<p>
				<?php esc_html_e( 'Delete all ', 'signups-cron' ); ?>
				<?php esc_html_e( '<SELECT>', 'signups-cron' ); ?>
				<?php esc_html_e( ' signups more than ', 'signups-cron' ); ?>
				<?php esc_html_e( '<NUMBER>', 'signups-cron' ); ?>
				<?php esc_html_e( ' days old.', 'signups-cron' ); ?>
			</p>
			<div class="delete-signups-now_submit-wrapper">
				<button class="button button-primary">Submit</button>
			</div>
		</div>
		<p style="color:red">
			<?php esc_html_e( '*WARNING! This action cannot be undone. Proceed with caution.*', 'signups-cron' ); ?>
		</p>
		<?php

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Signups_Cron_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Signups_Cron_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->signups_cron, plugin_dir_url( __FILE__ ) . 'css/signups-cron-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Signups_Cron_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Signups_Cron_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->signups_cron, plugin_dir_url( __FILE__ ) . 'js/signups-cron-admin.js', array( 'jquery' ), $this->version, false );

	}

}
