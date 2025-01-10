<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/scottmotion/WP-Signups-Cron/
 * @since      1.0.0
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Signups_Cron
 * @subpackage Signups_Cron/admin
 * @author     Scott Winn <hello@scottwinn.dev>
 */
class Signups_Cron_Admin {

    /**
	 * The class responsible for getting the signups table info.
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
		$this->default_options = array(								// Default option value for 'signups_cron_settings'.
			'signups_cron_field_active_enabled'			=> '0',		// Active Signups cron enabled.
			'signups_cron_field_active_threshold'		=> '365',	// Active Signups cron threshold.
			'signups_cron_field_pending_enabled'		=> '0',		// Pending Signups cron enabled.
			'signups_cron_field_pending_threshold'		=> '365',	// Pending Signups cron threshold.
			'signups_cron_field_send_email_report'		=> '0',		// Send cron email report enabled.
			'signups_cron_field_cron_schedule'			=> 'daily'	// Cron schedule recurrence.
		);

	}

	/**
	 * Get the Signups Cron options.
	 *
	 * @since 1.0.0
	 */
	public function load_signups_cron_options() {

		$this->options = get_option( 'signups_cron_settings' );

		$this->options = wp_parse_args( $this->options, $this->default_options );

	}

	/**
	 * Get the Signups Cron options.
	 *
	 * @since 1.0.0
	 */
	public function load_signups_cron_table_info() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-signups-cron-table-info.php';
		$this->signups_table_info = new Signups_Cron_Table_Info();

	}

	/**
	 * Register the 'Signups Cron' admin submenu page and insert under the 'Users' page.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_page() {

		// TODO: Move multisite/BP_VERSION checks to define_admin_hooks() ?
		// TODO: When page is accessed (i.e. by 'Settings' link) it displays "Sorry, you are not allowed to access this page." Show admin warning?
		if ( is_multisite() ) {
			return;
		}

		add_submenu_page(
			'users.php',								// $parent_slug	string		required - The slug name for the parent menu (or the file name of a standard WordPress admin page).
			__('Signups Cron', 'signups-cron'),			// $page_title	string		required - The text to be displayed in the title tags of the page when the menu is selected.
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

		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// check for multisite and skip render
		if ( is_multisite() ) {

			?>
				<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<hr>
				<p>
				<?php
					esc_html_e( 'Signups Cron is not yet compatible with Multisite installations.', 'signups-cron' );
				?>
				</p>
			<?php

			return;
		}

		// check for BuddyPress 2.0 and skip render
		if ( !defined('BP_VERSION') || version_compare( BP_VERSION, '2.0', '<' ) ) {

			?>
				<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<hr>
				<p>
				<?php
					if ( !defined('BP_VERSION') ) {
						printf(
							esc_html__( 'BuddyPress is not active. This plugin requires BuddyPress 2.0 or later.', 'signups-cron' )
						);
					} else {
						printf(
							/* translators: Software version number */
							esc_html__( 'BuddyPress %s active. This plugin requires BuddyPress 2.0 or later.', 'signups-cron' ),
							esc_html(BP_VERSION)
						);
					}
				?>
				</p>
			<?php

			return;
		}		
	
		// show error/update messages
		settings_errors( 'general' );

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<hr>
			<?php
				do_settings_sections( 'signups_cron_page_information' );
			?>
			<hr>
			<form name="settings" action="options.php" method="post">
				<?php
				// output security fields for the registered setting
				settings_fields( 'signups_cron_group_settings' );

				// output setting sections and their fields
				do_settings_sections( 'signups_cron_page_settings' );

				// output save settings button for this form.
				submit_button( __( 'Save Settings', 'signups-cron' ) );
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
		 * @param   string      $option_group               Setting (option) group. (Rendered as hidden input by settings_fields() and submitted to options.php as $option_page)
		 * @param   string      $option_name                Setting (option) name.
		 * @param   array       $args                       Array of setting registration arguments.
		 * @param   string      $args['type']               The type of data associated with this setting.
		 * @param   string      $args['label']              A label of the data attached to this setting.
		 * @param   string      $args['description']        A description of the data attached to this setting.
		 * @param   callable    $args['sanitize_callback']  A callback function that sanitizes the option’s value. // Called by form submit to options.php->update_option() (& maybe add_option() if update fails)
		 * @param   bool|array  $args['show_in_rest']       Whether data associated with this setting should be included in the REST API.
		 * @param   mixed       $args['default']            Default value when calling get_option().
		 */

		// register_setting( 'signups_cron_group_information', 'signups_cron_information' ); // registered as $allowed_option['option_group']['option_name'] and used by options.php
		// from option.php: if ( ! empty( $args['sanitize_callback'] ) ) {add_filter( "sanitize_option_{$option_name}", $args['sanitize_callback'] );}
		// from option.php: if ( array_key_exists( 'default', $args ) ) {add_filter( "default_option_{$option_name}", 'filter_default_option', 10, 3 );}
		register_setting(
			'signups_cron_group_settings',
			'signups_cron_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'signups_cron_settings_sanitize_cb' )
			)
		);
	
	}

	/**
	 * Sanitize registered setting 'signups_cron_settings'.
	 *
	 * @since	1.0.0
	 */
	public function signups_cron_settings_sanitize_cb( $input ) {

		// Create our output array for storing the validated options.
		$output = array();
		
		// Checkbox input keys to process
		$allowed_keys_checkbox = array( 'signups_cron_field_active_enabled', 'signups_cron_field_pending_enabled', 'signups_cron_field_send_email_report' );
		// Allowed values for checkboxes: 1. See <input type="checkbox" value="1">.
		$allowed_values_checkbox = 1;

		// Threshold number input keys to process.
		$allowed_keys_threshold = array( 'signups_cron_field_active_threshold', 'signups_cron_field_pending_threshold' );
		// Allowed values for threshold numbers: 0 - 999. See <input type="number" min="0" max="999">.
		$allowed_values_threshold_min = 0;
		$allowed_values_threshold_max = 999;

		// Schedule select options keys to process
		$allowed_keys_schedule = array( 'signups_cron_field_cron_schedule' );
		// Allowed values for schedule select options. Copied from WP default schedules. See <select><option value=*>.
		$allowed_values_schedule = array( 'hourly', 'twicedaily', 'daily', 'weekly' );

		 // All allowable keys. Corresponds to default options keys.
		$all_allowed_keys = array_merge( $allowed_keys_checkbox, $allowed_keys_threshold, $allowed_keys_schedule );

		// Loop through each of the incoming options.
		foreach( $input as $key => $value ) {
			
			// Check if input is not any of allowed and exit (continue) foreach loop.
			if ( !in_array( $key, $all_allowed_keys ) ) {
				unset( $input[$key] );
				continue;
			}
			
			// Check to see if the current option has a value. If so, process it. 
			if ( isset( $input[$key] ) ) {

				// Check if key is for checkbox
				if ( in_array( $key, $allowed_keys_checkbox ) ) {
					if ( $input[$key] != $allowed_values_checkbox ) {
						continue;
					};
				}

				// Check if key is for threshold number
				if ( in_array( $key, $allowed_keys_threshold ) ) {
					$min = $allowed_values_threshold_min;
					$max = $allowed_values_threshold_max;
					if ( filter_var( $input[$key], FILTER_VALIDATE_INT, array( "options" => array( "min_range"=>$min, "max_range"=>$max ) ) ) === false ) {
						continue;
					};
				}

				// Check if key is schedule select options
				if ( in_array( $key, $allowed_keys_schedule ) ) {
					if ( !in_array( $input[$key], $allowed_values_schedule ) ) {
						continue;
					};
				}

				// Strip all HTML and PHP tags and properly handle quoted strings 
				$output[$key] = wp_strip_all_tags( stripslashes( $input[$key] ) );

			} // end if 
			
		} // end foreach 
		
		// Return the array processing any additional functions filtered by this action 
		return apply_filters( 'signups_cron_settings_sanitize_cb', $output, $input );

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
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Information about the signups table retrieved from the site database.', 'signups-cron' ); ?></p>
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
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Enable a cron event that will remove active and/or pending signups from the database.', 'signups-cron' ); ?></p>
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
		 * @param string    $id					Slug-name to identify the field. Used in the 'id' attribute of tags. // As of WP 4.6 this value is used only internally.
		 * @param string    $title				Formatted title of the field. Shown as the label for the field during output.
		 * @param callable  $callback			Function that fills the field with the desired form inputs. The function should echo its output. // Called internally by do_settings_sections()->do_settings_fields()->call_user_func()
		 * @param string    $page				The slug-name of the settings page on which to show the section.
		 * @param string	$section			The slug-name of the section of the settings page in which to show the box.
		 * @param array     $args				Extra arguments that get passed to the callback function.
		 * @param string	$args['label_for']	When supplied, the setting title will be wrapped in a <label> element, its 'for' attribute populated with this value.
		 * @param string	$args['class]		CSS Class to be added to the <tr> element when the field is output.
		 */

		// Information section fields.
		add_settings_field(
			'signups_cron_field_signups_information',
			__( 'Signups Table Information', 'signups-cron' ),
			array( $this, 'signups_cron_field_signups_information_cb' ),
			'signups_cron_page_information',
			'signups_cron_section_information'
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
			__( 'Cron Event Schedule', 'signups-cron' ),
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
	 * All fields callbacks are called internally by do_settings_sections()->do_settings_fields()
	 *
	 * @since	1.0.0
	 * @param	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_signups_information_cb( $args ) {

		$this->load_signups_cron_table_info();
		$data = $this->signups_table_info->get_signups_table_info();

		?>
		<table class="signups-table-info">
			<tr>
                <td><?php esc_html_e( 'Signups Table Size:', 'signups-cron' ) ?></td>
                <td><?php echo number_format( $data["signups_table_size"], 2, '.' ); ?> MB</td>
            </tr>
            <tr>
                <td><?php esc_html_e( 'Total Signups Count:', 'signups-cron' ) ?></td>
                <td><?php echo number_format( $data["signups_count_total"] ); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e( 'Active Signups Count:', 'signups-cron' ) ?></td>
                <td><?php echo number_format( $data["signups_count_active"] ); ?></td>
            </tr>
            <tr>
                <td><?php esc_html_e( 'Pending Signups Count:', 'signups-cron' ) ?></td>
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

		?>
		<label>
			<?php esc_html_e( 'Enable Active signups cron ', 'signups-cron' ); ?>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="1"
				<?php checked( $options[ $args['label_for'] ], 1 ); ?>
			>
			</input>
		</label>
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
		<label>
			<?php esc_html_e( 'Delete Active signups after ', 'signups-cron' ); ?>
			<input
				type="number"
				min="0"
				max="999"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value=<?php echo esc_attr( $options[ $args['label_for'] ] ); ?>
			>
			</input>
			<?php esc_html_e( ' days.', 'signups-cron' ); ?>
		</label>
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

		?>
		<label>
			<?php esc_html_e( 'Enable Pending signups cron ', 'signups-cron' ); ?>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="1"
				<?php checked( $options[ $args['label_for'] ], 1 ); ?>
			>
			</input>
		</label>
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
		<label>
			<?php esc_html_e( 'Delete Pending signups after ', 'signups-cron' ); ?>
			<input
				type="number"
				min="0"
				max="999"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value=<?php echo esc_attr( $options[ $args['label_for'] ] ); ?>
			>
			</input>
			<?php esc_html_e( ' days.', 'signups-cron' ); ?>
		</label>
		<?php

	}

	/**
	 * Cron Email Report field callback function.
	 * 
	 * @since	1.0.0
	 * @param 	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_send_email_report_cb( $args ) {

		$options = $this->options;

		// Get admin email
		// $admin_email = get_option('admin_email');

		?>
		<label>
			<?php esc_html_e( 'Email a report to the Site Admin ', 'signups-cron' ); ?>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
				value="1"
				<?php checked( $options[ $args['label_for'] ], 1 ); ?>
			>
			</input>
		</label>
		<?php

	}

	/**
	 * Cron Event Schedule field callback function.
	 * 
	 * @since	1.0.0
	 * @param 	array $args  The settings array ($id, $title, $callback, $page, $section, $args).
	 */
	public function signups_cron_field_cron_schedule_cb( $args ) {

		$options = $this->options;

		?>
		<label>
			<?php esc_html_e( 'Schedule the event to run ', 'signups-cron' ); ?>
			<select
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="signups_cron_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
			>
				<option
					value="hourly"
					<?php selected( $options[ $args['label_for'] ], 'hourly' ); ?>
				>
					<?php esc_html_e( 'Hourly', 'signups-cron' ); ?>
				</option>
				<option
					value="twicedaily"
					<?php selected( $options[ $args['label_for'] ], 'twicedaily' ); ?>
				>
					<?php esc_html_e( 'Twice Daily', 'signups-cron' ); ?>
				</option>
				<option
					value="daily"
					<?php selected( $options[ $args['label_for'] ], 'daily' ); ?>
				>
					<?php esc_html_e( 'Daily', 'signups-cron' ); ?>
				</option>
				<option
					value="weekly"
					<?php selected( $options[ $args['label_for'] ], 'weekly' ); ?>
				>
					<?php esc_html_e( 'Weekly', 'signups-cron' ); ?>
				</option>
			</select>
			<?php esc_html_e( ' starting now.', 'signups-cron' ); ?>
		</label>
		<p>
			<?php
				// get timestamp of next cron event
				$scheduled_event_timestamp = wp_next_scheduled( 'signups_cron_event_hook' );

				if ($scheduled_event_timestamp) {

					// Get scheduled event display name
					$scheduled_event_display = wp_get_schedules()[wp_get_scheduled_event( 'signups_cron_event_hook' )->schedule]['display'];

					// Convert scheduled event timestamp to date
					$scheduled_event_datetime = wp_date('F j, Y, g:i a T', $scheduled_event_timestamp);

					printf(
						/* translators: 1: Date 2: Interval of time */
						esc_html__( 'Next cron event scheduled for %1$s (%2$s)', 'signups-cron' ),
						esc_html($scheduled_event_datetime),
						esc_html($scheduled_event_display)
					);
				} else {
					esc_html_e( "Cron event is not currently scheduled.", 'signups-cron' );
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

		wp_enqueue_style( $this->signups_cron, plugin_dir_url( __FILE__ ) . 'css/signups-cron-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since	1.0.0
	 */
	// public function enqueue_scripts() {

	// 	wp_enqueue_script( $this->signups_cron, plugin_dir_url( __FILE__ ) . 'js/signups-cron-admin.js', array( 'jquery' ), $this->version, false );

	// }

}
