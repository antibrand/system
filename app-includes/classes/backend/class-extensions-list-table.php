<?php
/**
 * List Table API: Extensions_List_Table class
 *
 * @package App_Package
 * @subpackage Administration
 * @since 1.0.0
 */

namespace AppNamespace\Backend;

/**
 * Core class used to extensions in a list table.
 *
 * @since  1.0.0
 * @access private
 *
 * @see List_Table
 */
class Extensions_List_Table extends List_Table {

	/**
	 * Constructor method
	 *
	 * @see List_Table::__construct() for more information on default arguments.
	 *
	 * @since  1.0.0
	 * @global string $status
	 * @param  array $args An associative array of arguments.
	 * @return self
	 */
	public function __construct( $args = [] ) {

		if ( isset( $args['screen'] ) ) {
			$screen = $args['screen'];
		} else {
			$screen = null;
		}

		parent::__construct( [
			'screen' => $screen
		] );
	}

	/**
	 * Table classes
	 *
	 * CSS classes for the list table.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns an array of classes.
	 */
	protected function get_table_classes() {

		// Array of table classes.
		$classes = [
			'widefat',
			'extensions'
		];

		return $classes;
	}

	/**
	 * User permission
	 *
	 * @since 1.0.0
	 * @access public
	 * @return bool Returns true if the user meets the capability requirement.
	 */
	public function ajax_user_can() {
		return current_user_can( 'manage_options' );
	}

	/**
	 *
	 * @global string $status
	 * @global array $plugins
	 * @global array $totals
	 * @global int $page
	 * @global string $orderby
	 * @global string $order
	 * @global string $s
	 */
	public function prepare_items() {

		// Access global variables.
		global $status, $plugins, $totals, $page, $orderby, $order, $s;

		wp_reset_vars( [ 'orderby', 'order' ] );

		/**
		 * Filters the full array of plugins to list in the Plugins list table.
		 *
		 * @see get_plugins()
		 *
		 * @since 1.0.0
		 * @param array $all_plugins An array of plugins to display in the list table.
		 */
		$all_plugins = apply_filters( 'all_plugins', get_plugins() );

		$plugins = [
			'all'       => $all_plugins,
			'extension' => []
		];

		$screen = $this->screen;

		if ( ! is_network() || ( $screen->in_admin( 'network' ) && current_user_can( 'manage_network_plugins' ) ) ) {

			/**
			 * Filters whether to display the advanced plugins list table.
			 *
			 * There are two types of advanced plugins - must-use and drop-ins -
			 * which can be used in a single site or Multisite network.
			 *
			 * The $type parameter allows you to differentiate between the type of advanced
			 * plugins to filter the display of. Contexts include 'extension' and 'dropins'.
			 *
			 * @since 1.0.0
			 * @param bool   $show Whether to show the advanced plugins for the specified
			 *                     plugin type. Default true.
			 * @param string $type The plugin type. Accepts 'extension', 'dropins'.
			 */
			if ( apply_filters( 'show_advanced_plugins', true, 'extension' ) ) {
				$plugins['extension'] = get_mu_plugins();
			}
		}

		if ( ! $screen->in_admin( 'network' ) ) {

			$show = current_user_can( 'manage_network_plugins' );
			/**
			 * Filters whether to display network-active plugins alongside plugins active for the current site.
			 *
			 * This also controls the display of inactive network-only plugins (plugins with
			 * "Network: true" in the plugin header).
			 *
			 * Plugins cannot be network-activated or network-deactivated from this screen.
			 *
			 * @since 1.0.0
			 * @param bool $show Whether to show network-active plugins. Default is whether the current
			 *                   user can manage network plugins (ie. a Network Admin).
			 */
			$show_network_active = apply_filters( 'show_network_active_plugins', $show );
		}

		$totals = [];

		foreach ( $plugins as $type => $list ) {
			$totals[ $type ] = count( $list );
		}

		if ( empty( $plugins[ $status ] ) && ! in_array( $status, [ 'extension' ] ) ) {
			$status = 'extension';
		}

		$this->items = [];

		foreach ( $plugins[ $status ] as $plugin_file => $plugin_data ) {

			// Translate, Don't Apply Markup, Sanitize HTML.
			$this->items[ $plugin_file ] = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, false, true );
		}

		$total_this_page = $totals[ $status ];

		if ( ! $orderby ) {
			$orderby = 'Name';
		} else {
			$orderby = ucfirst( $orderby );
		}

		$order = strtoupper( $order );

		uasort( $this->items, [ $this, '_order_callback' ] );

		$this->set_pagination_args( [
			'total_items' => $total_this_page,
			'per_page'    => '9999',
		] );
	}

	/**
	 * @global array $plugins
	 */
	public function no_items() {

		global $plugins;

		_e( 'No extenionss found.' );
	}

	/**
	 *
	 * @global string $status
	 * @return array
	 */
	public function get_columns() {

		return [
			'name'        => __( 'Extension' ),
			'description' => __( 'Description' ),
			'tags'        => __( 'Tagged' ),
			'uri'         => __( 'Link' )
		];
	}

	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		return [];
	}

	/**
	 *
	 * @global string $status
	 */
	public function display_rows() {

		global $status;

		if ( is_network() && ! $this->screen->in_admin( 'network' ) && in_array( $status, [ 'extension' ] ) ) {
			return;
		}

		foreach ( $this->items as $plugin_file => $plugin_data ) {
			$this->single_row( [ $plugin_file, $plugin_data ] );
		}
	}

	/**
	 * @global string $status
	 * @global int $page
	 * @global string $s
	 * @global array $totals
	 *
	 * @param array $item
	 */
	public function single_row( $item ) {

		global $status, $page, $s, $totals;

		list( $plugin_file, $plugin_data ) = $item;

		$context = $status;
		$screen  = $this->screen;

		// Do not restrict by default.
		$restrict_network_active = false;
		$restrict_network_only   = false;

		$class       = 'extensions-item';
		$description = '<p>' . ( $plugin_data['Description'] ? $plugin_data['Description'] : '&nbsp;' ) . '</p>';
		$plugin_name = $plugin_data['Name'];
		$plugin_uri  = $plugin_data['PluginURI'];
		$plugin_tags = $plugin_data['Tags'];

		if ( isset( $plugin_data['slug'] ) ) {
			$plugin_slug = $plugin_data['slug'];
		} else {
			$plugin_slug = sanitize_title( $plugin_name );
		}

		printf( '<tr class="%s" data-slug="%s" data-extension="%s">',
			esc_attr( $class ),
			esc_attr( $plugin_slug ),
			esc_attr( $plugin_file )
		);

		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {

			$extra_classes = '';

			if ( in_array( $column_name, $hidden ) ) {
				$extra_classes = ' hidden';
			}

			switch ( $column_name ) {

				case 'name' :

					echo "<td class='extension-title column-primary'><strong>$plugin_name</strong>";
					echo "</td>";

					break;

				case 'description' :

					$classes = 'column-description desc';

					echo "<td class='$classes{$extra_classes}'>
						<div class='extension-description'>$description</div>
						<div class='$class second plugin-version-author-uri'>";

					$plugin_meta = [];

					if ( ! empty( $plugin_data['Version'] ) ) {
						$plugin_meta[] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
					}

					if ( ! empty( $plugin_data['Author'] ) ) {

						$author = $plugin_data['Author'];

						if ( ! empty( $plugin_data['AuthorURI'] ) ) {
							$author = '<a href="' . $plugin_data['AuthorURI'] . '" target="_blank" rel="noopener noreferrer">' . $plugin_data['Author'] . '</a>';
						}

						$plugin_meta[] = sprintf( __( 'By %s' ), $author );
					}

					/**
					 * Filters the array of row meta for each plugin in the Plugins list table.
					 *
					 * @since 1.0.0
					 * @param array  $plugin_meta An array of the plugin's metadata,
					 *                            including the version, author,
					 *                            author URI, and plugin URI.
					 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
					 * @param array  $plugin_data An array of plugin data.
					 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
					 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Integrated',
					 *                            'Drop-ins', 'Search'.
					 */
					$plugin_meta = apply_filters( 'plugin_row_meta', $plugin_meta, $plugin_file, $plugin_data, $status );

					echo implode( ' | ', $plugin_meta );

					echo "</div></td>";

					break;

				case 'tags' :

					echo "<td class='extension-tags column-primary'>$plugin_tags";
					echo "</td>";

					break;

				case 'uri' :

					echo "<td class='extension-link column-primary'><a href='$plugin_uri'>$plugin_uri</a>";
					echo "</td>";

					break;

				default :

					$classes = "$column_name column-$column_name $class";

					echo "<td class='$classes{$extra_classes}'>";

					/**
					 * Fires inside each custom column of the Plugins list table.
					 *
					 * @since 3.1.0
					 *
					 * @param string $column_name Name of the column.
					 * @param string $plugin_file Path to the plugin file.
					 * @param array  $plugin_data An array of plugin data.
					 */
					do_action( 'manage_plugins_custom_column', $column_name, $plugin_file, $plugin_data );

					echo "</td>";
			}
		}

		echo "</tr>";

		/**
		 * Fires after each row in the Plugins list table.
		 *
		 * @since 1.0.0
		 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Integrated',
		 *                            'Drop-ins', 'Search'.
		 */
		do_action( 'after_plugin_row', $plugin_file, $plugin_data, $status );

		/**
		 * Fires after each specific row in the Plugins list table.
		 *
		 * The dynamic portion of the hook name, `$plugin_file`, refers to the path
		 * to the plugin file, relative to the plugins directory.
		 *
		 * @since 1.0.0
		 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Integrated',
		 *                            'Drop-ins', 'Search'.
		 */
		do_action( "after_plugin_row_{$plugin_file}", $plugin_file, $plugin_data, $status );
	}

	/**
	 * Gets the name of the primary column for this specific list table.
	 *
	 * @since  1.0.0 name for the primary column, in this case, 'name'.
	 */
	protected function get_primary_column_name() {
		return 'name';
	}
}
