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
 * @author     Scott Winn <hello@scottwinn.dev>
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
	 * The Signups Cron options from WP options table.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		array		$options	The plugin options.
	 */
	private $options;

    /**
	 * The Signups Cron default options.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		array		$options	The plugin options.
	 */
	private $default_options;

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
	 * @since	1.0.0
	 * @param   string		$signups_cron		The name of this plugin.
	 * @param   string		$version    		The version of this plugin.
	 * @var		array		$options			Plugin options from the database.
	 */
	public function __construct( $signups_cron, $version ) {

		$this->signups_cron = $signups_cron;
		$this->version = $version;
		$this->options = array();
		// $this->defaults = array();
		$this->default_options = array(								// Default option value for 'signups_cron_settings'.
			'signups_cron_field_active_enabled'			=> NULL,	// Active Signups cron enabled.
			'signups_cron_field_active_threshold'		=> '365',	// Active Signups cron threshold.
			'signups_cron_field_pending_enabled'		=> NULL,	// Pending Signups cron enabled.
			'signups_cron_field_pending_threshold'		=> '365',	// Pending Signups cron threshold.
			'signups_cron_field_send_email_report'		=> NULL,	// Send cron email report enabled.
			'signups_cron_field_cron_schedule'			=> 'daily'	// Cron schedule recurrence.
		);

	}

	/**
	 * Register the 'Signups Cron' admin submenu page and insert under the 'Users' page.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_page() {

		add_submenu_page(
			'users.php',								// $parent_slug	string		required - The slug name for the parent menu (or the file name of a standard WordPress admin page).
			__('Signups Cron Home', 'signups-cron'),	// $page_title	string		required - The text to be displayed in the title tags of the page when the menu is selected.
			__('Signups Cron', 'signups-cron'),			// $menu_title	string		required - The text to be used for the menu.
			'manage_options',							// $capability	string		required - The capability required for this menu to be displayed to the user.
			'signups-cron',								// $menu_slug	string		required - The slug name to refer to this menu by.
			array( $this, 'render_admin_page' ),		// $callback	callable	optional - The function to be called to output the content for this page.
			null										// $position	int|float	optional - The position in the menu order this item should appear.
		);
	
	}

	/**
	 * Render the admin page for Signups Cron.
	 *
	 * @since	1.0.0
	 */
	public function render_admin_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-table-info.php';	// Todo: move to section callback?
		$this->signups_table_info = new Signups_Cron_Table_Info();											// Todo: move to section callback?
		$this->options = get_option( 'signups_cron_settings' );												// Todo: move to render_admin_page() or load-users_page_signups-cron hook?
		// $this->options = get_option( 'signups_cron_settings', $this->default_options );						// Todo: move to render_admin_page() or load-users_page_signups-cron hook?
		// $options_test = wp_parse_args( $this->options, $this->default_options );

		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<hr>
			<?php
				// settings_fields( 'signups_cron_group_information' );
				do_settings_sections( 'signups_cron_page_information' );
			?>
			<hr>
			<form name="settings" action="options.php" method="post">
				<?php
				// output security fields for the registered setting
				settings_fields( 'signups_cron_group_settings' );			// for $option_group

				// output setting sections and their fields
				do_settings_sections( 'signups_cron_page_settings' );		// for $page

				// output save settings button for this form.
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php		

	}

	/**
	 * Register the settings (options groups and options names) for Signups Cron.
	 *
	 * @since	1.0.0
	 */
	public function register_settings() {

		/**
		 * Registers a setting and its data.
		 * 
		 * register_setting( $option_group, $option_name, $args = array() )
		 * 
		 * @param   string      $option_group               Setting group. (Page)
		 * @param   string      $option_name                Setting name.
		 * @param   array       $args                       Array of setting registration arguments.
		 * @param   string      $args['type']               The type of data associated with this setting.
		 * @param   string      $args['label']              A label of the data attached to this setting.
		 * @param   string      $args['description']        A description of the data attached to this setting.
		 * @param   callable    $args['sanitize_callback']  A callback function that sanitizes the option’s value.
		 * @param   bool|array  $args['show_in_rest']       Whether data associated with this setting should be included in the REST API.
		 * @param   mixed       $args['default']            Default value when calling get_option().
		 */

		register_setting( 'signups_cron_group_information', 'signups_cron_information' );
		register_setting( 'signups_cron_group_settings', 'signups_cron_settings' );
	
	}

	/**
	 * Add settings sections for Signups Cron.
	 *
	 * @since	1.0.0
	 */
	public function add_settings_sections() {

		/**
		 * Adds a new section to a settings page.
		 * 
    	 * add_settings_section( $id, $title, $callback, $page, $args = array( 'before_section', 'after_section', 'section_class' ) )
		 * 
		 * @param 	string    	$id							Slug-name to identify the section. Used in the 'id' attribute of tags.
		 * @param 	string    	$title						Formatted title of the section. Shown as the heading for the section.
		 * @param 	callable 	$callback					Function that echos out any content at the top of the section (between heading and fields).
		 * @param 	string    	$page						The slug-name of the settings page on which to show the section.
		 * @param 	array     	$args						Arguments used to create the settings section.
		 * @param 	string		$args['before_section']		HTML content to prepend to the section’s HTML output. Receives the section’s class name as '%s'.
		 * @param 	string		$args['after_section']		HTML content to append to the section’s HTML output.
		 * @param 	string		$args['section_class']		The class name to use for the section.
		 */
		
		// Signups Table Information Section
		// Called by render_admin_page()
		 add_settings_section(
			'signups_cron_section_information',
			__( 'Table Information', 'signups-cron' ),
			array( $this, 'signups_cron_section_information_cb' ), // Called internally by do_settings_sections()->call_user_func()
			'signups_cron_page_information'
		);
	
		// Cron Event Settings Section 
		// Called by render_admin_page() as child of <form name="settings" action="options.php" method="post")>
		add_settings_section(
			'signups_cron_section_settings',
			__( 'Cron Event Settings', 'signups-cron' ),
			array( $this, 'signups_cron_section_settings_cb' ),
			'signups_cron_page_settings'
		);
			
	}

	/**
	 * Information Section callback function.
	 * 
	 * Function that echos out any content at the top of the section (between heading and fields).
	 * 
	 * @since	1.0.0
	 * @param	array	$args  The settings array ( 'id', 'title', 'callback', 'before_section', 'after_section', 'section_class' ).
	 */
	public function signups_cron_section_information_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Signups table information.', 'signups-cron' ); ?></p>
		<?php
	}

	/**
	 * Settings section callback function.
	 * 
	 * Function that echos out any content at the top of the section (between heading and fields).
	 *
	 * @since   1.0.0
	 * @param	array	$args	The settings array ( 'id', 'title', 'callback', 'before_section', 'after_section', 'section_class' ).
	 */
	public function signups_cron_section_settings_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Signups cron event settings.', 'signups-cron' ); ?></p>
		<?php
	}

	/**
	 * Add settings fields for Signups Cron.
	 *
	 * @since	1.0.0
	 */
	public function add_settings_fields() {

		/**
		 * Adds a new field to a section of a settings page.
		 * 
		 * add_settings_field( string $id, string $title, callable $callback, string $page, string $section = ‘default’, array $args = array( 'label_for', 'class') )
		 * 
		 * @param string    $id					Slug-name to identify the field. Used in the 'id' attribute of tags.
		 * @param string    $title				Formatted title of the field. Shown as the label for the field during output.
		 * @param callable  $callback			Function that fills the field with the desired form inputs. The function should echo its output.
		 * @param string    $page				The slug-name of the settings page on which to show the section.
		 * @param string	$section			The slug-name of the section of the settings page in which to show the box.
		 * @param array     $args				Extra arguments that get passed to the callback function.
		 * @param string	$args['label_for']	When supplied, the setting title will be wrapped in a <label> element, its 'for' attribute populated with this value.
		 * @param string	$args['class]		CSS Class to be added to the <tr> element when the field is output.
		 */

		// Information section fields.
		add_settings_field(
			'signups_cron_field_signups_information',						// As of WP 4.6 this value is used only internally.
			__( 'Signups Table Information', 'signups-cron' ),
			array( $this, 'signups_cron_field_signups_information_cb' ),	// Called internally by do_settings_sections()->do_settings_fields()->call_user_func()
			'signups_cron_page_information',
			'signups_cron_section_information',
			array(
				'label_for' => 'signups_cron_field_signups_information'		// Use $args' label_for to populate the id inside the callback.
			)
		);
		
		// Settings section fields.
		add_settings_field(
			'signups_cron_field_active_enabled',
			__( 'Active Signups Cron', 'signups-cron' ),
			array( $this, 'signups_cron_field_active_enabled_cb' ),
			'signups_cron_page_settings',
			'signups_cron_section_settings',
			array(
				'label_for' => 'signups_cron_field_active_enabled'
			)
		);

		add_settings_field(
			'signups_cron_field_active_threshold',
			__( 'Active Signups Threshold', 'signups-cron' ),
			array( $this, 'signups_cron_field_active_threshold_cb' ),
			'signups_cron_page_settings',
			'signups_cron_section_settings',
			array(
				'label_for' => 'signups_cron_field_active_threshold'
			)
		);

		add_settings_field(
			'signups_cron_field_pending_enabled',
			__( 'Pending Signups Cron', 'signups-cron' ),
			array( $this, 'signups_cron_field_pending_enabled_cb' ),
			'signups_cron_page_settings',
			'signups_cron_section_settings',
			array(
				'label_for' => 'signups_cron_field_pending_enabled'
			)
		);
	
		add_settings_field(
			'signups_cron_field_pending_threshold',
			__( 'Pending Signups Threshold', 'signups-cron' ),
			array( $this, 'signups_cron_field_pending_threshold_cb' ),
			'signups_cron_page_settings',
			'signups_cron_section_settings',
			array(
				'label_for' => 'signups_cron_field_pending_threshold'
			)
		);
	
		add_settings_field(
			'signups_cron_field_send_email_report',
			__( 'Cron Email Report', 'signups-cron' ),
			array( $this, 'signups_cron_field_send_email_report_cb' ),
			'signups_cron_page_settings',
			'signups_cron_section_settings',
			array(
				'label_for' => 'signups_cron_field_send_email_report'
			)
		);
		
		add_settings_field(
			'signups_cron_field_cron_schedule',
			__( 'Cron Schedule Recurrence', 'signups-cron' ),
			array( $this, 'signups_cron_field_cron_schedule_cb' ),
			'signups_cron_page_settings',
			'signups_cron_section_settings',
			array(
				'label_for' => 'signups_cron_field_cron_schedule'
			)
		);
	
	}

	/**
	 * Signups Information field callback function.
	 * 
	 * Called internally by do_settings_sections()->do_settings_fields()
	 *
	 * @since	1.0.0
	 * @param	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_signups_information_cb( $args ) {
		
		// $signups_table_info = new Signups_Cron_Table_Info();
		$data = $this->signups_table_info->get_signups_table_info();

		?>
		<table class="signups-table-info">
			<tr>
                <td>Signups Table Size:</td>
                <td><?php echo number_format( $data["signups_table_size"], 2, '.' ); ?> MB</td>
            </tr>
            <tr>
                <td>Total Signups Count:</td>
                <td><?php echo number_format( $data["signups_count_total"] ); ?></td>
            </tr>
            <tr>
                <td>Active Signups Count:</td>
                <td><?php echo number_format( $data["signups_count_active"] ); ?></td>
            </tr>
            <tr>
                <td>Pending Signups Count:</td>
                <td><?php echo number_format( $data["signups_count_pending"] ); ?></td>
            </tr>
		</table>
		<?php

	}

	/**
	 * Active Signups Delete Enabled field callback function.
	 * 
	 * @since	1.0.0
	 * @param	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_active_enabled_cb( $args ) {

		$options = $this->options;

		if ( ! isset( $options[ $args['label_for'] ] ) ) {
			$options[ $args['label_for'] ] = '0';
		}

		?>
		<p>
			<?php esc_html_e( 'Enable Active signups cron ', 'signups-cron' ); ?>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="1"
				<?php checked( $options[ $args['label_for'] ], 1 ) ?>
			>
			</input>
		</p>
		<?php

	}

	/**
	 * Active Signups Threshold field callback function.
	 * 
	 * @since	1.0.0
	 * @param	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_active_threshold_cb( $args ) {

		$options = $this->options;

		?>
		<p id="text_for_signups_cron_field_active_threshold">
			<?php esc_html_e( 'Delete Active signups after ', 'signups-cron' ); ?>
			<input
				type="number"
				min="0"
				max="999"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value=<?php echo isset( $options[ $args['label_for'] ] ) ? ( $options[ $args['label_for'] ] ) : ( '365' ); ?>
			>
			</input>
			<?php esc_html_e( ' days.', 'signups-cron' ); ?>
		</p>
		<?php

	}
	
	/**
	 * Pending Signups Delete Enabled field callback function.
	 * 
	 * @since	1.0.0
	 * @param	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_pending_enabled_cb( $args ) {

		$options = $this->options;

		if ( ! isset( $options[ $args['label_for'] ] ) ) {
			$options[ $args['label_for'] ] = '0';
		}

		?>
		<p>
			<?php esc_html_e( 'Enable Pending signups cron ', 'signups-cron' ); ?>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="1"
				<?php checked( $options[ $args['label_for'] ], 1 ) ?>
			>
			</input>
		</p>
		<?php

	}

	/**
	 * Pending Signups Threshold field callback function.
	 * 
	 * @since	1.0.0
	 * @param	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_pending_threshold_cb( $args ) {

		$options = $this->options;

		?>
		<p id="text_for_signups_cron_field_pending_threshold">
			<?php esc_html_e( 'Delete Pending signups after ', 'signups-cron' ); ?>
			<input
				type="number"
				min="0"
				max="999"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value=<?php echo isset( $options[ $args['label_for'] ] ) ? ( $options[ $args['label_for'] ] ) : ( '365' ); ?>
			>
			</input>
			<?php esc_html_e( ' days.', 'signups-cron' ); ?>
		</p>
		<?php

	}

	/**
	 * Signups Send Email field callback function.
	 * 
	 * @since	1.0.0
	 * @param 	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_send_email_report_cb( $args ) {

		$options = $this->options;

		if ( ! isset( $options[ $args['label_for'] ] ) ) {
			$options[ $args['label_for'] ] = '0';
		}
		// Get admin email
		$admin_email = get_option('admin_email');

		?>
		<p>
			<?php esc_html_e( 'Email cron report to Site Admin (' . $admin_email . ') ', 'signups-cron' ); ?>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="1"
				<?php checked( $options[ $args['label_for'] ], 1 ) ?>
			>
			</input>
		</p>
		<?php

	}

	/**
	 * Signups Send Email field callback function.
	 * 
	 * @since	1.0.0
	 * @param 	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_cron_schedule_cb( $args ) {

		$options = $this->options;

		if ( ! isset( $options[ $args['label_for'] ] ) ) {
			$options[ $args['label_for'] ] = 'daily';
		}

		?>
		<p>
			<?php esc_html_e( 'Schedule cron to run ', 'signups-cron' ); ?>
			<select
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]">
				<option value="hourly" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'hourly', false ) ) : ( '' ); ?>>
					<?php esc_html_e( 'Hourly', 'signups-cron' ); ?>
				</option>
				<option value="twicedaily" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'twicedaily', false ) ) : ( '' ); ?>>
					<?php esc_html_e( 'Twice Daily', 'signups-cron' ); ?>
				</option>
				<option value="daily" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'daily', false ) ) : ( '' ); ?>>
					<?php esc_html_e( 'Daily', 'signups-cron' ); ?>
				</option>
				<option value="weekly" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'weekly', false ) ) : ( '' ); ?>>
					<?php esc_html_e( 'Weekly', 'signups-cron' ); ?>
				</option>
			</select>
			<?php esc_html_e( ' starting now.', 'signups-cron' ); ?>
		</p>
		<p>
			<?php
				// get timestamp of next cron event
				$scheduled_event_timestamp = wp_next_scheduled( 'signups_cron_event_hook' );

				if ($scheduled_event_timestamp) {
					// get scheduled event display name
					$scheduled_event_display = wp_get_schedules()[wp_get_scheduled_event( 'signups_cron_event_hook' )->schedule]['display'];
					$scheduled_event_datetime = date_format(date_create()->setTimestamp($scheduled_event_timestamp)->setTimezone(new DateTimeZone(wp_timezone_string())), 'F j, Y, g:i a T'); // TODO: Check if site options uses timezone_string or gmt_offset

					echo esc_html_e( "Next cron scheduled for {$scheduled_event_datetime} ({$scheduled_event_display})", 'signups_cron' );
				} else {
					echo esc_html_e( "Cron is not currently scheduled.", 'signups-cron' );
				}
			?>
		</p>
		<?php

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since	1.0.0
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
	 * @since	1.0.0
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
