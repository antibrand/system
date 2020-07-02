<?php
/**
 * Taxonomy API: Walker_Category_Dropdown class
 *
 * @package App_Package
 * @subpackage Template
 * @since Previous 4.4.0
 */

namespace AppNamespace\Includes;

/**
 * Alias namespaces
 *
 * Make sure the namespaces here are the same base as that
 * used in your copy of this website management system.
 *
 * @since 1.0.0
 */
use \AppNamespace\Backend as Backend;

/**
 * Core class used to create an HTML dropdown list of Categories
 *
 * @see Walker
 *
 * @since Previous 2.1.0
 */
class Walker_Category_Dropdown extends Walker {

	/**
	 * What the class handles
	 *
	 * @see Walker::$tree_type
	 *
	 * @since  WP 2.1.0
	 * @var    string
	 * @access public
	 */
	public $tree_type = 'category';

	/**
	 * Database fields to use
	 *
	 * @see Walker::$db_fields
	 *
	 * @since  WP 2.1.0
	 * @todo   Decouple this
	 * @var    array
	 * @access public
	 */
	public $db_fields = [ 'parent' => 'parent', 'id' => 'term_id' ];

	/**
	 * Starts the element output
	 *
	 * @see Walker::start_el()
	 *
	 * @since Previous 2.1.0
	 * @access public
	 * @param string $output   Used to append additional content (passed by reference).
	 * @param object $category Category data object.
	 * @param int    $depth    Depth of category. Used for padding.
	 * @param array  $args     Uses 'selected', 'show_count', and 'value_field' keys, if they exist.
	 *                         See wp_dropdown_categories().
	 * @param int    $id       Optional. ID of the current category. Default 0 (unused).
	 * @return void
	 */
	public function start_el( &$output, $category, $depth = 0, $args = [], $id = 0 ) {

		$pad = str_repeat('&nbsp;', $depth * 3);

		// This filter is documented in wp-includes/category-template.php.
		$cat_name = apply_filters( 'list_cats', $category->name, $category );

		if ( isset( $args['value_field'] ) && isset( $category->{$args['value_field']} ) ) {
			$value_field = $args['value_field'];
		} else {
			$value_field = 'term_id';
		}

		$output .= "\t<option class=\"level-$depth\" value=\"" . esc_attr( $category->{$value_field} ) . "\"";

		// Type-juggling causes false matches, so we force everything to a string.
		if ( (string) $category->{$value_field} === (string) $args['selected'] ) {
			$output .= ' selected="selected"';
		}

		$output .= '>';
		$output .= $pad.$cat_name;

		if ( $args['show_count'] )
			$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
		$output .= "</option>\n";
	}
}