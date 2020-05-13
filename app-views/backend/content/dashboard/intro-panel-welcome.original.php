<?php
/**
 * Dashboard intro panel: Original system administrator
 *
 * Reworked from the WP 4.9.8 "Welcome" panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// App version.
$version = get_bloginfo( 'version' );

/**
 * Welcome panel description
 *
 * Uses the white label tagline if available.
 */
if ( defined( 'APP_TAGLINE' ) ) {
	$description = APP_TAGLINE;
} else {
	$description = __( 'Following are some links to help manage your website:' );
}

?>
<div class="top-panel-content">
	<h2>
	<?php echo sprintf(
		'%1s %2s %3s',
		__( 'Welcome to' ),
		APP_NAME,
		$version
	); ?>
	</h2>
	<p class="description welcome-description"><?php echo $description; ?></p>
	<div class="top-panel-column-container">
		<div class="top-panel-column">
			<h3><?php _e( 'Get Started' ); ?></h3>
			<ul>
				<li>
					<a href="<?php echo admin_url( 'about.php' ); ?>">
						<?php echo __( 'The features of ' ) . APP_NAME; ?>
					</a>
				</li>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<li>
					<a href="<?php echo admin_url( 'options-general.php' ); ?>">
						<?php _e( 'Manage website settings' ); ?>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( current_user_can( 'customize' ) ) : ?>
				<li>
					<a class="load-customize hide-if-no-customize" href="<?php echo wp_customize_url(); ?>">
						<?php _e( 'Customize your website' ); ?>
					</a>
				</li>
			<?php endif; ?>
			</ul>
		</div>
		<div class="top-panel-column">
			<h3><?php _e( 'Next Steps' ); ?></h3>
			<ul>
			<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
				<li><?php printf( '<a href="%s">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
				<li><?php printf( '<a href="%s">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
			<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
				<li><?php printf( '<a href="%s">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
				<li><?php printf( '<a href="%s">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
				<li><?php printf( '<a href="%s">' . __( 'Add a blog or news post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>
			<?php else : ?>
				<li><?php printf( '<a href="%s">' . __( 'Add a blog or news post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>
				<li><?php printf( '<a href="%s">' . __( 'Add an informational page' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
			<?php endif; ?>
				<li><?php printf( '<a href="%s">' . __( 'Upload photos & media' ) . '</a>', admin_url( 'upload.php' ) ); ?></li>
			</ul>
		</div>
		<div class="top-panel-column top-panel-last">
			<h3><?php _e( 'More Actions' ); ?></h3>
			<ul>
			<?php if ( current_theme_supports( 'widgets' ) || current_theme_supports( 'menus' ) ) : ?>
				<li><?php
					if ( current_theme_supports( 'widgets' ) && current_theme_supports( 'menus' ) ) {
						printf( __( 'Manage <a href="%1$s">widgets</a> or <a href="%2$s">menus</a>' ),
							admin_url( 'widgets.php' ), admin_url( 'nav-menus.php' ) );
					} elseif ( current_theme_supports( 'widgets' ) ) {
						echo '<a href="' . admin_url( 'widgets.php' ) . '">' . __( 'Manage widgets' ) . '</a>';
					} else {
						echo '<a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Manage menus' ) . '</a>';
					}
				?></li>
			<?php endif; ?>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<li><?php printf( '<a href="%s">' . __( 'Manage discussions' ) . '</a>', admin_url( 'options-discussion.php' ) ); ?></li>
			<?php endif; ?>
			<?php if ( current_user_can( 'add_users' ) ) : ?>
				<li><?php printf( '<a href="%s">' . __( 'Manage website users' ) . '</a>', admin_url( 'users.php' ) ); ?></li>
			<?php endif; ?>
			</ul>
		</div>
	</div>
</div>