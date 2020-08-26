<?php
/**
 * General settings screen class
 *
 * @package App_Package
 * @subpackage Administration/Backend
 */

namespace AppNamespace\Backend;

// Stop here if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * General settings screen class
 *
 * Extends the Settings_Screen class.
 *
 * @see class-settings.screen.php
 *
 * @since  1.0.0
 * @access public
 */
class Settings_General extends Settings_Screen {

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string The name of the registered fields to be executed.
	 */
	public $fields = 'general';

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {

		// Return the instance.
		return new self;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		parent :: __construct();

		// Print page scripts to head.
		add_action( 'admin_head', [ $this, 'child_print_scripts' ] );
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the translated title.
	 */
	public function title() {

		$this->title = __( 'Website Settings' );

		return apply_filters( 'settings_general_page_title', $this->title );
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the description markup.
	 */
	public function description() {

		$this->description = sprintf(
			'<p class="description">%1s</p>',
			__( 'Configuration settings for the website.' )
		);

		return apply_filters( 'settings_general_page_description', $this->description );
	}

	/**
	 * Print page scripts to head
	 *
	 * Default script listens for input changes and
	 * updates text accordingly.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the script markup.
	 */
	function child_print_scripts() {

		/**
		 * Print unminified script if in development mode
		 * or in debug mode or compression is off.
		 */
		if ( ( defined( 'APP_DEV_MODE' ) && APP_DEV_MODE )
			|| ( defined( 'APP_DEBUG' ) && APP_DEBUG )
			|| ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
			|| ( defined( 'COMPRESS_SCRIPTS' ) && ! COMPRESS_SCRIPTS ) ) :

	?>
	<script>
		jQuery( document ).ready( function($) {

			$( '.novalidate' ).attr( 'novalidate', 'novalidate' );

			var $siteName = $( '#user-toolbar-site-name' ).children( 'a' ).first(),
				homeURL   = ( <?php echo wp_json_encode( get_home_url() ); ?> || '' ).replace( /^(https?:\/\/)?(www\.)?/, '' );

			$( '#blogname' ).on( 'input', function() {
				var title = $.trim( $( this ).val() ) || homeURL;

				// Truncate to 40 characters.
				if ( 40 < title.length ) {
					title = title.substring( 0, 40 ) + '\u2026';
				}

				$siteName.text( title );
			});

			$( 'input[name="date_format"]' ).click( function() {
				if ( "date_format_custom_radio" != $( this ).attr( 'id' ) )
					$( 'input[name="date_format_custom"]' ).val( $( this ).val() ).closest( 'fieldset' ).find( '.example' ).text( $( this ).parent( 'label' ).children( '.format-i18n' ).text() );
			});

			$( 'input[name="date_format_custom"]' ).on( 'click input', function() {
				$( '#date_format_custom_radio' ).prop( 'checked', true );
			});

			$( 'input[name="time_format"]').click( function() {
				if ( "time_format_custom_radio" != $( this ).attr( 'id' ) )
					$( 'input[name="time_format_custom"]' ).val( $( this ).val() ).closest( 'fieldset' ).find( '.example' ).text( $( this ).parent( 'label' ).children( '.format-i18n' ).text() );
			});

			$( 'input[name="time_format_custom"]' ).on( 'click input', function() {
				$( '#time_format_custom_radio' ).prop( 'checked', true );
			});

			$( 'input[name="date_format_custom"], input[name="time_format_custom"]' ).change( function() {
				var format   = $( this ),
					fieldset = format.closest( 'fieldset' ),
					example  = fieldset.find( '.example' ),
					spinner  = fieldset.find( '.spinner' );

				spinner.addClass( 'is-active' );

				$.post( ajaxurl, {
						action: 'date_format_custom' == format.attr( 'name' ) ? 'date_format' : 'time_format',
						date : format.val()
					}, function( d ) { spinner.removeClass( 'is-active' ); example.text( d ); } );
			});

			var languageSelect = $( '#APP_LANG' );
			$( 'form' ).submit( function() {
				/**
				 * Don't show a spinner for English and installed languages
				 * as there is nothing to download.
				 */
				if ( ! languageSelect.find( 'option:selected' ).data( 'installed' ) ) {
					$( '#submit', this ).after( '<span class="spinner language-install-spinner is-active" />' );
				}
			});
		});
	</script>
	<?php
		// If not in dev or debug mode.
		else :
	?>
	<script>jQuery(document).ready(function(a){a("#settings-general").attr("novalidate","novalidate");var b=a("#user-toolbar-site-name").children("a").first(),homeURL=(<?php echo wp_json_encode( get_home_url() ); ?>||'').replace(/^(https?:\/\/)?(www\.)?/,"");a("#blogname").on("input",function(){var d=a.trim(a(this).val())||c;40<d.length&&(d=d.substring(0,40)+"\u2026"),b.text(d)}),a('input[name="date_format"]').click(function(){"date_format_custom_radio"!=a(this).attr("id")&&a('input[name="date_format_custom"]').val(a(this).val()).closest("fieldset").find(".example").text(a(this).parent("label").children(".format-i18n").text())}),a('input[name="date_format_custom"]').on("click input",function(){a("#date_format_custom_radio").prop("checked",!0)}),a('input[name="time_format"]').click(function(){"time_format_custom_radio"!=a(this).attr("id")&&a('input[name="time_format_custom"]').val(a(this).val()).closest("fieldset").find(".example").text(a(this).parent("label").children(".format-i18n").text())}),a('input[name="time_format_custom"]').on("click input",function(){a("#time_format_custom_radio").prop("checked",!0)}),a('input[name="date_format_custom"], input[name="time_format_custom"]').change(function(){var b=a(this),c=b.closest("fieldset"),d=c.find(".example"),e=c.find(".spinner");e.addClass("is-active"),a.post(ajaxurl,{action:"date_format_custom"==b.attr("name")?"date_format":"time_format",date:b.val()},function(a){e.removeClass("is-active"),d.text(a)})});var d=a("#APP_LANG");a("form").submit(function(){d.find("option:selected").data("installed")||a("#submit",this).after('<span class="spinner language-install-spinner is-active" />')})});</script>
	<?php
		// End if dev or debug mode.
		endif;

	}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tabbed content.
	 */
	public function tabs() {

		$screen = get_current_screen();

		$screen->add_content_tab( [
			'id'         => 'identity',
			'capability' => 'manage_options',
			'tab'        => __( 'Identity' ),
			'icon'       => '',
			'heading'    => __( 'Site Identity' ),
			'content'    => '',
			'callback'   => [ $this, 'identity' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'accounts',
			'capability' => 'manage_options',
			'tab'        => __( 'Accounts' ),
			'icon'       => '',
			'heading'    => __( 'User Accounts' ),
			'content'    => '',
			'callback'   => [ $this, 'accounts' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'archival',
			'capability' => 'manage_options',
			'tab'        => __( 'Archival' ),
			'icon'       => '',
			'heading'    => __( 'Date & Time Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'archival' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'language',
			'capability' => 'manage_options',
			'tab'        => __( 'Language' ),
			'icon'       => '',
			'heading'    => __( 'Language Settings' ),
			'content'    => '',
			'callback'   => [ $this, 'language' ]
		] );
	}

	/**
	 * Site Identity tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function identity() {

		?>
		<div class="tab-section-wrap tab-section-wrap__identity">

			<?php if ( current_user_can( 'customize' ) && has_action( 'plugins_loaded', 'live_manager_include' ) ) : ?>
			<p><?php _e( 'Some of these settings can be edited with a' ) ?> <a href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=title_tagline' ); ?>"><?php _e( 'live preview.' ) ?></a></p>
			<?php endif; ?>

			<p>
				<label for="blogname"><?php _e( 'Site Name' ) ?></label>
				<br /><input name="blogname" type="text" id="blogname" value="<?php form_option( 'blogname' ); ?>" class="regular-text" />
			</p>

			<p>
				<label for="blogdescription"><?php _e( 'Site Description' ) ?></label>
				<br /><input name="blogdescription" type="text" id="blogdescription" aria-describedby="tagline-description" value="<?php form_option( 'blogdescription' ); ?>" class="regular-text" />
				<br /><span class="description" id="tagline-description"><?php _e( 'In a few words, explain what this site is about.' ) ?></span>
			</p>

			<?php if ( ! is_network() ) { ?>

			<p>
				<label for="siteurl"><?php _e( 'Application Address (URL)' ) ?></label>
				<br /><input name="siteurl" type="url" id="siteurl" value="<?php form_option( 'siteurl' ); ?>"<?php disabled( defined( 'APP_SITEURL' ) ); ?> class="regular-text code<?php if ( defined( 'APP_SITEURL' ) ) echo ' disabled' ?>" />
			</p>

			<p>
				<label for="home"><?php _e( 'Site Address (URL)' ) ?></label>
				<br /><input name="home" type="url" id="home" aria-describedby="home-description" value="<?php form_option( 'home' ); ?>"<?php disabled( defined( 'APP_HOME' ) ); ?> class="regular-text code<?php if ( defined( 'APP_HOME' ) ) echo ' disabled' ?>" />
				<?php if ( ! defined( 'APP_HOME' ) ) : ?>
				<br /><span class="description" id="home-description"><?php _e( 'Enter the address here if you want your site home page to be different from your application installation directory.' ); ?></span>
				<?php endif; ?>
			</p>

			<?php } // is_network() ?>
		</div>
		<?php
	}

	/**
	 * Time & Date tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function accounts() {

		?>
		<div class="tab-section-wrap tab-section-wrap__accounts">

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'Site Administration' ) ?></legend>

				<h3><?php _e( 'Site Administration' ) ?></h3>

				<p>
					<label for="new_admin_email"><?php _e( 'Administrative Email' ); ?></label>
					<br /><input name="new_admin_email" type="email" id="new_admin_email" aria-describedby="new-admin-email-description" value="<?php form_option( 'admin_email' ); ?>" class="regular-text ltr" />
					<br /><span class="description" id="new-admin-email-description"><?php _e( 'This address is used for admin purposes.' ); ?></span>
				</p>
				<p id="new-admin-email-description"><?php _e( 'If you change the administrative email the system will send you an email at your new address to confirm it. The new address will not become active until confirmed.' ); ?></p>

				<?php
				$new_admin_email = get_option( 'new_admin_email' );

				if ( $new_admin_email && $new_admin_email != get_option( 'admin_email' ) ) :

				?>
				<div class="updated inline">
					<p><?php
						printf(
							// Translators: %s: new admin email.
							__( 'There is a pending change of the admin email to %s.' ),
							'<code>' . esc_html( $new_admin_email ) . '</code>'
						);
						printf(
							' <a href="%1$s">%2$s</a>',
							esc_url( wp_nonce_url( admin_url( 'options.php?dismiss=new_admin_email' ), 'dismiss-' . get_current_blog_id() . '-new_admin_email' ) ),
							__( 'Cancel' )
						);
					?></p>
				</div>
				<?php endif; ?>
			</fieldset>

			<?php if ( ! is_network() ) { ?>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'User Registration' ) ?></legend>

				<h3><?php _e( 'User Registration' ) ?></h3>

				<p>
					<label for="users_can_register"><?php _e( 'Site Membership' ) ?></label>
					<br /><input name="users_can_register" type="checkbox" id="users_can_register" value="1" <?php checked( '1', get_option( 'users_can_register' ) ); ?> /> <span class="description"><?php _e( 'Anyone can register' ) ?></span>
				</p>
				<p>
					<label for="default_role"><?php _e( 'New User Default Role' ) ?></label>
					<br /><select name="default_role" id="default_role"><?php wp_dropdown_roles( get_option( 'default_role' ) ); ?></select>
				</p>
			</fieldset>
			<?php } // ! is_network() ?>
		</div>
		<?php
	}

	/**
	 * Time & Date tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function archival() {

		$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
		$current_offset  = get_option( 'gmt_offset' );
		$tzstring        = get_option( 'timezone_string' );
		$check_zone_info = true;

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( false !== strpos( $tzstring,'Etc/GMT' ) ) {
			$tzstring = '';
		}

		// Create a UTC+- zone if no timezone string exists.
		if ( empty( $tzstring ) ) {

			$check_zone_info = false;

			if ( 0 == $current_offset ) {
				$tzstring = 'UTC+0';
			} elseif ( $current_offset < 0 ) {
				$tzstring = 'UTC' . $current_offset;
			} else {
				$tzstring = 'UTC+' . $current_offset;
			}
		}

		?>
		<div class="tab-section-wrap tab-section-wrap__time-date">

			<p><a href="<?php echo esc_url( 'https://www.php.net/manual/en/datetime.format.php' ); ?>" target="_blank" rel="noindex, nofollow"><?php _e( 'Documentation on date and time formatting.' ); ?></a></p>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'Date Format' ) ?></legend>

				<h3 class="form-label"><?php _e( 'Date Format' ) ?></h3>

				<ul class="form-field-list">
					<?php
						/**
						* Filters the default date formats.
						*
						* @since Previous 2.7.0
						* @since Previous 4.0.0 Added ISO date standard YYYY-MM-DD format.
						* @param array $default_date_formats Array of default date formats.
						*/
						$date_formats = array_unique( apply_filters( 'date_formats', [ __( 'F j, Y' ), 'Y-m-d', 'm/d/Y', 'd/m/Y' ] ) );
						$custom       = true;

						foreach ( $date_formats as $format ) {

							echo "\t<li><label class='check-label'><input type='radio' name='date_format' value='" . esc_attr( $format ) . "'";

							// checked() uses "==" rather than "==="
							if ( get_option( 'date_format' ) === $format ) {
								echo " checked='checked'";
								$custom = false;
							}

							echo ' /> <span class="date-time-text format-i18n">' . date_i18n( $format ) . '</span><code>' . esc_html( $format ) . '</code></label></li>';
						}
					?>
					<li>
						<label class="check-label"><input type="radio" name="date_format" id="date_format_custom_radio" value="\c\u\s\t\o\m"<?php echo checked( $custom ); ?>/>

						<?php echo ' <span class="date-time-text date-time-custom-text">' . __( 'Custom:' ) . '<span class="screen-reader-text"> ' . __( 'enter a custom date format in the following field' ) . '</span></span></label>' .
							'<label for="date_format_custom" class="screen-reader-text">' . __( 'Custom date format:' ) . '</label>' .
							'<input type="text" name="date_format_custom" id="date_format_custom" value="' . esc_attr( get_option( 'date_format' ) ) . '" class="small-text" />' .
							'<br />' .
							'<p><strong>' . __( 'Preview:' ) . '</strong> <span class="example">' . date_i18n( get_option( 'date_format' ) ) . '</span>' .
							"<span class='spinner'></span>\n" . '</p>';
						?>
					</li>
				</ul>
			</fieldset>

			<p>
				<label for="start_of_week"><?php _e( 'Week Starts On' ) ?></label>
				<br />
				<select name="start_of_week" id="start_of_week">
					<?php
					/**
					 * @global WP_Locale $wp_locale
					 */
					global $wp_locale;

					for ( $day_index = 0; $day_index <= 6; $day_index++) :
						$selected = (get_option( 'start_of_week' ) == $day_index) ? 'selected="selected"' : '';
						echo "\n\t<option value='" . esc_attr( $day_index) . "' $selected>" . $wp_locale->get_weekday( $day_index) . '</option>';
					endfor;
					?>
				</select>
			</p>

			<fieldset form="<?php echo $this->fields . '-settings'; ?>">

				<legend class="screen-reader-text"><?php _e( 'Time Format' ) ?></legend>

				<h3 class="form-label"><?php _e( 'Time Format' ) ?></h3>

				<ul class="form-field-list">
				<?php
				/**
				* Filters the default time formats.
				*
				* @since Previous 2.7.0
				* @param array $default_time_formats Array of default time formats.
				*/
				$time_formats = array_unique( apply_filters( 'time_formats', [ __( 'g:i a' ), 'g:i A', 'H:i' ] ) );
				$custom       = true;

				foreach ( $time_formats as $format ) {

					echo "<li><label class='check-label'><input type='radio' name='time_format' value='" . esc_attr( $format ) . "'";

					// checked() uses "==" rather than "==="
					if ( get_option( 'time_format' ) === $format ) {
						echo " checked='checked'";
						$custom = false;
					}

					echo ' /> <span class="date-time-text format-i18n">' . date_i18n( $format ) . '</span><code>' . esc_html( $format ) . "</code></label></li>";
				}

				echo '<li><label><input type="radio" name="time_format" id="time_format_custom_radio" value="\c\u\s\t\o\m"';

				checked( $custom );

				echo '/> <span class="date-time-text date-time-custom-text">' . __( 'Custom:' ) . '<span class="screen-reader-text"> ' . __( 'enter a custom time format in the following field' ) . '</span></span></label>' .
					'<label for="time_format_custom" class="screen-reader-text">' . __( 'Custom time format:' ) . '</label>' .
					'<input type="text" name="time_format_custom" id="time_format_custom" value="' . esc_attr( get_option( 'time_format' ) ) . '" class="small-text" />' .
					'<br />' .
					'<p><strong>' . __( 'Preview:' ) . '</strong> <span class="example">' . date_i18n( get_option( 'time_format' ) ) . '</span>' .
					"<span class='spinner'></span></li>" . '</p>';
				?>
				</ul>

			</fieldset>

			<p>
				<label for="timezone_string"><?php _e( 'Set Time Zone' ) ?></label>
				<br />
				<select id="timezone_string" name="timezone_string" aria-describedby="timezone-description">
					<?php echo wp_timezone_choice( $tzstring, get_user_locale() ); ?>
				</select>
				<br /><span class="description" id="timezone-description"><?php _e( 'Choose either a city in the same timezone as you or a UTC timezone offset.' ); ?></span>
			</p>

			<p class="timezone-info">
				<span id="utc-time">
				<?php
					printf( __( 'Universal time (%1$s) is %2$s.' ),
						'<abbr>' . __( 'UTC' ) . '</abbr>',
						'<code>' . date_i18n( $timezone_format, false, true ) . '</code>'
					);
				?></span>
			<?php if ( get_option( 'timezone_string' ) || ! empty( $current_offset ) ) : ?>
				<span id="local-time">
				<?php
					printf( __( 'Local time is %s.' ),
						'<code>' . date_i18n( $timezone_format ) . '</code>'
					);
				?></span>
			<?php endif; ?>
			</p>

			<?php if ( $check_zone_info && $tzstring ) : ?>
			<p class="timezone-info">
				<span>
				<?php
				date_default_timezone_set( $tzstring );
				$now = localtime( time(), true );

				if ( $now['tm_isdst'] ) {
					_e( 'This timezone is currently in daylight saving time.' );
				} else {
					_e( 'This timezone is currently in standard time.' );
				}
				?>
				<br />
				<?php
				$allowed_zones = timezone_identifiers_list();

				if ( in_array( $tzstring, $allowed_zones ) ) {

					$found = false;
					$date_time_zone_selected = new \DateTimeZone( $tzstring );

					$tz_offset = timezone_offset_get( $date_time_zone_selected, date_create());
					$right_now = time();

					foreach ( timezone_transitions_get( $date_time_zone_selected ) as $tr ) {

						if ( $tr['ts'] > $right_now ) {
							$found = true;

							break;
						}
					}

					if ( $found ) {

						if ( $tr['isdst'] ) {
							$message = __( 'Daylight saving time begins on: %s.' );
						} else {
							$message = __( 'Standard time begins on: %s.' );
						}

						echo ' ';

						// Add the difference between the current offset and the new offset to ts to get the correct transition time from date_i18n().
						printf( $message,
							'<code>' . date_i18n(
								__( 'F j, Y' ) . ' ' . __( 'g:i a' ),
								$tr['ts'] + ( $tz_offset - $tr['offset'] )
							) . '</code>'
						);

					} else {
						_e( 'This timezone does not observe daylight saving time.' );
					}
				}

				// Set back to UTC.
				date_default_timezone_set( 'UTC' );

				?>
				</span>
			</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Language tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function language() {

		// Load the translation installation API.
		require_once( APP_INC_PATH . '/backend/translation-install.php' );

		?>
		<div class="tab-section-wrap tab-section-wrap__time-date">

			<?php
			$languages    = get_available_languages();
			$translations = wp_get_available_translations();

			if ( ! is_network() && defined( 'APP_LANG' ) && '' !== APP_LANG && 'en_US' !== APP_LANG && ! in_array( APP_LANG, $languages ) ) {
				$languages[] = APP_LANG;
			}

			/**
			 * Print if the external languages API is available
			 *
			 * This is a vestige so the condition is replaced with
			 * a dummy condition until the local languages API
			 * is restored.
			 *
			 * @todo Replace or remove this condition when local
			 *       languages are available.
			 *
			 * if ( ! empty( $languages ) || ! empty( $translations ) ) {
			 */
			if ( ! empty( 'something_fake' ) ) {

			?>
				<fieldset form="<?php echo $this->fields . '-settings'; ?>">

					<legend class="screen-reader-text"><?php _e( 'Default Languages' ); ?></legend>

					<h3><?php _e( 'Default Language' ); ?></h3>

					<p><?php _e( 'The following is under development. English is currently the only language available.' ); ?></p>

					<p>
						<label for="APP_LANG"><?php _e( 'Site Languages' ); ?></label>
						<br />
						<?php
						$locale = get_locale();
						if ( ! in_array( $locale, $languages ) ) {
							$locale = '';
						}

						wp_dropdown_languages( [
							'name'         => 'APP_LANG',
							'id'           => 'APP_LANG',
							'selected'     => $locale,
							'languages'    => $languages,
							'translations' => $translations,
							'show_available_translations' => current_user_can( 'install_languages' ) && wp_can_install_language_pack(),
						] );
						?>
					</p>

				</fieldset>
			<?php } else { ?>
				<p><?php _e( 'No language settings are available.' ); ?></p>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Help content
	 *
	 * Add content to the help section of the page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help() {

		$screen = get_current_screen();

		$screen->add_help_tab( [
			'id'       => $screen->id . '-overview',
			'title'    => __( 'Overview' ),
			'content'  => null,
			'callback' => [ $this, 'help_overview' ],
		] );
	}

	/**
	 * Overview help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_overview() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Overview' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'The fields on this screen determine some of the basics of your site setup.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'Most themes display the site title at the top of every page, in the title bar of the browser, and as the identifying name for syndicated feeds. The description, or tagline, is also displayed by many themes.' )
		);

		if ( ! is_network() ) {

			$help .= sprintf(
				'<p>%1s</p>',
				__( 'The Application URL and the Site URL can be the same (example.com) or different; for example, having the core files (example.com/blog) in a subdirectory instead of the root directory.' )
			);

			$help .= sprintf(
				'<p>%1s</p>',
				__( 'If you want site visitors to be able to register themselves, as opposed to by the site administrator, check the membership box. A default user role can be set for all new users, whether self-registered or registered by the site admin.' )
			);
		}

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You can set the language, and the translation files will be automatically downloaded and installed (available if your filesystem is writable).' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'UTC means Coordinated Universal Time.' )
		);

		$help .= sprintf(
			'<p>%1s</p>',
			__( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' )
		);

		$help = apply_filters( 'help_settings_general_overview', $help );

		echo $help;

	}
}
