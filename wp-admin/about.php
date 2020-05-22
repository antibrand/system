<?php
/**
 * About This Version administration panel.
 *
 * @package App_Package
 * @subpackage Administration
 */

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

wp_enqueue_script( 'app-tabs' );

$title   = __( 'About' );
$version = get_bloginfo( 'version' );

include( ABSPATH . 'wp-admin/admin-header.php' );
?>
<div class="wrap">

	<h1>
	<?php echo sprintf(
		'%1s %2s',
		APP_NAME,
		$version
	); ?>
	</h1>
	<p class="description"><?php _e( 'Tell folks about your website management system.' ); ?></p>

	<div class='app-tabs about-page-tabbed-content' data-tabbed="tabbed" data-tabevent="click">

		<ul class='app-tabs-list app-tabs-horizontal hide-if-no-js'>
			<li class="app-tab active"><a href="#intro"><?php _e( 'Introduction' ); ?></a>
			<li class="app-tab"><a href="#features"><?php _e( 'Features' ); ?></a>
			<li class="app-tab"><a href="#customize"><?php _e( 'Customize' ); ?></a>
			<li class="app-tab"><a href="#extend"><?php _e( 'Extend' ); ?></a>
			<li class="app-tab"><a href="#privacy"><?php _e( 'Privacy' ); ?></a>
		</ul>

		<div id='intro' class="app-tab-content about-page-tab-content">

			<header>
				<h2><?php _e( 'Introduction' ); ?></h2>
				<p class="description"><?php _e( 'Put section description here.' ); ?></p>
			</header>

			<p><?php _e( 'Put section content here.' ); ?></p>

		</div>

		<div id='features' class="app-tab-content about-page-tab-content">

			<header>
				<h2><?php _e( 'Features' ); ?></h2>
				<p class="description"><?php _e( 'Put section description here.' ); ?></p>
			</header>

			<p><?php _e( 'Put section content here.' ); ?></p>

		</div>

		<div id='customize' class="app-tab-content about-page-tab-content">

			<header>
				<h2><?php _e( 'Customize' ); ?></h2>
				<p class="description"><?php _e( 'Put section description here.' ); ?></p>
			</header>

			<p><?php _e( 'Put section content here.' ); ?></p>

		</div>

		<div id='extend' class="app-tab-content about-page-tab-content">

			<header>
				<h2><?php _e( 'Extend' ); ?></h2>
				<p class="description"><?php _e( 'Put section description here.' ); ?></p>
			</header>

			<p><?php _e( 'Put section content here.' ); ?></p>

		</div>

		<div id='privacy' class="app-tab-content about-page-tab-content">

			<header>
				<h2><?php _e( 'Privacy' ); ?></h2>
				<p class="description"><?php _e( 'Put section description here.' ); ?></p>
			</header>

			<p><?php _e( 'Put section content here.' ); ?></p>

		</div>

	</div>
</div>
<?php

include( ABSPATH . 'wp-admin/admin-footer.php' );