<?php
/**
 * Dashboard intro panel: Administrator
 *
 * @package App_Package
 * @subpackage Administration
 */

// App version.
$version = get_bloginfo( 'version' );

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
if ( defined( 'APP_WEBSITE' ) ) {
	$learn_link = esc_url( APP_WEBSITE );
} else {
	$learn_link = esc_url( 'https://antibrand.dev' );
}

$learn_link = apply_filters( 'dashboard_learn_link', $learn_link );

// Get the current user data for the greeting.
$current_user = wp_get_current_user();
$user_id      = get_current_user_id();
$user_name    = $current_user->display_name;
$avatar       = get_avatar(
	$user_id,
	64,
	'',
	$current_user->display_name,
	[
		'class'         => 'intro-panel-avatar alignnone',
		'force_display' => true
		]
);

// Get theme data.
$get_theme  = wp_get_theme();
$theme_name = $get_theme->get( 'Name' );
$theme_desc = $get_theme->get( 'Description' );

// Theme description.
if ( $theme_desc ) {
	$theme_description = sprintf(
		'<p class="description">%1s</p>',
		$theme_desc
	);
} else {
	$theme_description = '';
}

?>
<div class="top-panel">

	<?php echo $panel_heading; ?>
	<?php echo $panel_description; ?>

	<div class='app-tabs top-panel-tabbed-content' data-toggle="app-tabs" data-tab_animation="true" data-tab_anispeed="250" data-tab_mouseevent="click">

		<ul class='app-tabs-list app-tabs-horizontal hide-if-no-js'>
			<li class="app-tab"><a href="#manage"><?php _e( 'Manage' ); ?></a>
			<li class="app-tab"><a href="#customize"><?php _e( 'Customize' ); ?></a>
		</ul>

		<div id='manage' class="app-tab-content top-panel-tab">

			<h2><?php _e( 'Manage Your Site' ); ?></h2>

			<p class="description"><?php _e( 'This dashboard provides handy links to the various features of the website management system.' ); ?></p>

			<div class="top-panel-column-container">

				<div id="dashboard-get-started" class="top-panel-column">

					<h3><?php _e( 'Get Started' ); ?></h3>

					<div class="dashboard-panel-section-intro dashboard-panel-user-greeting">

						<figure>
							<a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
								<?php echo $avatar; ?>
							</a>
							<figcaption class="screen-reader-text"><?php echo $user_name; ?></figcaption>
						</figure>

						<div>
							<?php echo sprintf(
								'<h4>%1s %2s.</h4>',
								esc_html__( 'Hello,' ),
								$user_name
							); ?>
							<p><?php _e( 'This site may display your profile in posts that you author, and it offers user-defined color schemes.' ); ?></p>
							<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Manage Your Profile' ); ?></a></p>
						</div>

					</div>
				</div>

				<div id="dashboard-next-steps" class="top-panel-column">

					<h3><?php _e( 'Next Steps' ); ?></h3>
					<ul>

					<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

						<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

					<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

						<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

						<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Add a blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

					<?php else : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

						<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add an About page' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

						<li><?php printf( '<a href="%s" class="welcome-icon welcome-setup-home">' . __( 'Set up your homepage' ) . '</a>', current_user_can( 'customize' ) ? add_query_arg( 'autofocus[section]', 'static_front_page', admin_url( 'customize.php' ) ) : admin_url( 'options-reading.php' ) ); ?></li>
					<?php endif; ?>

					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-settings">' . __( 'Manage your settings' ) . '</a>', admin_url( 'options-general.php' ) ); ?></li>
					<?php endif; ?>
					</ul>
				</div>

				<div id="dashboard-more-actions" class="top-panel-column top-panel-last">

					<h3><?php _e( 'More Actions' ); ?></h3>
					<ul>

					<?php if ( current_user_can( 'upload_files' ) ) : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-media">' . __( 'Manage media' ) . '</a>', admin_url( 'upload.php' ) ); ?></li>
					<?php endif; ?>

					<?php if ( current_theme_supports( 'widgets' ) ) : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-widgets">' . __( 'Manage widgets' ) . '</a>', admin_url( 'widgets.php' ) ); ?></li>
					<?php endif; ?>

					<?php if ( current_theme_supports( 'menus' ) ) : ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-menus">' . __( 'Manage menus' ) . '</a>', admin_url( 'nav-menus.php' ) ); ?></li>
					<?php endif; ?>

						<li><?php printf( '<a href="%s" target="_blank" rel="nofollow" class="welcome-icon welcome-learn-more">' . __( 'Learn more' ) . '</a>', esc_url( $learn_link ) ); ?></li>
					</ul>
				</div>

			</div>

		</div><!-- #manage -->

		<div id='customize' class="app-tab-content top-panel-tab hide-if-no-js">

			<h2><?php _e( 'Customize Your Site' ); ?></h2>

			<p class="description"><?php _e( 'Choose layout options, color schemes, and more.' ); ?></p>

			<div class="top-panel-column-container">

				<div class="top-panel-column">

					<h3><?php _e( 'More Options' ); ?></h3>

					<div class="dashboard-panel-section-intro dashboard-panel-theme-greeting">

						<figure>
							<a href="<?php echo esc_url( wp_customize_url() ); ?>">
								<img class="avatar" src="<?php echo esc_url( app_assets_url( 'images/app-icon.jpg' ) ); ?>" alt="<?php echo $theme_name; ?>" width="64" height="64" />
							</a>
							<figcaption class="screen-reader-text"><?php echo $theme_name; ?></figcaption>
						</figure>

						<div>
							<h4><?php echo __( 'Active theme: ' ) . $theme_name; ?></h4>
							<?php echo $theme_description; ?>
							<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&return=' . site_url() ); ?>"><?php _e( 'Website Customizer' ); ?></a></p>
						</div>

					</div>
				</div>

				<div class="top-panel-column">

					<h3><?php _e( 'Content Options' ); ?></h3>
					<ul>
						<li><a class="welcome-icon customize-icon-site" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=title_tagline&return=' . site_url() ); ?>"><?php _e( 'Site identity' ); ?></a></li>
						<li><a class="welcome-icon customize-icon-layout" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Page layouts' ); ?></a></li>
						<li><a class="welcome-icon customize-icon-blog" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Blog & archives content' ); ?></a></li>
						<li><a class="welcome-icon customize-icon-bio" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Author Biographies' ); ?></a></li>
					</ul>
				</div>

				<div class="top-panel-column top-panel-last">

					<h3><?php _e( 'Appearance Options' ); ?></h3>
					<ul>
						<li><a class="welcome-icon customize-icon-schemes" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Choose color schemes' ); ?></a></li>
						<li><a class="welcome-icon customize-icon-headers" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Set site & page headers' ); ?></a></li>
						<li><a class="welcome-icon customize-icon-typography" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=typography_options&return=' . site_url() ); ?>"><?php _e( 'Design your typography' ); ?></a></li>
						<li><a class="welcome-icon customize-icon-background" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=background_image&return=' . site_url() ); ?>"><?php _e( 'Site background' ); ?></a></li>
					</ul>
				</div>

			</div>
		</div><!-- #customize -->
	</div><!-- .app-tabs -->
</div><!-- .top-panel -->