<?php
/**
 * Upgrader API: Automatic_Upgrader_Skin class
 *
 * @package App_Package
 * @subpackage Upgrader
 * @since Previous 4.6.0
 */

/**
 * Upgrader Skin for Automatic Upgrades
 *
 * This skin is designed to be used when no output is intended, all output
 * is captured and stored for the caller to process and log/email/discard.
 *
 * @since Previous 3.7.0
 * @since Previous 4.6.0 Moved to its own file from APP_ADMIN_DIR/includes/class-wp-upgrader-skins.php.
 *
 * @see Bulk_Upgrader_Skin
 */
class Automatic_Upgrader_Skin extends WP_Upgrader_Skin {

	/**
	 * Prepare messages array.
	 *
	 * @since  3.7.0
	 * @access protected
	 * @var array
	 */
	protected $messages = [];

	/**
	 * Determines whether the upgrader needs FTP/SSH details in order to connect
	 * to the filesystem.
	 *
	 * @see request_filesystem_credentials()
	 *
	 * @since Previous 3.7.0
	 * @since Previous 4.6.0 The `$context` parameter default changed from `false` to an empty string.
	 * @access public
	 * @param bool $error Optional. Whether the current request has failed to connect.
	 *                    Default false.
	 * @param string $context Optional. Full path to the directory that is tested
	 *                        for being writable. Default empty.
	 * @param bool $allow_relaxed_file_ownership Optional. Whether to allow Group/World writable.
	 *                                           Default false.
	 * @return bool True on success, false on failure.
	 */
	public function request_filesystem_credentials( $error = false, $context = '', $allow_relaxed_file_ownership = false ) {

		if ( $context ) {
			$this->options['context'] = $context;
		}

		/**
		 * This will output a credentials form in event of failure.
		 * We don't want that so just hide with a buffer.
		 *
		 * @todo Fix up request_filesystem_credentials(), or split it, to allow us to request a no-output version.
		 */
		ob_start();
		$result = parent::request_filesystem_credentials( $error, $context, $allow_relaxed_file_ownership );
		ob_end_clean();

		return $result;
	}

	/**
	 * Get upgrade messages
	 *
	 * @since  3.7.0
	 * @access public
	 * @return array
	 */
	public function get_upgrade_messages() {
		return $this->messages;
	}

	/**
	 * Feedback
	 *
	 * @since  3.7.0
	 * @access public
	 * @param string|array|WP_Error $data
	 */
	public function feedback( $data ) {

		if ( is_wp_error( $data ) ) {
			$string = $data->get_error_message();
		} elseif ( is_array( $data ) ) {
			return;
		} else {
			$string = $data;
		}

		if ( ! empty( $this->upgrader->strings[ $string ] ) ) {
			$string = $this->upgrader->strings[ $string ];
		}

		if ( strpos( $string, '%' ) !== false ) {

			$args = func_get_args();
			$args = array_splice( $args, 1 );

			if ( ! empty( $args ) ) {
				$string = vsprintf( $string, $args );
			}
		}

		$string = trim( $string );

		/**
		 * Only allow basic HTML in the messages as it will be used
		 * in emails/logs rather than direct browser output.
		 */
		$string = wp_kses( $string, [
			'a' => [
				'href' => true
			],
			'br'     => true,
			'em'     => true,
			'strong' => true,
		] );

		if ( empty( $string ) ) {
			return;
		}

		$this->messages[] = $string;
	}

	/**
	 * Header
	 *
	 * @since  3.7.0
	 * @access public
	 */
	public function header() {
		ob_start();
	}

	/**
	 * Footer
	 *
	 * @since  3.7.0
	 * @access public
	 */
	public function footer() {

		$output = ob_get_clean();

		if ( ! empty( $output ) ) {
			$this->feedback( $output );
		}
	}
}
