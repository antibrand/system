<?php
/**
 * Error API
 *
 * Contains the Error_Messages class and the is_wp_error() function.
 *
 * @package App_Package
 * @subpackage Includes
 */

namespace AppNamespace\Includes;

/**
 * Error class.
 *
 * Container for checking for errors and error messages. Return
 * Error_Messages and use is_wp_error() to check if this class is returned. Many
 * core functions pass this class in the event of an error and
 * if not handled properly will result in code errors.
 *
 * @since Previous 2.1.0
 */
class Error_Messages {
	/**
	 * Stores the list of errors.
	 *
	 * @since  WP 2.1.0
	 * @var    array
	 * @access public
	 */
	public $errors = [];

	/**
	 * Stores the list of data for error codes.
	 *
	 * @since  WP 2.1.0
	 * @var    array
	 * @access public
	 */
	public $error_data = [];

	/**
	 * Initialize the error.
	 *
	 * If `$code` is empty, the other parameters will be ignored.
	 * When `$code` is not empty, `$message` will be used even if
	 * it is empty. The `$data` parameter will be used only if it
	 * is not empty.
	 *
	 * Though the class is constructed with a single error code and
	 * message, multiple codes can be added using the `add()` method.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string|int $code Error code
	 * @param  string $message Error message
	 * @param  mixed $data Optional. Error data.
	 * @return string
	 */
	public function __construct( $code = '', $message = '', $data = '' ) {

		if ( empty( $code ) ) {
			return;
		}

		$this->errors[$code][] = $message;

		if ( ! empty( $data ) ) {
			$this->error_data[$code] = $data;
		}
	}

	/**
	 * Retrieve all error codes.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @return array List of error codes, if available.
	 */
	public function get_error_codes() {

		if ( empty( $this->errors ) ) {
			return [];
		}

		return array_keys( $this->errors );
	}

	/**
	 * Retrieve first error code available.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @return string|int Empty string, if no error codes.
	 */
	public function get_error_code() {

		$codes = $this->get_error_codes();

		if ( empty( $codes ) ) {
			return '';
		}

		return $codes[0];
	}

	/**
	 * Retrieve all error messages or error messages matching code.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string|int $code Optional. Retrieve messages matching code, if exists.
	 * @return array Error strings on success, or empty array on failure (if using code parameter).
	 */
	public function get_error_messages( $code = '' ) {

		// Return all messages if no code specified.
		if ( empty( $code ) ) {

			$all_messages = [];

			foreach ( (array) $this->errors as $code => $messages ) {
				$all_messages = array_merge( $all_messages, $messages );
			}

			return $all_messages;
		}

		if ( isset( $this->errors[$code] ) ) {
			return $this->errors[$code];
		} else {
			return [];
		}
	}

	/**
	 * Get single error message.
	 *
	 * This will get the first message available for the code. If no code is
	 * given then the first code available will be used.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string|int $code Optional. Error code to retrieve message.
	 * @return string
	 */
	public function get_error_message( $code = '' ) {

		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}

		$messages = $this->get_error_messages( $code );

		if ( empty( $messages ) ) {
			return '';
		}

		return $messages[0];
	}

	/**
	 * Retrieve error data for error code.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string|int $code Optional. Error code.
	 * @return mixed Error data, if it exists.
	 */
	public function get_error_data( $code = '' ) {

		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}

		if ( isset( $this->error_data[$code] ) ) {
			return $this->error_data[$code];
		}
	}

	/**
	 * Add an error or append additional message to an existing error.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string|int $code Error code.
	 * @param  string $message Error message.
	 * @param  mixed $data Optional. Error data.
	 * @return string
	 */
	public function add( $code, $message, $data = '' ) {

		$this->errors[$code][] = $message;

		if ( ! empty( $data ) ) {
			$this->error_data[$code] = $data;
		}
	}

	/**
	 * Add data for error code
	 *
	 * The error code can only contain one error data.
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  mixed $data Error data.
	 * @param  string|int $code Error code.
	 * @return string
	 */
	public function add_data( $data, $code = '' ) {

		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}

		$this->error_data[$code] = $data;
	}

	/**
	 * Removes the specified error
	 *
	 * This function removes all error messages associated with the specified
	 * error code, along with any error data for that code.
	 *
	 * @since  WP 4.1.0
	 * @access public
	 * @param  string|int $code Error code.
	 */
	public function remove( $code ) {

		unset( $this->errors[ $code ] );
		unset( $this->error_data[ $code ] );
	}
}