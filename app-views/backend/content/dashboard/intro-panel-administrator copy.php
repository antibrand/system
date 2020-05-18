<?php
/**
 * Dashboard intro panel: Administrator
 *
 * @package App_Package
 * @subpackage Administration
 */

use \AppNamespace\Backend\Dashboard as Dashboard;

// App version.
$version = get_bloginfo( 'version' );

/**
 * Top panel tabs
 */
if ( isset( $_GET['tab-active'] ) ) {
	$tab_active = empty( $_GET['tab-active'] ) ? '' : 'tab-active';
	update_user_meta( get_current_user_id(), 'top_panel_tab', $tab_active );
} else {
	$tab_active = get_user_meta( get_current_user_id(), 'top_panel_tab', 'tab-active' );
	if ( '' === $tab_active ) {
		$tab_active = 'tab-active';
	}
}

/**
 * Intro panel description
 *
 * Uses the white label tagline if available.
 */
if ( defined( 'APP_NAME' ) ) {
	$panel_heading = sprintf(
		'<h2>%1s %2s</h2>',
		__( 'Welcome to' ),
		APP_NAME
	);
} else {
	$panel_heading = sprintf(
		'<h2>%1s</h2>',
		__( 'Welcome' )
	);
}

if ( defined( 'APP_TAGLINE' ) ) {
	$panel_description = sprintf(
		'<p class="description">%1s</p>',
		APP_TAGLINE
	);
} else {
	$panel_description = sprintf(
		'<p class="description">%1s</p>',
		__( 'generic, white-label website management' )
	);
}

// Learn more link.
if ( APP_WEBSITE ) {
	$learn_link = APP_WEBSITE;
} else {
	$learn_link = admin_url( 'about.php' );
}

// $learn_link = apply_filters( 'dashboard_learn_link', $learn_link );

// Get the current user data for the greeting.
$current_user = wp_get_current_user();
$user_id      = get_current_user_id();
$user_name    = $current_user->display_name;

// Get theme data.
$get_theme         = wp_get_theme();
$theme_name        = $get_theme->get( 'Name' );
$theme_description = $get_theme->get( 'Description' );
$theme_tags        = $get_theme->get( 'Tags' );
$theme_icon        = $get_theme->get_theme_icon();

// Theme description.
if ( $theme_description ) {
	$theme_description = sprintf(
		'<p>%1s</p>',
		$theme_description
	);
} else {
	$theme_description = '';
}

// Theme icon.
if ( $theme_icon ) {
	$customize_icon = $theme_icon;
} else {
	$customize_icon = app_assets_url( 'images/customize-icon-round.jpg' );
}

// Theme tags.
if ( $theme_tags ) {
	$theme_tags = sprintf(
		'<p><strong>%1s</strong> %2s</p>',
		__( 'Tags:' ),
		implode( ', ', $theme_tags )
	);
} else {
	$theme_tags = '';
}

/**
 * Dashboard widgets tab
 *
 * Hide the "Widgets" tab and content if no dashboard widgets
 * have been registered for this screen.
 */
global $wp_meta_boxes;
$screen = get_current_screen();

if ( ! isset( $wp_meta_boxes[$screen->id] ) ) {
	$no_widgets = ' style="display: none !important"';
} else {
	$no_widgets = '';
}

$user_greeting = sprintf(
	'%1s %2s',
	esc_html__( 'Hello,' ),
	$user_name
);

?>
<div class="top-panel-inner">

	<?php // echo $panel_heading; ?>
	<?php // echo $panel_description; ?>

	<div class="app-tabs top-panel-tabbed-content" data-tabbed="tabbed" data-tabevent="click">

		<ul class="app-tabs-list app-tabs-horizontal hide-if-no-js">
			<li class="app-tab active"><a href="#overview"><?php _e( 'Overview' ); ?></a>
			<li class="app-tab"><a href="#manage"><?php _e( 'Manage' ); ?></a>
			<li class="app-tab"><a href="#customize"><?php _e( 'Customize' ); ?></a>
			<li class="app-tab"<?php echo $no_widgets; ?>><a href="#widgets"><?php _e( 'Widgets' ); ?></a>
			<li class="app-tab"><a href="#drafts"><?php _e( 'Drafts' ); ?></a>
		</ul>

		<div id='overview' class="app-tab-content top-panel-tab">

			<header>
				<h2><?php _e( 'Site Overview' ); ?></h2>
				<p class="description"><?php _e( 'This information is provided to you as a site administrator.' ); ?></p>
			</header>

			<?php Dashboard :: site_overview_tab(); ?>

		</div>

		<div id='manage' class="app-tab-content top-panel-tab">

			<header>
				<h2><?php _e( 'Manage Your Site' ); ?></h2>
				<p class="description"><?php _e( 'This dashboard provides handy links to the various features of the website management system.' ); ?></p>
			</header>

			<div class="top-panel-column-container">

				<div id="dashboard-get-started" class="top-panel-column">

					<h3><?php _e( 'Get Started' ); ?></h3>

					<div class="dashboard-panel-section-intro dashboard-panel-user">

						<figure>
							<a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
								<img class="avatar" src="<?php echo esc_url( get_avatar_url( $user_id ) ); ?>" alt="<?php echo $user_name; ?>" width="64" height="64" />
							</a>
							<figcaption class="screen-reader-text"><?php echo $user_name; ?></figcaption>
						</figure>

						<div>
							<p><?php _e( 'This site may display your profile details in posts that you author, depending on the theme and plugins used. You can edit yoyr details, set your images, and change your color schemes.' ); ?></p>

							<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Manage Your Profile' ); ?></a><br /><a href="<?php echo esc_url( admin_url( 'users.php' ) ); ?>"><?php _e( 'View all accounts' ); ?></a></p>
						</div>

					</div>
				</div>

				<div id="dashboard-next-steps" class="top-panel-column">

					<h3><?php _e( 'Next Steps' ); ?></h3>
					<ul>

					<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-edit-page"></span>' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-add-page"></span>' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

					<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-edit-page"></span>' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-add-page"></span>' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-write-blog"></span>' . __( 'Add a blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

					<?php else : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-write-blog"></span>' . __( 'Write your first blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-add-page"></span>' . __( 'Add an About page' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-setup-home"></span>' . __( 'Set up your homepage' ) . '</a>', current_user_can( 'customize' ) ? add_query_arg( 'autofocus[section]', 'static_front_page', admin_url( 'customize.php' ) ) : admin_url( 'options-reading.php' ) ); ?></li>
					<?php endif; ?>

					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-settings"></span>' . __( 'Manage your settings' ) . '</a>', admin_url( 'options-general.php' ) ); ?></li>
					<?php endif; ?>
					</ul>
				</div>

				<div id="dashboard-more-actions" class="top-panel-column top-panel-last">

					<h3><?php _e( 'More Actions' ); ?></h3>
					<ul>

					<?php if ( current_user_can( 'upload_files' ) ) : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-media"></span>' . __( 'Manage media' ) . '</a>', admin_url( 'upload.php' ) ); ?></li>
					<?php endif; ?>

					<?php if ( current_theme_supports( 'widgets' ) ) : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-widgets"></span>' . __( 'Manage widgets' ) . '</a>', admin_url( 'widgets.php' ) ); ?></li>
					<?php endif; ?>

					<?php if ( current_theme_supports( 'menus' ) ) : ?>
						<li><?php printf( '<a href="%s"><span class="dashboard-icon dashboard-menus"></span>' . __( 'Manage menus' ) . '</a>', admin_url( 'nav-menus.php' ) ); ?></li>
					<?php endif; ?>

						<li><?php printf( '<a href="%1s"><span class="dashboard-icon dashboard-learn-more"></span>' . __( 'Learn more' ) . '</a>', esc_url( $learn_link ) ); ?></li>
					</ul>
				</div>

			</div>

		</div><!-- #manage -->

		<div id='customize' class="app-tab-content top-panel-tab hide-if-no-js">

			<header>
				<h2><?php _e( 'Customize Your Site' ); ?></h2>
				<p class="description"><?php _e( 'Choose layout options, color schemes, and more.' ); ?></p>
			</header>

			<div class="top-panel-column-container">

				<div class="top-panel-column">

					<h3><?php _e( 'Active Theme' ); ?></h3>

					<div class="dashboard-panel-section-intro dashboard-panel-theme">

						<figure class="alignright">
							<a href="<?php echo esc_url( wp_customize_url() ); ?>">
								<img class="avatar" src="<?php echo esc_url( $customize_icon ); ?>" alt="<?php echo $theme_name; ?>" width="64" height="64" />
							</a>
							<figcaption class="screen-reader-text"><?php echo $theme_name; ?></figcaption>
						</figure>

						<div>
							<h4><?php echo $theme_name; ?></h4>

							<?php echo $theme_description; ?>
							<?php echo $theme_tags; ?>

							<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&return=' . site_url() ); ?>"><?php _e( 'Launch Customizer' ); ?></a></p>
						</div>

					</div>
				</div>

				<div class="top-panel-column">

					<h3><?php _e( 'Content Options' ); ?></h3>
					<ul>
						<li><a href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=title_tagline&return=' . site_url() ); ?>"><span class="dashboard-icon customize-icon-site"></span><?php _e( 'Site identity' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_customize_url() ); ?>"><span class="dashboard-icon customize-icon-layout"></span><?php _e( 'Page layouts' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_customize_url() ); ?>"><span class="dashboard-icon customize-icon-blog"></span><?php _e( 'Blog & archives content' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_customize_url() ); ?>"><span class="dashboard-icon customize-icon-bio"></span><?php _e( 'Author Biographies' ); ?></a></li>
					</ul>
				</div>

				<div class="top-panel-column top-panel-last">

					<h3><?php _e( 'Appearance Options' ); ?></h3>
					<ul>
						<li><a href="<?php echo esc_url( wp_customize_url() ); ?>"><span class="dashboard-icon customize-icon-schemes"></span><?php _e( 'Choose color schemes' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_customize_url() ); ?>"><span class="dashboard-icon customize-icon-headers"></span><?php _e( 'Set site & page headers' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=typography_options&return=' . site_url() ); ?>"><span class="dashboard-icon customize-icon-typography"></span><?php _e( 'Design your typography' ); ?></a></li>
						<li><a href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=background_image&return=' . site_url() ); ?>"><span class="dashboard-icon customize-icon-background"></span><?php _e( 'Site background' ); ?></a></li>
					</ul>
				</div>

			</div>
		</div>

		<div id='widgets' class="app-tab-content top-panel-tab"<?php echo $no_widgets; ?>>

			<header>
				<h2><?php _e( 'Widgets' ); ?></h2>
				<p class="description"><?php _e( 'The following widgets are registered by plugins or themes.' ); ?></p>
			</header>

			<div id="dashboard-widgets-wrap">

				<?php Dashboard :: dashboard(); ?>

			</div><!-- #dashboard-widgets-wrap -->

		</div><!-- #widgets -->

		<div id='drafts' class="app-tab-content top-panel-tab">

			<header>
				<h2><?php _e( 'Draft Posts' ); ?></h2>
				<p class="description"><?php _e( 'Create or follow up on draft post content.' ); ?></p>
			</header>

			<?php Dashboard :: dashboard_draft_posts(); ?>

		</div><!-- #drafts -->

	</div><!-- .app-tabs -->
</div><!-- .top-panel-inner -->