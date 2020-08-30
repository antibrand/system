<?php
/**
 * Post API: Walker_Page class
 *
 * @package App_Package
 * @subpackage Template
 * @since Previous 4.4.0
 */

namespace AppNamespace\Includes;

// Alias namespaces.
use \AppNamespace\Backend as Backend;

/**
 * Core walker class used to create an HTML list of pages.
 *
 * @since Previous 2.1.0
 *
 * @see Walker
 */
class Walker_Page extends Walker {

	/**
	 * What the class handles.
	 *
	 * @see Walker::$tree_type
	 *
	 * @since  WP 2.1.0
	 * @var    string
	 * @access public
	 */
	public $tree_type = 'page';

	/**
	 * Database fields to use.
	 *
	 * @see Walker::$db_fields
	 *
	 * @since  WP 2.1.0
	 * @var    array
	 * @access public
	 *
	 * @todo Decouple this.
	 */
	public $db_fields = [ 'parent' => 'post_parent', 'id' => 'ID' ];

	/**
	 * Outputs the beginning of the current level in the tree before elements are output.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string $output Used to append additional content (passed by reference).
	 * @param  int    $depth  Optional. Depth of page. Used for padding. Default 0.
	 * @param  array  $args   Optional. Arguments for outputting the next level.
	 *                       Default empty array.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {

		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "{$n}{$indent}<ul class='children'>{$n}";
	}

	/**
	 * Outputs the end of the current level in the tree after elements are output.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string $output Used to append additional content (passed by reference).
	 * @param  int    $depth  Optional. Depth of page. Used for padding. Default 0.
	 * @param  array  $args   Optional. Arguments for outputting the end of the current level.
	 *                       Default empty array.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = [] ) {

		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "{$indent}</ul>{$n}";
	}

	/**
	 * Outputs the beginning of the current element in the tree.
	 *
	 * @see Walker::start_el()
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string  $output       Used to append additional content. Passed by reference.
	 * @param  WP_Post $page         Page data object.
	 * @param  int     $depth        Optional. Depth of page. Used for padding. Default 0.
	 * @param  array   $args         Optional. Array of arguments. Default empty array.
	 * @param  int     $current_page Optional. Page ID. Default 0.
	 * @return void
	 */
	public function start_el( &$output, $page, $depth = 0, $args = [], $current_page = 0 ) {

		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		if ( $depth ) {
			$indent = str_repeat( $t, $depth );
		} else {
			$indent = '';
		}

		$css_class = [ 'page_item', 'page-item-' . $page->ID ];

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'page_item_has_children';
		}

		if ( ! empty( $current_page ) ) {

			$_current_page = get_post( $current_page );

			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current_page_ancestor';
			}

			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
			}

		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current_page_parent';
		}

		/**
		 * Filters the list of CSS classes to include with each page item in the list.
		 *
		 * @see wp_list_pages()
		 *
		 * @since Previous 2.8.0
		 * @param array   $css_class    An array of CSS classes to be applied
		 *                              to each list item.
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title ) {
			/* translators: %d: ID of a post */
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

		$atts = array();
		$atts['href'] = get_permalink( $page->ID );

		/**
		 * Filters the HTML attributes applied to a page menu item's anchor element.
		 *
		 * @since Previous 4.8.0
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $href The href attribute.
		 * }
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 */
		$atts       = apply_filters( 'page_menu_link_attributes', $atts, $page, $depth, $args, $current_page );
		$attributes = '';

		foreach ( $atts as $attr => $value ) {

			if ( ! empty( $value ) ) {
				$value       = esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$output .= $indent . sprintf(
			'<li class="%s"><a%s>%s%s%s</a>',
			$css_classes,
			$attributes,
			$args['link_before'],
			// This filter is documented in app-includes/post-template.php.
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after']
		);

		if ( ! empty( $args['show_date'] ) ) {

			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output     .= " " . mysql2date( $date_format, $time );
		}
	}

	/**
	 * Outputs the end of the current element in the tree
	 *
	 * @see Walker::end_el()
	 *
	 * @since  WP 2.1.0
	 * @access public
	 * @param  string  $output Used to append additional content. Passed by reference.
	 * @param  WP_Post $page   Page data object. Not used.
	 * @param  int     $depth  Optional. Depth of page. Default 0 (unused).
	 * @param  array   $args   Optional. Array of arguments. Default empty array.
	 * @return void
	 */
	public function end_el( &$output, $page, $depth = 0, $args = [] ) {

		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		$output .= "</li>{$n}";
	}
}