<?php
/**
 * Import data class
 *
 * General importer class.
 *
 * @package App_Package
 * @subpackage Includes
 * @since 1.0.0
 */

namespace AppNamespace\Includes;

/**
 * Import data class
 *
 * @since  1.0.0
 * @access public
 */
class Import_Data {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Returns array with imported permalinks from the database
	 *
	 * @since  1.0.0
	 * @global wpdb $wpdb Database abstraction object.
	 * @param  string $importer_name
	 * @param  string $bid
	 * @return array
	 */
	public function get_imported_posts( $importer_name, $bid ) {

		global $wpdb;

		$hashtable =[];
		$limit     = 100;
		$offset    = 0;

		// Grab all posts in chunks.
		do {
			$meta_key = $importer_name . '_' . $bid . '_permalink';
			$sql      = $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s LIMIT %d,%d", $meta_key, $offset, $limit );
			$results  = $wpdb->get_results( $sql );

			// Increment offset.
			$offset = ( $limit + $offset );

			if ( ! empty( $results ) ) {

				foreach ( $results as $r ) {

					// Set permalinks into array.
					$hashtable[$r->meta_value] = intval( $r->post_id );
				}
			}

		} while ( count( $results ) == $limit );

		// Unset to save memory.
		unset( $results, $r );

		return $hashtable;
	}

	/**
	 * Return count of imported permalinks from the database
	 *
	 * @since  1.0.0
	 * @global wpdb $wpdb Database abstraction object.
	 * @param  string $importer_name
	 * @param  string $bid
	 * @return int
	 */
	public function count_imported_posts( $importer_name, $bid ) {

		global $wpdb;

		$count = 0;

		// Get count of permalinks.
		$meta_key = $importer_name . '_' . $bid . '_permalink';
		$sql      = $wpdb->prepare( "SELECT COUNT( post_id ) AS cnt FROM $wpdb->postmeta WHERE meta_key = '%s'", $meta_key );
		$result   = $wpdb->get_results( $sql );

		if ( ! empty( $result ) ) {
			$count = intval( $result[0]->cnt );
		}

		// Unset to save memory.
		unset( $results );

		return $count;
	}

	/**
	 * Set array with imported comments from the database
	 *
	 * @since  1.0.0
	 * @global wpdb $wpdb Database abstraction object.
	 * @param string $bid
	 * @return array
	 */
	public function get_imported_comments( $bid ) {

		global $wpdb;

		$hashtable = [];
		$limit     = 100;
		$offset    = 0;

		// Grab all comments in chunks.
		do {
			$sql     = $wpdb->prepare( "SELECT comment_ID, comment_agent FROM $wpdb->comments LIMIT %d,%d", $offset, $limit );
			$results = $wpdb->get_results( $sql );

			// Increment offset.
			$offset = ( $limit + $offset );

			if ( ! empty( $results ) ) {

				foreach ( $results as $r ) {

					// Explode comment_agent key.
					list ( $ca_bid, $source_comment_id ) = explode( '-', $r->comment_agent );
					$source_comment_id = intval( $source_comment_id );

					// Check if this comment came from this blog.
					if ( $bid == $ca_bid ) {
						$hashtable[$source_comment_id] = intval( $r->comment_ID );
					}
				}
			}

		} while ( count( $results ) == $limit );

		// Unset to save memory.
		unset( $results, $r );

		return $hashtable;
	}

	/**
	 * Set blog
	 *
	 * @since  1.0.0
	 * @param  int $blog_id
	 * @return int|void
	 */
	public function set_blog( $blog_id ) {

		if ( is_numeric( $blog_id ) ) {

			$blog_id = (int) $blog_id;

		} else {

			$blog = 'http://' . preg_replace( '#^https?://#', '', $blog_id );

			if ( ( !$parsed = parse_url( $blog ) ) || empty( $parsed['host'] ) ) {

				fwrite( STDERR, "Error: can not determine blog_id from $blog_id\n" );

				exit();
			}

			if ( empty( $parsed['path'] ) ) {
				$parsed['path'] = '/';
			}

			$blogs = get_sites(
				[
					'domain' => $parsed['host'],
					'number' => 1,
					'path'   => $parsed['path']
				]
			);

			if ( ! $blogs ) {
				fwrite( STDERR, "Error: Could not find blog\n" );

				exit();
			}

			$blog    = array_shift( $blogs );
			$blog_id = (int) $blog->blog_id;
		}

		if ( function_exists( 'is_network' ) ) {

			if ( is_network() )
				switch_to_blog( $blog_id );
		}

		return $blog_id;
	}

	/**
	 * Set user
	 *
	 * @since  1.0.0
	 * @param  int $user_id
	 * @return int|void
	 */
	public function set_user( $user_id ) {

		if ( is_numeric( $user_id ) ) {
			$user_id = (int) $user_id;
		} else {
			$user_id = (int) username_exists( $user_id );
		}

		if ( ! $user_id || !wp_set_current_user( $user_id ) ) {

			fwrite( STDERR, "Error: can not find user\n" );

			exit();
		}

		return $user_id;
	}

	/**
	 * Sort by strlen, longest string first
	 *
	 * @since  1.0.0
	 * @param  string $a
	 * @param  string $b
	 * @return int
	 */
	public function cmpr_strlen( $a, $b ) {
		return strlen( $b ) - strlen( $a );
	}

	/**
	 * Get URL
	 *
	 * @since  1.0.0
	 * @param string $url
	 * @param string $username
	 * @param string $password
	 * @param bool   $head
	 * @return array
	 */
	public function get_page( $url, $username = '', $password = '', $head = false ) {

		// Increase the timeout.
		add_filter( 'http_request_timeout', [ $this, 'bump_request_timeout' ] );

		$headers = [];
		$args    = [];

		if ( true === $head ) {
			$args['method'] = 'HEAD';
		}

		if ( ! empty( $username ) && ! empty( $password ) ) {
			$headers['Authorization'] = 'Basic ' . base64_encode( "$username:$password" );
		}

		$args['headers'] = $headers;

		return wp_safe_remote_request( $url, $args );
	}

	/**
	 * Bump up the request timeout for http requests
	 *
	 * @since  1.0.0
	 * @param int $val
	 * @return int
	 */
	public function bump_request_timeout( $val ) {
		return 60;
	}

	/**
	 * Check if user has exceeded disk quota
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function is_user_over_quota() {

		if ( function_exists( 'upload_is_user_over_quota' ) ) {

			if ( upload_is_user_over_quota() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Replace newlines, tabs, and multiple spaces with a single space
	 *
	 * @since  1.0.0
	 * @param string $string
	 * @return string
	 */
	public function min_whitespace( $string ) {
		return preg_replace( '|[\r\n\t ]+|', ' ', $string );
	}

	/**
	 * Resets global variables that grow out of control during imports.
	 *
	 * @since Previous 3.0.0
	 * @global wpdb  $wpdb Database abstraction object.
	 * @global array $wp_actions
	 */
	public function stop_the_insanity() {

		global $wpdb, $wp_actions;

		// Or define( 'WP_IMPORTING', true );
		$wpdb->queries = [];

		// Reset $wp_actions to keep it from growing out of control
		$wp_actions = [];
	}
}