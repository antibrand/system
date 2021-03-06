<?php
/**
 * Upgrader API: Bulk_Upgrader_Skin class
 *
 * @package App_Package
 * @subpackage Upgrader
 * @since Previous 4.6.0
 */

/**
 * Generic Bulk Upgrader Skin for Upgrades.
 *
 * @since Previous 3.0.0
 *
 * @see WP_Upgrader_Skin
 */
class Bulk_Upgrader_Skin extends WP_Upgrader_Skin {

	/**
	 * In the loop
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @var    boolean|false
	 */
	public $in_loop = false;

	/**
	 * Error messages
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @var    string|false
	 */
	public $error = false;

	/**
	 * Constructor method
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param  array $args
	 * @return self
	 */
	public function __construct( $args = [] ) {

		$defaults = [
			'url'   => '',
			'nonce' => ''
		];

		$args     = wp_parse_args( $args, $defaults );

		parent::__construct( $args );
	}

	/**
	 * Add strings
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function add_strings() {

		$this->upgrader->strings['skin_upgrade_start'] = __( 'The update process is starting. This process may take a while on some hosts, so please be patient.' );

		$this->upgrader->strings['skin_update_failed_error'] = __( 'An error occurred while updating %1$s: %2$s' );

		$this->upgrader->strings['skin_update_failed'] = __( 'The update of %1$s failed.' );

		$this->upgrader->strings['skin_update_successful'] = __( '%1$s updated successfully.' );

		$this->upgrader->strings['skin_upgrade_end'] = __( 'All updates have been completed.' );
	}

	/**
	 * Feedback
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param  string $string
	 * @return string
	 */
	public function feedback( $string ) {

		if ( isset( $this->upgrader->strings[$string] ) ) {
			$string = $this->upgrader->strings[$string];
		}

		if ( strpos($string, '%' ) !== false ) {

			$args = func_get_args();
			$args = array_splice( $args, 1 );

			if ( $args ) {

				$args   = array_map( 'strip_tags', $args );
				$args   = array_map( 'esc_html', $args );
				$string = vsprintf( $string, $args );
			}
		}

		if ( empty( $string ) ) {
			return;
		}

		if ( $this->in_loop ) {
			echo "$string<br />\n";
		} else {
			echo "<p>$string</p>\n";
		}
	}

	/**
	 * Header
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function header() {
		// This will be displayed within a iframe.
	}

	/**
	 * Footer
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return vois
	 */
	public function footer() {
		// This will be displayed within a iframe.
	}

	/**
	 * Error messages
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param  string|WP_Error $error
	 * @return mixed
	 */
	public function error( $error ) {

		if ( is_string( $error ) && isset( $this->upgrader->strings[$error] ) ) {
			$this->error = $this->upgrader->strings[$error];
		}

		if ( is_wp_error( $error ) ) {

			$messages = [];

			foreach ( $error->get_error_messages() as $emessage ) {

				if ( $error->get_error_data() && is_string( $error->get_error_data() ) ) {
					$messages[] = $emessage . ' ' . esc_html( strip_tags( $error->get_error_data() ) );
				} else {
					$messages[] = $emessage;
				}
			}

			$this->error = implode( ', ', $messages);
		}

		echo '<script type="text/javascript">jQuery(\'.waiting-' . esc_js($this->upgrader->update_current) . '\' ).hide();</script>';
	}

	/**
	 * Bulk header
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function bulk_header() {
		$this->feedback( 'skin_upgrade_start' );
	}

	/**
	 * Bulk footer
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function bulk_footer() {
		$this->feedback( 'skin_upgrade_end' );
	}

	/**
	 * Before
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param string $title
	 * @return void
	 */
	public function before( $title = '' ) {

		$this->in_loop = true;

		printf( '<h2>' . $this->upgrader->strings['skin_before_update_header'] . ' <span class="spinner waiting-' . $this->upgrader->update_current . '"></span></h2>', $title, $this->upgrader->update_current, $this->upgrader->update_count );

		echo '<script type="text/javascript">jQuery(\'.waiting-' . esc_js( $this->upgrader->update_current ) . '\' ).css("display", "inline-block");</script>';

		// This progress messages div gets moved via JavaScript when clicking on "Show details.".
		echo '<div class="update-messages hide-if-js" id="progress-' . esc_attr( $this->upgrader->update_current ) . '"><p>';

		$this->flush_output();
	}

	/**
	 * After
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @param string $title
	 * @return void
	 */
	public function after( $title = '' ) {

		echo '</p></div>';

		if ( $this->error || ! $this->result ) {

			if ( $this->error ) {
				echo '<div class="error"><p>' . sprintf($this->upgrader->strings['skin_update_failed_error'], $title, '<strong>' . $this->error . '</strong>' ) . '</p></div>';
			} else {
				echo '<div class="error"><p>' . sprintf($this->upgrader->strings['skin_update_failed'], $title) . '</p></div>';
			}

			echo '<script type="text/javascript">jQuery(\'#progress-' . esc_js($this->upgrader->update_current) . '\' ).show();</script>';
		}

		if ( $this->result && ! is_wp_error( $this->result ) ) {

			if ( ! $this->error ) {

				echo '<div class="updated js-update-details" data-update-details="progress-' . esc_attr( $this->upgrader->update_current ) . '">' .
					'<p>' . sprintf( $this->upgrader->strings['skin_update_successful'], $title ) .
					' <button type="button" class="hide-if-no-js button-link js-update-details-toggle" aria-expanded="false">' . __( 'Show details.' ) . '</button>' .
					'</p></div>';
			}

			echo '<script type="text/javascript">jQuery(\'.waiting-' . esc_js($this->upgrader->update_current) . '\' ).hide();</script>';
		}

		$this->reset();
		$this->flush_output();
	}

	/**
	 * Reset
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function reset() {
		$this->in_loop = false;
		$this->error   = false;
	}

	/**
	 * Flush output
	 *
	 * @since  Previous 3.0.0
	 * @access public
	 * @return void
	 */
	public function flush_output() {
		wp_ob_end_flush_all();
		flush();
	}
}
