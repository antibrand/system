<?php
/**
 * App dashboard class
 *
 * @package App_Package
 * @subpackage Administration
 */

namespace AppNamespace\Backend;

class Dashboard {

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

			// Instantiate the widgets API.
			$instance->dashboard_widgets();

			// Instantiate the tabbed content.
			$instance->tabs();

			// Instantiate the help content.
			$instance->help();
		}

		// Return the instance.
		return $instance;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access private
	 * @return self
	 */
	public function __construct() {

		// Add content to the tabbed section of the dashboard page.
		// add_action( 'dashboard_top_panel', [ $this, 'tabs' ] );

		// Add dashboard quota to the activity box.
		add_action( 'activity_box_end', [ $this, 'dashboard_quota' ] );
	}

	/**
	 * Dashboard intro panel
	 *
	 * Displays introductory information and quick access links.
	 *
	 * Content varies by user role and can be superceded by themes & plugins.
	 *
	 * @since  WP 3.3.0 WordPress released the "Welcome" panel.
	 * @since  1.0.0 Completely reworked for this management system.
	 * @access public
	 * @return void
	 */
	public function dashboard_tabs( $args = [] ) {

		/**
		 * Get intro panel content by user role.
		 */

		// Developer.
		if ( current_user_can( 'develop' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-developer.php' );

		// Network administrator.
		} elseif ( current_user_can( 'manage_network' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-network.php' );

		// Administrator.
		} elseif ( current_user_can( 'manage_options' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-administrator.php' );

		// Editor.
		} elseif ( current_user_can( 'edit_others_posts' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-editor.php' );

		// Author.
		} elseif ( current_user_can( 'publish_posts' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-author.php' );

		// Contributor.
		} elseif ( current_user_can( 'edit_posts' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-contributor.php' );

		// Subscriber.
		} elseif ( current_user_can( 'read' ) ) {
			$tabs['intro'] = include( ABSPATH . 'app-views/backend/content/dashboard/intro-panel-subscriber.php' );

		// Fallback.
		} else {
			$tabs['intro'] = '';
		}

		return apply_filters( 'dashboard_tabs', $tabs );
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
		if ( 'dashboard' != $screen->id ) {
			return;
		}

		$start_heading = apply_filters( 'tabs_dashboard_start_heading', sprintf(
			'%1s %2s %3s',
			__( 'Your' ),
			get_bloginfo( 'name' ),
			__( 'Dashboard' )
		) );

		$screen->add_content_tab( [
			'id'         => 'dashboard-start',
			'capability' => 'read',
			'tab'        => __( 'Start' ),
			'icon'       => '',
			'heading'    => $start_heading,
			'content'    => '',
			'callback'   => [ $this, 'start_tab' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'dashboard-overview',
			'capability' => 'manage_options',
			'tab'        => __( 'Overview' ),
			'icon'       => '',
			'heading'    => __( 'Site Overview' ),
			'content'    => '',
			'callback'   => [ $this, 'site_overview_tab' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'dashboard-widgets',
			'capability' => 'read',
			'tab'        => __( 'Widgets' ),
			'icon'       => '',
			'heading'    => __( 'Dashboard Widgets' ),
			'content'    => '',
			'callback'   =>  [ $this, 'dashboard' ]
		] );

		$screen->add_content_tab( [
			'id'         => 'dashboard-draftposts',
			'capability' => 'edit_posts',
			'tab'        => __( 'Drafts' ),
			'icon'       => '',
			'heading'    => __( 'Draft Posts' ),
			'content'    => '',
			'callback'   => [ $this, 'dashboard_draft_posts' ]
		] );
	}

	/**
	 * Start tab
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function start_tab() {

		// Get the current user data for the greeting.
		$current_user = wp_get_current_user();
		$user_id      = get_current_user_id();
		$user_name    = $current_user->display_name;

		$user_greeting = sprintf(
			'%1s %2s',
			esc_html__( 'Hello,' ),
			$user_name
		);

	?>
	<div class="tab-section-wrap tab-section-wrap__dashboard">

		<section class="tab-section tab-section-dashboard tab-section__user-intro">

			<div class="tab-icon-content tab-icon-content-dashboard tab-icon-content__system-overview">

				<figure>
					<a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
						<img class="avatar" src="<?php echo esc_url( get_avatar_url( $user_id ) ); ?>" alt="<?php echo $user_name; ?>" width="80" height="80" />
					</a>
					<figcaption class="screen-reader-text"><?php echo $user_name; ?></figcaption>
				</figure>

				<div>
					<?php echo sprintf(
						'<h3>%1s</h3>',
						$user_greeting
					); ?>
					<p><?php _e( 'This site may display your profile details in posts that you author, depending on the theme and plugins used. You can edit your details, set your images, and change your color schemes.' ); ?></p>

					<p class="dashboard-panel-call-to-action">
						<a class="button button-primary button-hightlight" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Manage Your Account' ); ?></a>

						<?php if ( current_user_can( 'list_users' ) ) : ?>
						<a href="<?php echo esc_url( admin_url( 'users.php' ) ); ?>"><?php _e( 'View all accounts' ); ?></a>
						<?php endif; ?>
					</p>
				</div>

			</div>

		</section>

		<section class="tab-section tab-section-dashboard tab-section__system-info">

			<div>

				<h3><?php _e( 'Website Management' ); ?></h3>

				<p><?php _e( 'The content of this website, including blog & news posts, informational pages, media presentations, user discussions, and more, is made easy by the website management system.' ); ?></p>

				<p class="dashboard-panel-call-to-action">
					<a class="button button-primary button-hightlight" href="<?php echo esc_url( admin_url( 'about.php' ) ); ?>"><?php _e( 'Learn More' ); ?></a>
				</p>
			</div>

		</section>
	</div>
	<?php

	}

	/**
	 * Register dashboard widgets
	 *
	 * Handles POST data, sets up filters.
	 *
	 * @since  WP 2.5.0 Function added in WP.
	 * @since  1.0.0 Method added to the dashboard class in this app.
	 * @access public
	 * @global array $wp_registered_widgets
	 * @global array $wp_registered_widget_controls
	 * @global array $wp_dashboard_control_callbacks
	 */
	public function dashboard_widgets() {

		// Accress global variables.
		global $wp_registered_widgets, $wp_registered_widget_controls, $wp_dashboard_control_callbacks;

		$wp_dashboard_control_callbacks = [];
		$screen = get_current_screen();

		// Register wdgets and controls.
		$this->add_dashboard_widget( 'sample_widget_one', __( 'Sample Widget One' ), [ $this, 'app_sample_dashboard_widget_one' ] );
		$this->add_dashboard_widget( 'sample_widget_two', __( 'Sample Widget Two' ), [ $this, 'app_sample_dashboard_widget_two' ] );

		if ( is_network_admin() ) {
			$this->add_dashboard_widget( 'network_dashboard_right_now', __( 'Right Now' ), [ $this, 'network_overview' ] );
		}

		// Activity widget.
		if ( is_blog_admin() ) {	}

		if ( is_network_admin() ) {

			/**
			 * Fires after core widgets for the Network Admin dashboard have been registered.
			 *
			 * @since WP 3.1.0
			 */
			do_action( 'wp_network_dashboard_setup' );

			/**
			 * Filters the list of widgets to load for the Network Admin dashboard.
			 *
			 * @since WP 3.1.0
			 *
			 * @param array $dashboard_widgets An array of dashboard widgets.
			 */
			$dashboard_widgets = apply_filters( 'wp_network_dashboard_widgets', [] );

		} elseif ( is_user_admin() ) {

			/**
			 * Fires after core widgets for the User Admin dashboard have been registered.
			 *
			 * @since WP 3.1.0
			 */
			do_action( 'wp_user_dashboard_setup' );

			/**
			 * Filters the list of widgets to load for the User Admin dashboard.
			 *
			 * @since WP 3.1.0
			 *
			 * @param array $dashboard_widgets An array of dashboard widgets.
			 */
			$dashboard_widgets = apply_filters( 'wp_user_dashboard_widgets', [] );

		} else {

			/**
			 * Fires after core widgets for the admin dashboard have been registered.
			 *
			 * @since WP 2.5.0
			 */
			do_action( 'app_dashboard_setup' );

			/**
			 * Filters the list of widgets to load for the admin dashboard.
			 *
			 * @since WP 2.5.0
			 *
			 * @param array $dashboard_widgets An array of dashboard widgets.
			 */
			$dashboard_widgets = apply_filters( 'wp_dashboard_widgets', [] );
		}

		foreach ( $dashboard_widgets as $widget_id ) {

			if ( empty( $wp_registered_widgets[$widget_id]['all_link'] ) ) {
				$name = $wp_registered_widgets[$widget_id]['name'];
			} else {
				$name = $wp_registered_widgets[$widget_id]['name'] . " <a href='{$wp_registered_widgets[$widget_id]['all_link']}' class='edit-box open-box'>" . __( 'View all' ) . '</a>';
			}

			wp_add_dashboard_widget(
				$widget_id,
				$name,
				$wp_registered_widgets[$widget_id]['callback'],
				$wp_registered_widget_controls[$widget_id]['callback']
			);
		}

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['widget_id'] ) ) {

			check_admin_referer( 'edit-dashboard-widget_' . $_POST['widget_id'], 'dashboard-widget-nonce' );

			ob_start();
			wp_dashboard_trigger_widget_control( $_POST['widget_id'] );
			ob_end_clean();

			wp_redirect( remove_query_arg( 'edit' ) );

			exit;
		}

		// This action is documented in wp-admin/edit-form-advanced.php.
		do_action( 'do_meta_boxes', $screen->id, 'normal', '' );

		// This action is documented in wp-admin/edit-form-advanced.php.
		do_action( 'do_meta_boxes', $screen->id, 'side', '' );
	}

	/**
	 * Adds a new dashboard widget.
	 *
	 * @since  WP 2.7.0 Function added in WP.
	 * @since  1.0.0 Method added to the dashboard class in this app.
	 * @access public
	 * @global array $wp_dashboard_control_callbacks
	 * @param  string   $widget_id        Widget ID  (used in the 'id' attribute for the widget).
	 * @param  string   $widget_name      Title of the widget.
	 * @param  callable $callback         Function that fills the widget with the desired content.
	 *                                   The function should echo its output.
	 * @param  callable $control_callback Optional. Function that outputs controls for the widget. Default null.
	 * @param  array    $callback_args    Optional. Data that should be set as the $args property of the widget array
	 *                                   (which is the second parameter passed to your callback). Default null.
	 */
	public function add_dashboard_widget( $widget_id, $widget_name, $callback, $control_callback = null, $callback_args = null ) {

		// Accress global variables.
		global $wp_dashboard_control_callbacks;

		$screen = get_current_screen();
		$private_callback_args = [ '__widget_basename' => $widget_name ];

		if ( is_null( $callback_args ) ) {
			$callback_args = $private_callback_args;
		} else if ( is_array( $callback_args ) ) {
			$callback_args = array_merge( $callback_args, $private_callback_args );
		}

		if ( $control_callback && current_user_can( 'edit_dashboard' ) && is_callable( $control_callback ) ) {

			$wp_dashboard_control_callbacks[$widget_id] = $control_callback;

			if ( isset( $_GET['edit'] ) && $widget_id == $_GET['edit'] ) {

				list( $url ) = explode( '#', add_query_arg( 'edit', false ), 2 );

				$widget_name .= ' <span class="postbox-title-action"><a href="' . esc_url( $url ) . '">' . __( 'Cancel' ) . '</a></span>';
				$callback     = [ $this, 'dashboard_control_callback' ];

			} else {

				list( $url )  = explode( '#', add_query_arg( 'edit', $widget_id ), 2 );
				$widget_name .= ' <span class="postbox-title-action"><a href="' . esc_url( "$url#$widget_id" ) . '" class="edit-box open-box">' . __( 'Configure' ) . '</a></span>';
			}
		}

		$side_widgets = [ 'dashboard_quick_press', 'dashboard_primary' ];
		$location     = 'normal';

		if ( in_array( $widget_id, $side_widgets ) ) {
			$location = 'side';
		}

		$priority = 'core';

		if ( 'dashboard_browser_nag' === $widget_id ) {
			$priority = 'high';
		}

		add_meta_box( $widget_id, $widget_name, $callback, $screen, $location, $priority, $callback_args );
	}

	/**
	 * Outputs controls for the current dashboard widget.
	 *
	 * @access protected
	 * @since  WP 2.7.0
	 * @param  mixed $dashboard
	 * @param  array $meta_box
	 */
	protected function dashboard_control_callback( $dashboard, $meta_box ) {

		echo '<form method="post" class="dashboard-widget-control-form wp-clearfix">';

		wp_dashboard_trigger_widget_control( $meta_box['id'] );

		wp_nonce_field( 'edit-dashboard-widget_' . $meta_box['id'], 'dashboard-widget-nonce' );

		echo '<input type="hidden" name="widget_id" value="' . esc_attr( $meta_box['id'] ) . '" />';

		submit_button( __( 'Submit' ) );

		echo '</form>';
	}

	/**
	 * Displays the dashboard.
	 *
	 * @since  WP 2.5.0
	 * @access public
	 * @return mixed Returns the markup of the dashboard widgets.
	 */
	public static function dashboard() {

		$screen      = get_current_screen();
		$columns     = absint( $screen->get_columns() );
		$columns_css = '';

		if ( $columns ) {
			$columns_css = " columns-$columns";
		}

	?>
	<div id="dashboard-widgets" class="metabox-holder<?php echo $columns_css; ?>">
		<div id="postbox-container-1" class="postbox-container">
		<?php do_meta_boxes( $screen->id, 'normal', '' ); ?>
		</div>
		<div id="postbox-container-2" class="postbox-container">
		<?php do_meta_boxes( $screen->id, 'side', '' ); ?>
		</div>
		<div id="postbox-container-3" class="postbox-container">
		<?php do_meta_boxes( $screen->id, 'column3', '' ); ?>
		</div>
		<div id="postbox-container-4" class="postbox-container">
		<?php do_meta_boxes( $screen->id, 'column4', '' ); ?>
		</div>
	</div>

	<?php
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

	}

	/**
	 * Sample dashboard widgets
	 *
	 * Using this for testing while the system is in development.
	 * This may be retained to aid in development of forks.
	 *
	 * @todo Remove this if no longer desired.
	 */
	function app_sample_dashboard_widget_one() {

	?>
		<h3><?php _e( 'Sample Dashboard Widget #1' ); ?></h3>

		<p><?php _e( 'Using this for testing while the system is in development.' ); ?></p>

		<p><?php _e( 'This may be retained to aid in development of forks.' ); ?></p>
	<?php
	}

	function app_sample_dashboard_widget_two() {

	?>
		<h3><?php _e( 'Sample Dashboard Widget #2' ); ?></h3>

		<p><?php _e( 'Using this for testing while the system is in development.' ); ?></p>

		<p><?php _e( 'This may be retained to aid in development of forks.' ); ?></p>
	<?php
	}

	/**
	 * Site Overview tab
	 *
	 * Displays information about the site in the top panel tabbed content.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tab content.
	 */
	public function site_overview_tab() {

	?>
		<div class="tab-section-wrap tab-section-wrap__dashboard">

			<section class="tab-section tab-section-dashboard tab-section__system-overview">

				<?php

				// Include the system overview.
				$this->system_overview();
				?>
			</section>

			<section class="tab-section tab-section-dashboard tab-section__dashboard-content">

				<h3><?php _e( 'Content' ); ?></h3>

				<div>
					<ul>
						<?php
						// Posts and pages.
						foreach ( [ 'post', 'page' ] as $post_type ) {

							$num_posts = wp_count_posts( $post_type );

							if ( $num_posts && $num_posts->publish ) {

								if ( 'post' == $post_type ) {
									$text = _n( '%s Post', '%s Posts', $num_posts->publish );
								} else {
									$text = _n( '%s Page', '%s Pages', $num_posts->publish );
								}

								$text = sprintf( $text, number_format_i18n( $num_posts->publish ) );
								$post_type_object = get_post_type_object( $post_type );

								if ( $post_type_object && current_user_can( $post_type_object->cap->edit_posts ) ) {
									printf( '<li class="%1$s-count"><a href="edit.php?post_type=%1$s">%2$s</a></li>', $post_type, $text );
								} else {
									printf( '<li class="%1$s-count"><span>%2$s</span></li>', $post_type, $text );
								}

							}
						}
						// Comments.
						$num_comm = wp_count_comments();

						if ( $num_comm && ( $num_comm->approved || $num_comm->moderated ) ) {

							$text = sprintf( _n( '%s Comment', '%s Comments', $num_comm->approved ), number_format_i18n( $num_comm->approved ) );

							?>
							<li class="comment-count"><a href="edit-comments.php"><?php echo $text; ?></a></li>
							<?php

							$moderated_comments_count_i18n = number_format_i18n( $num_comm->moderated );

							// Translators: %s: number of comments in moderation.
							$text = sprintf( _nx( '%s in moderation', '%s in moderation', $num_comm->moderated, 'comments' ), $moderated_comments_count_i18n );

							// Translators: %s: number of comments in moderation.
							$aria_label = sprintf( _nx( '%s comment in moderation', '%s comments in moderation', $num_comm->moderated, 'comments' ), $moderated_comments_count_i18n );

							?>
							<li class="comment-mod-count<?php if ( ! $num_comm->moderated ) { echo ' hidden'; } ?>">
								<a href="edit-comments.php?comment_status=moderated" aria-label="<?php esc_attr_e( $aria_label ); ?>"><?php echo $text; ?></a>
							</li>
							<?php
						}

						/**
						 * Filters the array of extra elements to list in the 'Site Overview'
						 * dashboard widget.
						 *
						 * Prior to 3.8.0, the widget was named 'Right Now'. Each element
						 * is wrapped in list-item tags on output.
						 *
						 * @since WP 3.8.0
						 * @param array $items Array of extra 'Site Overview' widget items.
						 */
						$elements = apply_filters( 'dashboard_glance_items', [] );

						if ( $elements ) {
							echo '<li>' . implode( "</li>\n<li>", $elements ) . "</li>\n";
						}

						?>
					</ul>
				</div>
			</section>

			<?php if ( ! current_user_can( 'list_users' ) ) : ?>
			<section class="tab-section tab-section-dashboard tab-section__dashboard-accounts">

				<h3><?php _e( 'Accounts' ); ?></h3>

				<div>
					<ul>
					<?php
					$result = count_users();

					foreach( $result['avail_roles'] as $role => $count ) {

						if ( 'none' != $role ) {
							echo '<li><a href="' . esc_url( admin_url( 'users.php?role=' . $role ) ) . '">' . $count . ' ' . _n( ucwords( $role ), ucwords( $role ) . 's', $count ) . '</a></li>';
						}

					}
					?>
					</ul>
				</div>
			</section>
			<?php endif; ?>
		</div><!-- .tab-section-wrap -->
		<?php
		/*
			* activity_box_end has a core action, but only prints content when multisite.
			* Using an output buffer is the only way to really check if anything's displayed here.
			*/
		ob_start();

		/**
		 * Fires at the end of the 'Site Overview' dashboard widget.
		 *
		 * Prior to WP 3.8.0, the widget was named 'Right Now'.
		 *
		 * @since WP 2.5.0
		 */
		do_action( 'rightnow_end' );

		/**
		 * Fires at the end of the 'Site Overview' dashboard widget.
		 *
		 * Prior to WP 3.8.0, the widget was named 'Right Now'.
		 *
		 * @since WP 2.0.0
		 */
		do_action( 'activity_box_end' );

		$actions = ob_get_clean();

		if ( ! empty( $actions ) ) : ?>
		<div class="sub">
			<?php echo $actions; ?>
		</div>
		<?php endif;
	}

	/**
	 * Update system overview
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function system_overview() {

		$theme_name = wp_get_theme();

		if ( current_user_can( 'switch_themes' ) ) {
			$theme_name = sprintf( '<a href="themes.php">%1$s</a>', $theme_name );
		}

		$content = sprintf(
			'<h3>%1s %2s</h3>',
			APP_NAME,
			get_bloginfo( 'version' )
		);

		$content .= sprintf(
			'
			<div class="tab-icon-content tab-icon-content-dashboard tab-icon-content__system-overview">
				<figure>
					<a href="%1s">
						<img class="avatar" src="%2s" alt="%3s" width="80" height="80" />
					</a>
					<figcaption class="screen-reader-text">%4s</figcaption>
				</figure>
				<div>
			',
			esc_url( admin_url( 'about.php' ) ),
			esc_url( app_assets_url( 'images/server-icon-round.jpg' ) ),
			__( '' ),
			__( '' )
		);

		$content .= sprintf(
			'<p class="dashboard-overview-item"><strong>%1s</strong> <span id="active-theme">%2s</span></p>',
			__( 'Active theme:' ),
			$theme_name
		);

		$content .= sprintf(
			'<p class="dashboard-overview-item"><strong>%1s</strong> <span id="php-version">%2s</span></p>',
			__( 'PHP version:' ),
			phpversion()
		);

		// Check if search engines are asked not to index this site.
		if ( ! is_network_admin() && ! is_user_admin() && current_user_can( 'manage_options' ) && '0' == get_option( 'blog_public' ) ) {

			/**
			 * Filters the link title attribute for the 'Search Engines Discouraged'
			 * message displayed in the 'Site Overview' dashboard widget.
			 *
			 * Prior to WP 3.8.0, the widget was named 'Right Now'.
			 *
			 * @since WP 3.0.0
			 * @since WP 4.5.0 The default for `$title` was updated to an empty string.
			 * @param string $title Default attribute text.
			 */
			$title = apply_filters( 'privacy_on_link_title', '' );

			/**
			 * Filters the link label for the 'Search Engines Discouraged' message
			 * displayed in the 'Site Overview' dashboard widget.
			 *
			 * Prior to WP 3.8.0, the widget was named 'Right Now'.
			 *
			 * @since WP 3.0.0
			 * @param string $content Default text.
			 */
			$link_text = apply_filters( 'engines_discouraged_link_text' , __( 'Search Engines Discouraged' ) );
			if ( '' === $title ) {
				$title_attr = '';
			} else {
				$title_attr = sprintf(
					' title="%1s"',
					$title
				);
			}

			// Search engines message/link.
			if ( current_user_can( 'manage_options' ) ) {
				$content .= "<p class='dashboard-overview-item'><a href='options-reading.php'$title_attr>$link_text</a></p>";
			}
		}

		$content .= '</div></div><!-- .tab-icon-content -->';

		/**
		 * Filters the text displayed in the 'Site Overview' dashboard widget.
		 *
		 * Prior to 3.8.0, the widget was named 'Right Now'.
		 *
		 * @since WP 4.4.0
		 * @since 1.0.0 Modified by this fork.
		 * @param string $content Default text.
		 */
		$content = apply_filters( 'update_right_now_text', $content );

		echo $content;
	}

	/**
	 * Network Overview
	 *
	 * Dashboard widget that displays some basic stats about the network.
	 *
	 * @since WP 3.1.0
	 * @access public
	 * @return mixed Returns the markup of the Network Overview content
	 */
	public function network_overview() {

		$actions = [];

		if ( current_user_can( 'create_sites' ) ) {
			$actions['create-site'] = '<a href="' . network_admin_url( 'site-new.php' ) . '">' . __( 'Create a New Site' ) . '</a>';
		}

		if ( current_user_can( 'create_users' ) ) {
			$actions['create-user'] = '<a href="' . network_admin_url( 'user-new.php' ) . '">' . __( 'Create a New User' ) . '</a>';
		}

		$c_users = get_user_count();
		$c_blogs = get_blog_count();

		// Translators: %s: number of users on the network.
		$user_text = sprintf( _n( '%s user', '%s users', $c_users ), number_format_i18n( $c_users ) );
		// Translators: %s: number of sites on the network.
		$blog_text = sprintf( _n( '%s site', '%s sites', $c_blogs ), number_format_i18n( $c_blogs ) );

		// Translators: 1: text indicating the number of sites on the network, 2: text indicating the number of users on the network.
		$sentence = sprintf( __( 'You have %1$s and %2$s.' ), $blog_text, $user_text );

		if ( $actions ) {
			echo '<ul class="subsubsub">';
			foreach ( $actions as $class => $action ) {
				$actions[ $class ] = "\t<li class='$class'>$action";
			}
			echo implode( " |</li>\n", $actions ) . "</li>\n";
			echo '</ul>';
		}
	?>
		<p class="youhave"><?php echo $sentence; ?></p>

		<?php
			/**
			 * Fires in the Network Admin 'Site Overview' dashboard widget
			 * just before the user and site search form fields.
			 *
			 * @since MU (3.0.0)
			 *
			 * @param null $unused
			 */
			do_action( 'wpmuadminresult', '' );
		?>

		<form action="<?php echo network_admin_url( 'users.php' ); ?>" method="get">
			<p>
				<label class="screen-reader-text" for="search-users"><?php _e( 'Search Users' ); ?></label>
				<input type="search" name="s" value="" size="30" autocomplete="off" id="search-users"/>
				<?php submit_button( __( 'Search Users' ), '', false, false, array( 'id' => 'submit_users' ) ); ?>
			</p>
		</form>

		<form action="<?php echo network_admin_url( 'sites.php' ); ?>" method="get">
			<p>
				<label class="screen-reader-text" for="search-sites"><?php _e( 'Search Sites' ); ?></label>
				<input type="search" name="s" value="" size="30" autocomplete="off" id="search-sites"/>
				<?php submit_button( __( 'Search Sites' ), '', false, false, array( 'id' => 'submit_sites' ) ); ?>
			</p>
		</form>
	<?php
		/**
		 * Fires at the end of the 'Right Now' widget in the Network Admin dashboard.
		 *
		 * @since MU (3.0.0)
		 */
		do_action( 'mu_rightnow_end' );

		/**
		 * Fires at the end of the 'Right Now' widget in the Network Admin dashboard.
		 *
		 * @since MU (3.0.0)
		 */
		do_action( 'mu_activity_box_end' );
	}

	/**
	 * Draft posts form
	 *
	 * @since  WP 3.8.0
	 * @access public
	 * @global int $post_ID
	 * @param  string $error_msg Optional. Error message. Default false.
	 * @return mixed Returns the markup of the draft posts form.
	 */
	public function dashboard_draft_posts( $error_msg = null ) {

		// Access global variables.
		global $post_ID;

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$error_msg = '';

		/**
		 * Check if a new auto-draft (= no new post_ID) is needed or
		 * if the old can be used.
		 *
		 * Get the last post_ID.
		 */
		$last_post_id = (int) get_user_option( 'dashboard_quick_press_last_post_id' );

		if ( $last_post_id ) {

			$post = get_post( $last_post_id );

			// auto-draft doesn't exists anymore.
			if ( empty( $post ) || $post->post_status != 'auto-draft' ) {

				$post = get_default_post_to_edit( 'post', true );

				// Save post_ID.
				update_user_option( get_current_user_id(), 'dashboard_quick_press_last_post_id', (int) $post->ID );

			} else {

				// Remove the auto draft title.
				$post->post_title = '';
			}

		} else {

			$post    = get_default_post_to_edit( 'post' , true );
			$user_id = get_current_user_id();

			// Don't create an option if this is a super admin who does not belong to this site.
			if ( in_array( get_current_blog_id(), array_keys( get_blogs_of_user( $user_id ) ) ) ) {

				// Save post_ID.
				update_user_option( $user_id, 'dashboard_quick_press_last_post_id', (int) $post->ID );
			}
		}

		$post_ID = (int) $post->ID;
	?>
		<div class="tab-section-wrap tab-section-wrap__dashboard hide-if-no-js">

			<section class="tab-section tab-section-dashboard tab-section__quick-draft">

				<h3><?php _e( 'Quick Draft' ); ?></h3>

				<p class="description"><?php _e( 'Save a thought or a note as a standard post to be completed & published at a later time.' ); ?></p>

				<form name="post" action="<?php echo esc_url( admin_url( 'post.php' ) ); ?>" method="post" id="quick-press" class="initial-form">

					<?php if ( $error_msg ) : ?>
					<div class="error dashboard-quick-draft-error"><?php echo $error_msg; ?></div>
					<?php endif; ?>

					<div class="input-text-wrap" id="title-wrap">
						<label class="screen-reader-text prompt" for="title" id="title-prompt-text">

							<?php
							// This filter is documented in wp-admin/edit-form-advanced.php'
							echo apply_filters( 'enter_title_here', __( 'Title' ), $post );
							?>
						</label>
						<input type="text" name="post_title" id="title" autocomplete="off" />
					</div>

					<div class="textarea-wrap" id="description-wrap">
						<label class="screen-reader-text prompt" for="content" id="content-prompt-text"><?php _e( 'Draft content' ); ?></label>
						<textarea name="content" id="content" class="mceEditor" rows="3" cols="15" autocomplete="off"></textarea>
					</div>

					<p class="submit">
						<input type="hidden" name="action" id="quickpost-action" value="post-quickdraft-save" />
						<input type="hidden" name="post_ID" value="<?php echo $post_ID; ?>" />
						<input type="hidden" name="post_type" value="post" />

						<?php wp_nonce_field( 'add-post' ); ?>

						<?php submit_button( __( 'Save Draft' ), 'primary', 'save', false, [ 'id' => 'save-post' ] ); ?>
					</p>

				</form>

			</section>

			<section id="dashboard-recent-drafts" class="tab-section tab-section-dashboard tab-section__recent-drafts">

				<h3><?php _e( 'Recent Drafts' ); ?></h3>

				<p class="description"><?php _e( 'The following posts have not been published.' ); ?></p>

				<?php $this->dashboard_recent_drafts(); ?>
			</section>
		</div>
		<?php
	}

	/**
	 * Show recent drafts of the user on the dashboard
	 *
	 * @since  WP 2.7.0
	 * @access public
	 * @param  array $drafts Array of posts.
	 * @return mixed Returns the markup of the tab content.
	 */
	public function dashboard_recent_drafts( $drafts = false ) {

		if ( ! $drafts ) {

			$query_args = [
				'post_type'      => 'post',
				'post_status'    => 'draft',
				'author'         => get_current_user_id(),
				'posts_per_page' => 4,
				'orderby'        => 'modified',
				'order'          => 'DESC'
			];

			/**
			 * Filters the post query arguments for the 'Recent Drafts' dashboard widget.
			 *
			 * @since WP 4.4.0
			 * @param array $query_args The query arguments for the 'Recent Drafts' dashboard widget.
			 */
			$query_args = apply_filters( 'dashboard_recent_drafts_query_args', $query_args );
			$drafts     = get_posts( $query_args );
		}
	?>
			<div class="drafts">

				<ul>
				<?php

				$drafts = array_slice( $drafts, 0, 4 );

				foreach ( $drafts as $draft ) {

					$url   = get_edit_post_link( $draft->ID );
					$title = _draft_or_post_title( $draft->ID );
					?>

					<li>
						<p class="draft-title"><a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ); ?>"><?php echo esc_html( $title ); ?></a>

						<time datetime="<?php echo get_the_time( 'c', $draft ); ?>"><?php echo get_the_time( __( 'F j, Y' ), $draft ); ?></time></p>

						<?php if ( $the_content = wp_trim_words( $draft->post_content, 10 ) ) {
							echo '<p>' . $the_content . '</p>';
						} ?>

					</li>
				<?php } ?>

				</ul>

				<?php
				// Print "view all" link if more than 4 draft posts.
				if ( count( $drafts ) > 4 ) {

					echo sprintf(
						'<p class="view-all"><a href="%1s">%2s</a></p>',
						esc_url( admin_url( 'edit.php?post_status=draft' ) ),
						__( 'View all drafts' )
					);
				}

				// Print notice if no draft posts.
				if ( count( $drafts ) < 1 ) {

					echo sprintf(
						'<p>%1s</p>',
						__( 'No draft posts are available.' )
					);
				}
				?>
			</div>
		<?php
	}

	/**
	 * Outputs a row for the Recent Comments widget.
	 *
	 * @access private
	 * @since WP 2.7.0
	 * @global WP_Comment $comment
	 * @param WP_Comment $comment   The current comment.
	 * @param bool       $show_date Optional. Whether to display the date.
	 */
	private function dashboard_recent_comments_row( &$comment, $show_date = true ) {

		$GLOBALS['comment'] = clone $comment;

		if ( $comment->comment_post_ID > 0 ) {

			$comment_post_title = _draft_or_post_title( $comment->comment_post_ID );
			$comment_post_url   = get_the_permalink( $comment->comment_post_ID );
			$comment_post_link  = "<a href='$comment_post_url'>$comment_post_title</a>";

		} else {
			$comment_post_link = '';
		}

		$actions_string = '';

		if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {

			// Pre-order it: Approve | Reply | Edit | Spam | Trash.
			$actions = [
				'approve'   => '',
				'unapprove' => '',
				'reply'     => '',
				'edit'      => '',
				'spam'      => '',
				'trash'     => '',
				'delete'    => '',
				'view'      => '',
			];

			$del_nonce     = esc_html( '_wpnonce=' . wp_create_nonce( "delete-comment_$comment->comment_ID" ) );
			$approve_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "approve-comment_$comment->comment_ID" ) );

			$approve_url   = esc_url( "comment.php?action=approvecomment&p=$comment->comment_post_ID&c=$comment->comment_ID&$approve_nonce" );
			$unapprove_url = esc_url( "comment.php?action=unapprovecomment&p=$comment->comment_post_ID&c=$comment->comment_ID&$approve_nonce" );
			$spam_url      = esc_url( "comment.php?action=spamcomment&p=$comment->comment_post_ID&c=$comment->comment_ID&$del_nonce" );
			$trash_url     = esc_url( "comment.php?action=trashcomment&p=$comment->comment_post_ID&c=$comment->comment_ID&$del_nonce" );
			$delete_url    = esc_url( "comment.php?action=deletecomment&p=$comment->comment_post_ID&c=$comment->comment_ID&$del_nonce" );

			$actions['approve'] = "<a href='$approve_url' data-wp-lists='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=approved' class='vim-a' aria-label='" . esc_attr__( 'Approve this comment' ) . "'>" . __( 'Approve' ) . '</a>';

			$actions['unapprove'] = "<a href='$unapprove_url' data-wp-lists='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=unapproved' class='vim-u' aria-label='" . esc_attr__( 'Unapprove this comment' ) . "'>" . __( 'Unapprove' ) . '</a>';

			$actions['edit'] = "<a href='comment.php?action=editcomment&amp;c={$comment->comment_ID}' aria-label='" . esc_attr__( 'Edit this comment' ) . "'>". __( 'Edit' ) . '</a>';

			$actions['reply'] = '<a onclick="window.commentReply && commentReply.open(\'' . $comment->comment_ID . '\',\''.$comment->comment_post_ID.'\');return false;" class="vim-r hide-if-no-js" aria-label="' . esc_attr__( 'Reply to this comment' ) . '" href="#">' . __( 'Reply' ) . '</a>';

			$actions['spam'] = "<a href='$spam_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::spam=1' class='vim-s vim-destructive' aria-label='" . esc_attr__( 'Mark this comment as spam' ) . "'>" . _x( 'Spam', 'verb' ) . '</a>';

			if ( ! EMPTY_TRASH_DAYS ) {

				$actions['delete'] = "<a href='$delete_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::trash=1' class='delete vim-d vim-destructive' aria-label='" . esc_attr__( 'Delete this comment permanently' ) . "'>" . __( 'Delete Permanently' ) . '</a>';

			} else {

				$actions['trash'] = "<a href='$trash_url' data-wp-lists='delete:the-comment-list:comment-$comment->comment_ID::trash=1' class='delete vim-d vim-destructive' aria-label='" . esc_attr__( 'Move this comment to the Trash' ) . "'>" . _x( 'Trash', 'verb' ) . '</a>';
			}

			$actions['view'] = '<a class="comment-link" href="' . esc_url( get_comment_link( $comment ) ) . '" aria-label="' . esc_attr__( 'View this comment' ) . '">' . __( 'View' ) . '</a>';

			/**
			 * Filters the action links displayed for each comment in the 'Recent Comments'
			 * dashboard widget.
			 *
			 * @since WP 2.6.0
			 * @param array $actions An array of comment actions. Default actions include:
			 *              'Approve', 'Unapprove', 'Edit', 'Reply', 'Spam', 'Delete', and 'Trash'.
			 * @param WP_Comment $comment The comment object.
			 */
			$actions = apply_filters( 'comment_row_actions', array_filter( $actions ), $comment );

			$i = 0;

			foreach ( $actions as $action => $link ) {

				++$i;

				( ( ( 'approve' == $action || 'unapprove' == $action ) && 2 === $i ) || 1 === $i ) ? $sep = '' : $sep = ' | ';

				// Reply and quickedit need a hide-if-no-js span.
				if ( 'reply' == $action || 'quickedit' == $action ) {
					$action .= ' hide-if-no-js';
				}

				if ( 'view' === $action && '1' !== $comment->comment_approved ) {
					$action .= ' hidden';
				}

				$actions_string .= "<span class='$action'>$sep$link</span>";
			}
		}
	?>

			<li id="comment-<?php echo $comment->comment_ID; ?>" <?php comment_class( [ 'comment-item', wp_get_comment_status( $comment ) ], $comment ); ?>>

				<?php echo get_avatar( $comment, 50, 'generic' ); ?>

				<?php if ( !$comment->comment_type || 'comment' == $comment->comment_type ) : ?>

				<div class="dashboard-comment-wrap has-row-actions">
					<p class="comment-meta">
					<?php
						// Comments might not have a post they relate to, e.g. programmatically created ones.
						if ( $comment_post_link ) {

							printf(
								// Translators: 1: comment author, 2: post link, 3: notification if the comment is pending.
								__( 'From %1$s on %2$s %3$s' ),
								'<cite class="comment-author">' . get_comment_author_link( $comment ) . '</cite>',
								$comment_post_link,
								'<span class="approve">' . __( '[Pending]' ) . '</span>'
							);

						} else {
							printf(
								// Translators: 1: comment author, 2: notification if the comment is pending.
								__( 'From %1$s %2$s' ),
								'<cite class="comment-author">' . get_comment_author_link( $comment ) . '</cite>',
								'<span class="approve">' . __( '[Pending]' ) . '</span>'
							);
						}
					?>
					</p>

					<?php
					else :
						switch ( $comment->comment_type ) {
							default :
								$type = ucwords( $comment->comment_type );
						}

						$type = esc_html( $type );
					?>
				<div class="dashboard-comment-wrap has-row-actions">
					<p class="comment-meta">
					<?php
						// Custom comment types might not have a post they relate to, e.g. programmatically created ones.
						if ( $comment_post_link ) {
							printf(
								// Translators: 1: type of comment, 2: post link, 3: notification if the comment is pending.
								_x( '%1$s on %2$s %3$s', 'dashboard' ),
								"<strong>$type</strong>",
								$comment_post_link,
								'<span class="approve">' . __( '[Pending]' ) . '</span>'
							);
						} else {
							printf(
								// Translators: 1: type of comment, 2: notification if the comment is pending.
								_x( '%1$s %2$s', 'dashboard' ),
								"<strong>$type</strong>",
								'<span class="approve">' . __( '[Pending]' ) . '</span>'
							);
						}
					?>
					</p>
					<p class="comment-author"><?php comment_author_link( $comment ); ?></p>
					<?php endif; // comment_type ?>

					<blockquote>
						<p><?php comment_excerpt( $comment ); ?></p>
					</blockquote>

					<?php if ( $actions_string ) : ?>

					<p class="row-actions"><?php echo $actions_string; ?></p>

				<?php endif; ?>
				</div>
			</li>
	<?php
		$GLOBALS['comment'] = null;
	}

	/**
	 * Site Activity widget
	 *
	 * @since WP 3.8.0
	 * @access public
	 * @return mixed Returns the markup of the Site Activity widget.
	 */
	public function dashboard_site_activity() {

		echo '<div id="activity-widget">';

		$future_posts = $this->dashboard_recent_posts( [
			'max'     => 5,
			'status'  => 'future',
			'order'   => 'ASC',
			'title'   => __( 'Publishing Soon' ),
			'id'      => 'future-posts',
		] );

		$recent_posts = $this->dashboard_recent_posts( [
			'max'     => 5,
			'status'  => 'publish',
			'order'   => 'DESC',
			'title'   => __( 'Recently Published' ),
			'id'      => 'published-posts',
		] );

		$recent_comments = $this->dashboard_recent_comments();

		if ( ! $future_posts && ! $recent_posts && ! $recent_comments ) {

			echo '<div class="no-activity">';
			echo '<p class="smiley" aria-hidden="true"></p>';
			echo '<p>' . __( 'No activity yet!' ) . '</p>';
			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Publishing Soon and Recently Published sections.
	 *
	 * @since WP 3.8.0
	 * @access public
	 * @param array $args {
	 *     An array of query and display arguments.
	 *
	 *     @type int    $max     Number of posts to display.
	 *     @type string $status  Post status.
	 *     @type string $order   Designates ascending ('ASC') or descending ('DESC') order.
	 *     @type string $title   Section title.
	 *     @type string $id      The container id.
	 * }
	 * @return bool Returns false if no posts were found. Returns true otherwise.
	 * @return mixed Returns the markup of the post queries.
	 */
	public function dashboard_recent_posts( $args ) {

		$query_args = [
			'post_type'      => 'post',
			'post_status'    => $args['status'],
			'orderby'        => 'date',
			'order'          => $args['order'],
			'posts_per_page' => intval( $args['max'] ),
			'no_found_rows'  => true,
			'cache_results'  => false,
			'perm'           => ( 'future' === $args['status'] ) ? 'editable' : 'readable',
		];

		/**
		 * Filters the query arguments used for the Recent Posts widget.
		 *
		 * @since WP 4.2.0
		 * @param array $query_args The arguments passed to WP_Query to produce the list of posts.
		 */
		$query_args = apply_filters( 'dashboard_recent_posts_query_args', $query_args );
		$posts      = new WP_Query( $query_args );

		if ( $posts->have_posts() ) {

			echo '<div id="' . $args['id'] . '" class="activity-block">';

			echo '<h3>' . $args['title'] . '</h3>';

			echo '<ul>';

			$today    = date( 'Y-m-d', current_time( 'timestamp' ) );
			$tomorrow = date( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );

			while ( $posts->have_posts() ) {

				$posts->the_post();

				$time = get_the_time( 'U' );

				if ( date( 'Y-m-d', $time ) == $today ) {
					$relative = __( 'Today' );

				} elseif ( date( 'Y-m-d', $time ) == $tomorrow ) {
					$relative = __( 'Tomorrow' );

				} elseif ( date( 'Y', $time ) !== date( 'Y', current_time( 'timestamp' ) ) ) {

					// Translators: date and time format for recent posts on the dashboard, from a different calendar year, see https://secure.php.net/date.
					$relative = date_i18n( __( 'M jS Y' ), $time );

				} else {

					// Translators: date and time format for recent posts on the dashboard, see https://secure.php.net/date.
					$relative = date_i18n( __( 'M jS' ), $time );
				}

				// Use the post edit link for those who can edit, the permalink otherwise.
				$recent_post_link    = current_user_can( 'edit_post', get_the_ID() ) ? get_edit_post_link() : get_permalink();
				$draft_or_post_title = _draft_or_post_title();

				printf(
					'<li><span>%1$s</span> <a href="%2$s" aria-label="%3$s">%4$s</a></li>',
					// Translators: 1: relative date, 2: time */
					sprintf( _x( '%1$s, %2$s', 'dashboard' ), $relative, get_the_time() ),
					$recent_post_link,
					// Translators: %s: post title */
					esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $draft_or_post_title ) ),
					$draft_or_post_title
				);
			}

			echo '</ul>';
			echo '</div>';

		} else {
			return false;
		}

		wp_reset_postdata();

		return true;
	}

	/**
	 * Show Comments section
	 *
	 * @since  WP 3.8.0
	 * @access public
	 * @param  int $total_items Optional. Number of comments to query. Default 5.
	 * @return bool Returns false if no comments were found. Returns true otherwise.
	 */
	public function dashboard_recent_comments( $total_items = 5 ) {

		// Select all comment types and filter out spam later for better query performance.
		$comments       = [];
		$comments_query = [
			'number' => $total_items * 5,
			'offset' => 0
		];

		if ( ! current_user_can( 'edit_posts' ) ) {
			$comments_query['status'] = 'approve';
		}

		while ( count( $comments ) < $total_items && $possible = get_comments( $comments_query ) ) {

			if ( ! is_array( $possible ) ) {
				break;
			}

			foreach ( $possible as $comment ) {

				if ( ! current_user_can( 'read_post', $comment->comment_post_ID ) ) {
					continue;
				}

				$comments[] = $comment;

				if ( count( $comments ) == $total_items ) {
					break 2;
				}

			}

			$comments_query['offset'] += $comments_query['number'];
			$comments_query['number'] = $total_items * 10;
		}

		if ( $comments ) {

			echo '<div id="latest-comments" class="activity-block">';
			echo '<h3>' . __( 'Recent Comments' ) . '</h3>';

			echo '<ul id="the-comment-list" data-wp-lists="list:comment">';
			foreach ( $comments as $comment )
				$this->dashboard_recent_comments_row( $comment );
			echo '</ul>';

			if ( current_user_can( 'edit_posts' ) ) {
				echo '<h3 class="screen-reader-text">' . __( 'View more comments' ) . '</h3>';
				_get_list_table( 'AppNamepace\Backend\Comments_List_Table' )->views();
			}

			wp_comment_reply( -1, false, 'dashboard', false );
			wp_comment_trashnotice();

			echo '</div>';
		} else {
			return false;
		}
		return true;
	}

	/**
	 * Calls widget control callback.
	 *
	 * @since  WP 2.5.0
	 * @access public
	 * @global array $wp_dashboard_control_callbacks
	 * @param  int $widget_control_id Registered Widget ID.
	 * @return string Returns a callback function name per widget.
	 */
	public function wp_dashboard_trigger_widget_control( $widget_control_id = false ) {

		// Access global variables.
		global $wp_dashboard_control_callbacks;

		if ( is_scalar( $widget_control_id ) && $widget_control_id && isset( $wp_dashboard_control_callbacks[$widget_control_id] ) && is_callable( $wp_dashboard_control_callbacks[$widget_control_id] ) ) {

			call_user_func( $wp_dashboard_control_callbacks[$widget_control_id], '', [ 'id' => $widget_control_id, 'callback' => $wp_dashboard_control_callbacks[$widget_control_id] ] );
		}
	}

	/**
	 * Display file upload quota on dashboard
	 *
	 * Runs on the {@see 'activity_box_end'} hook in wp_dashboard_right_now().
	 *
	 * @since  WP 3.0.0
	 * @access public
	 * @return bool|null Returns true if not multisite, user can't upload files, or the space check option is disabled.
	 * @return mixed Returns the markup of the quota content.
	 */
	public function dashboard_quota() {

		if ( ! is_multisite() || !current_user_can( 'upload_files' ) || get_site_option( 'upload_space_check_disabled' ) ) {
			return true;
		}

		$quota = get_space_allowed();
		$used  = get_space_used();

		if ( $used > $quota ) {
			$percentused = '100';
		} else {
			$percentused = ( $used / $quota ) * 100;
		}

		$used_class  = ( $percentused >= 70 ) ? ' warning' : '';
		$used        = round( $used, 2 );
		$percentused = number_format( $percentused );

		?>
		<h3 class="mu-storage"><?php _e( 'Storage Space' ); ?></h3>

		<div class="mu-storage">
			<ul>
				<li class="storage-count">
					<?php $text = sprintf(
						// Translators: %s: number of megabytes.
						__( '%s MB Space Allowed' ),
						number_format_i18n( $quota )
					);
					printf(
						'<a href="%1$s">%2$s <span class="screen-reader-text">(%3$s)</span></a>',
						esc_url( admin_url( 'upload.php' ) ),
						$text,
						__( 'Manage Uploads' )
					); ?>
				</li><li class="storage-count <?php echo $used_class; ?>">
					<?php $text = sprintf(
						// Translators: 1: number of megabytes, 2: percentage.
						__( '%1$s MB (%2$s%%) Space Used' ),
						number_format_i18n( $used, 2 ),
						$percentused
					);
					printf(
						'<a href="%1$s" class="musublink">%2$s <span class="screen-reader-text">(%3$s)</span></a>',
						esc_url( admin_url( 'upload.php' ) ),
						$text,
						__( 'Manage Uploads' )
					); ?>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Help content
	 *
	 * Add content to the help section of the dashboard page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help() {

		$screen = get_current_screen();
		if ( 'dashboard' != $screen->id ) {
			return;
		}

		$screen->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview' ),
			'content'  => null,
			'callback' => [ $this, 'help_overview' ],
		] );

		$screen->add_help_tab( [
			'id'      => 'help-navigation',
			'title'   => __( 'Navigation' ),
			'content'  => null,
			'callback' => [ $this, 'help_navigation' ],
		] );

		$screen->add_help_tab( [
			'id'      => 'help-layout',
			'title'   => __( 'Widgets Layout' ),
			'content'  => null,
			'callback' => [ $this, 'help_layout' ],
		] );

		$screen->add_help_tab( [
			'id'      => 'help-content',
			'title'   => __( 'Content' ),
			'content'  => null,
			'callback' => [ $this, 'help_content' ],
		] );

		// Add a help sidebar.
		$screen->set_help_sidebar(
			$this->help_sidebar()
		);

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

		$help .= '<p>' . __( 'Welcome to your dashboard. This is the screen you will see when you log in to your site, and gives you access to all the site management features. You can get help for any screen by clicking the Help tab above the screen title.' ) . '</p>';

		echo $help;

	}

	/**
	 * Navigation help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_navigation() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Navigation' )
		);

		$help .= '<p>' . __( 'The left-hand navigation menu provides links to all of the administration screens, with submenu items displayed on hover. You can minimize this menu to a narrow icon strip by clicking on the Collapse Menu arrow at the bottom.' ) . '</p>';

		$help .= '<p>' . __( 'Links in the toolbar at the top of the screen connect your dashboard and the front end of your site, and provide access to your profile and helpful information.' ) . '</p>';

		echo $help;

	}

	/**
	 * Widgets Layout help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_layout() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Widgets Layout' )
		);

		$help .= '<p>' . __( 'You can use the following controls to arrange your Dashboard screen to suit your workflow. This is true on most other administration screens as well.' ) . '</p>';
		$help .= '<p>' . __( '<strong>Screen Options</strong> &mdash; Use the Screen Options tab to choose which Dashboard boxes to show.' ) . '</p>';
		$help .= '<p>' . __( '<strong>Drag and Drop</strong> &mdash; To rearrange the boxes, drag and drop by clicking on the title bar of the selected box and releasing when you see a gray dotted-line rectangle appear in the location you want to place the box.' ) . '</p>';
		$help .= '<p>' . __( '<strong>Box Controls</strong> &mdash; Click the title bar of the box to expand or collapse it. Some boxes added by plugins may have configurable content, and will show a &#8220;Configure&#8221; link in the title bar if you hover over it.' ) . '</p>';

		echo $help;

	}

	/**
	 * Content help content
	 *
	 * @since 1.0.0
	 * @access public
	 * @return mixed Returns the markup of the help content.
	 */
	public function help_content() {

		$help = sprintf(
			'<h3>%1s</h3>',
			__( 'Content' )
		);

		$help .= '<p>' . __( 'The boxes on your Dashboard screen are:' ) . '</p>';
		if ( current_user_can( 'edit_posts' ) )
			$help .= '<p>' . __( '<strong>Site Overview</strong> &mdash; Displays a summary of the content on your site and identifies which theme and version of the website management system that you are using.' ) . '</p>';
			$help .= '<p>' . __( '<strong>Activity</strong> &mdash; Shows the upcoming scheduled posts, recently published posts, and the most recent comments on your posts and allows you to moderate them.' ) . '</p>';
		if ( is_blog_admin() && current_user_can( 'edit_posts' ) )
			$help .= '<p>' . __( "<strong>Quick Draft</strong> &mdash; Allows you to create a new post and save it as a draft. Also displays links to the 5 most recent draft posts you've started." ) . '</p>';
		if ( current_user_can( 'edit_theme_options' ) )
			$help .= '<p>' . __( '<strong>Welcome</strong> &mdash; Shows links for some of the most common tasks when setting up a new site.' ) . '</p>';

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

		$set_help_sidebar = apply_filters( 'set_help_sidebar_index', '' );
		return $set_help_sidebar;
	}
}