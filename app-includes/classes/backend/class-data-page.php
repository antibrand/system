<?php
/**
 * Manage data page
 *
 * @package App_Package
 * @subpackage Administration
 * @since 1.0.0
 */

namespace AppNamespace\Backend;

use \AppNamespace\Includes as Includes;

/**
 * Manage data page class
 *
 * Displays tabbed content for managing website and network data.
 *
 * @since  1.0.0
 * @access public
 */
class Data_Page {

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {

		// Varialbe for the instance to be used outside the class.
		static $instance = null;

		if ( is_null( $instance ) ) {

			// Set variable for new instance.
			$instance = new self;

			// Instantiate the tabbed content.
			$instance->tabs();

			// Help tabs.
			$instance->help();
		}

		// Return the instance.
		return $instance;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Add tabs hashtags.
		add_filter( 'app_tabs_hashtags', [ $this, 'app_hashtags' ] );
	}

	/**
	 * Tabs hashtags
	 *
	 * Use the tab hashtags to allow for
	 * direct linking to an open tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool Returns true to allow hashtag linking.
	 */
	public function app_hashtags() {
		return true;
	}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the dashboard page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tabbed content.
	 */
	public function tabs() {

		// Stop if not on the relevant screen.
		$screen = get_current_screen();
		if ( 'data' != $screen->id ) {
			return;
		}

		if ( is_network() ) {
			$start_heading = __( 'Network Data' );
		} else {
			$start_heading = __( 'Website Data' );
		}

		$start_heading = apply_filters( 'tabs_data_start_heading', $start_heading );

		// Start. Editor capability for post imporation.
		$screen->add_content_tab( [
			'id'         => 'data-start',
			'capability' => 'edit_posts',
			'tab'        => __( 'Data' ),
			'icon'       => '',
			'heading'    => $start_heading,
			'content'    => '',
			'callback'   => [ $this, 'start_tab' ]
		] );

		// Import data. Import capability.
		$screen->add_content_tab( [
			'id'         => 'data-import',
			'url'        => admin_url( 'import.php' ),
			'capability' => 'import',
			'tab'        => __( 'Import' ),
			'icon'       => '',
			'heading'    => __( 'Import Data' ),
			'content'    => '',
			'callback'   => [ $this, 'import_tab' ]
		] );

		// Import data. Import capability.
		$screen->add_content_tab( [
			'id'         => 'data-export',
			// 'url'        => admin_url( 'export.php' ),
			'capability' => 'export',
			'tab'        => __( 'Export' ),
			'icon'       => '',
			'heading'    => __( 'Export Data' ),
			'content'    => '',
			'callback'   => [ $this, 'export_tab' ]
		] );

		// User accounts data. Erase others personal data capability.
		$screen->add_content_tab( [
			'id'         => 'data-accounts',
			'capability' => 'erase_others_personal_data',
			'tab'        => __( 'Accounts' ),
			'icon'       => '',
			'heading'    => __( 'Accounts Data' ),
			'content'    => '',
			'callback'   => [ $this, 'accounts_tab' ]
		] );
	}

	/**
	 * Start tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function start_tab() {

	?>
	<div class="tab-section-wrap tab-section-wrap__data-start">

		<p><?php _e( 'This section is in development.' ); ?></p>
	</div>
	<?php

	}

	/**
	 * Import tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function import_tab() {

	?>
	<div class="tab-section-wrap tab-section-wrap__data-import">

		<p><?php _e( 'This section is in development.' ); ?></p>

	</div>
	<?php

	}

	/**
	 * Export tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function export_tab() {

		global $wpdb;

	?>
	<div class="tab-section-wrap tab-section-wrap__data-export">

		<form method="get" id="export-filters">

			<input type="hidden" name="download" value="true" />

			<p><label><input type="radio" name="content" value="all" checked="checked" aria-describedby="all-content-desc" /> <?php _e( 'All Content' ); ?></label></p>

			<p class="description" id="all-content-desc"><?php _e( 'This will contain all of your posts, pages, comments, custom fields, terms, navigation menus, and custom posts.' ); ?></p>

			<p><label><input type="radio" name="content" value="posts" /> <?php _e( 'Posts' ); ?></label></p>

			<ul id="post-filters" class="export-filters">

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Categories to Export' ); ?></legend>

						<label for="cat" class="label-responsive">
							<span><?php _e( 'Categories:' ); ?></span>
							<?php wp_dropdown_categories( [ 'show_option_all' => __( 'All' ) ] ); ?>
						</label>

					</fieldset>
				</li>

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Post Authors to Export' ); ?></legend>

						<label for="post_author" class="label-responsive">
							<span><?php _e( 'Authors:' ); ?></span>
							<?php
							$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'post'" );
							wp_dropdown_users( [
								'include'         => $authors,
								'name'            => 'post_author',
								'multi'           => true,
								'show_option_all' => __( 'All' ),
								'show'            => 'display_name_with_login',
							] ); ?>
						</label>

					</fieldset>
				</li>

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Date Range to Export' ); ?></legend>

						<label for="post-start-date" class="label-responsive">
							<span><?php _e( 'Start Date:' ); ?></span>
						</label>
						<select name="post_start_date" id="post-start-date">
							<option value="0"><?php _e( 'Select' ); ?></option>
							<?php Includes\Export_Data :: export_date_options(); ?>
						</select>

						<label for="post-end-date" class="label-responsive">
							<span><?php _e( 'End Date:' ); ?></span>
						</label>
						<select name="post_end_date" id="post-end-date">
							<option value="0"><?php _e( 'Select' ); ?></option>
							<?php Includes\Export_Data :: export_date_options(); ?>
						</select>

					</fieldset>
				</li>
				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Post Statuses to Export' ); ?></legend>

						<label for="post-status" class="label-responsive">
							<span><?php _e( 'Status:' ); ?></span>
						</label>
						<select name="post_status" id="post-status">

							<option value="0"><?php _e( 'All' ); ?></option>

							<?php $post_stati = get_post_stati( [ 'internal' => false ], 'objects' );

							foreach ( $post_stati as $status ) : ?>
							<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
							<?php endforeach; ?>

						</select>

					</fieldset>
				</li>
			</ul>

			<p><label><input type="radio" name="content" value="pages" /> <?php _e( 'Pages' ); ?></label></p>

			<ul id="page-filters" class="export-filters">

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Page Authors to Export' ); ?></legend>

						<label class="label-responsive">
							<span><?php _e( 'Authors:' ); ?></span>
						<?php
						$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'page'" );
						wp_dropdown_users( [
							'include'         => $authors,
							'name'            => 'page_author',
							'multi'           => true,
							'show_option_all' => __( 'All' ),
							'show'            => 'display_name_with_login',
						] ); ?>
						</label>

					</fieldset>
				</li>

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Date Range to Export' ); ?></legend>

						<label for="page-start-date" class="label-responsive">
							<span><?php _e( 'Start Date:' ); ?></span>
						</label>
						<select name="page_start_date" id="page-start-date">
							<option value="0"><?php _e( 'Select' ); ?></option>
							<?php Includes\Export_Data :: export_date_options( 'page' ); ?>
						</select>

						<label for="page-end-date" class="label-responsive">
							<span><?php _e( 'End Date:' ); ?></span>
						</label>
						<select name="page_end_date" id="page-end-date">
							<option value="0"><?php _e( 'Select' ); ?></option>
							<?php Includes\Export_Data :: export_date_options( 'page' ); ?>
						</select>

					</fieldset>
				</li>

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Page Authors to Export' ); ?></legend>

						<label for="page-status" class="label-responsive">
							<span><?php _e( 'Status:' ); ?></span>
						</label>
						<select name="page_status" id="page-status">

							<option value="0"><?php _e( 'All' ); ?></option>

							<?php foreach ( $post_stati as $status ) : ?>
							<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
							<?php endforeach; ?>

						</select>

					</fieldset>
				</li>
			</ul>

			<?php foreach ( get_post_types( [ '_builtin' => false, 'can_export' => true ], 'objects' ) as $post_type ) : ?>
			<p><label><input type="radio" name="content" value="<?php echo esc_attr( $post_type->name ); ?>" /> <?php echo esc_html( $post_type->label ); ?></label></p>
			<?php endforeach; ?>

			<p><label><input type="radio" name="content" value="attachment" /> <?php _e( 'Media' ); ?></label></p>

			<ul id="attachment-filters" class="export-filters">

				<li>
					<fieldset>

						<legend class="screen-reader-text"><?php _e( 'Date range:' ); ?></legend>

						<label for="attachment-start-date" class="label-responsive">
							<span><?php _e( 'Start date:' ); ?></span>
						</label>
						<select name="attachment_start_date" id="attachment-start-date">
							<option value="0"><?php _e( 'Select' ); ?></option>
							<?php Includes\Export_Data :: export_date_options( 'attachment' ); ?>
						</select>

						<label for="attachment-end-date" class="label-responsive">
							<span><?php _e( 'End date:' ); ?></span>
						</label>
						<select name="attachment_end_date" id="attachment-end-date">
							<option value="0"><?php _e( 'Select' ); ?></option>
							<?php Includes\Export_Data :: export_date_options( 'attachment' ); ?>
						</select>

					</fieldset>
				</li>
			</ul>
			<?php
			/**
			 * Fires at the end of the export filters form.
			 *
			 * @since Previous 3.5.0
			 */
			do_action( 'export_filters' );
			?>

			<?php submit_button( __( 'Download Export File' ) ); ?>

		</form>

	</div>
	<?php

	}

	/**
	 * Accounts tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function accounts_tab() {

	?>
	<div class="tab-section-wrap tab-section-wrap__data-accounts">

		<p><?php _e( 'This section is in development.' ); ?></p>
	</div>
	<?php

	}

	/**
	 * Help content
	 *
	 * Add content to the help section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help() {

		// Stop if not on the relevant screen.
		$screen = get_current_screen();
		if ( 'data' != $screen->id ) {
			return;
		}

		$screen->add_help_tab( [
			'id'      => 'export',
			'title'   => __( 'Export' ),
			'content'  => null,
			'callback' => [ $this, 'help_export' ],
		] );

		$screen->add_help_tab( [
			'id'      => 'import',
			'title'   => __( 'Import' ),
			'content'  => null,
			'callback' => [ $this, 'help_import' ],
		] );

		// Add a help sidebar.
		$screen->set_help_sidebar(
			$this->help_sidebar()
		);

	}

	/**
	 * Export help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_export() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Export' )
		);

		$help .= '<p>' . __( 'You can export a file of your site&#8217;s content in order to import it into another installation or platform. The export file will be in the XML file format. Posts, pages, comments, custom fields, categories, and tags can be included. You can choose for the file to include only certain posts or pages by setting the dropdown filters to limit the export by category, author, date range by month, or publishing status.' ) . '</p>';

		$help .= '<p>' . __( 'Once generated, the XML file can be imported by a compatible site or by another platform able to access this format.' ) . '</p>';

		echo $help;

	}

	/**
	 * Import help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_import() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Import' )
		);

		$help .= '<p>' . __( 'This screen lists links to plugins to import data from blogging/content management platforms. Choose the platform you want to import from, and click Install Now when you are prompted in the popup window. If your platform is not listed, click the link to search the plugin directory for other importer plugins to see if there is one for your platform.' ) . '</p>';

		$help .= '<p>' . __( 'In previous versions, all importers were built-in. They have been turned into plugins since most people only use them once or infrequently.' ) . '</p>';

		echo $help;

	}

	/**
	 * Help sidebar
	 *
	 * This system adds no content to the help sidebar
	 * but there is a filter applied for adding content.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void Applies a filter for the markup of the help sidebar content.
	 */
	public function help_sidebar() {

		$set_help_sidebar = apply_filters( 'set_help_sidebar_data_page', '' );
		return $set_help_sidebar;
	}
}