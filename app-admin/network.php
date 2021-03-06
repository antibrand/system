<?php
/**
 * Network installation administration panel.
 *
 * A multi-step process allowing the user to enable a network of sites.
 *
 * @package App_Package
 * @subpackage Administration
 *
 * @since Previous 3.0.0
 */

define( 'APP_INSTALLING_NETWORK', true );

// Get the system environment constants from the root directory.
require_once( dirname( dirname( __FILE__ ) ) . '/app-environment.php' );

// Load the administration environment.
require_once( APP_INC_PATH . '/backend/app-admin.php' );

if ( ! current_user_can( 'setup_network' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

if ( is_network() ) {

	if ( ! is_network_admin() ) {
		wp_redirect( network_admin_url( 'setup.php' ) );
		exit;
	}

	if ( ! defined( 'MULTISITE' ) ) {
		wp_die( __( 'The Network creation panel is not for MU networks.' ) );
	}
}

require_once( APP_INC_PATH . '/backend/network.php' );

// We need to create references to ms global tables to enable network.
foreach ( $wpdb->tables( 'ms_global' ) as $table => $prefixed_table ) {
	$wpdb->$table = $prefixed_table;
}

if ( ! network_domain_check() && ( ! defined( 'APP_ALLOW_NETWORK' ) || ! APP_ALLOW_NETWORK ) ) {
	wp_die(
		printf(
			__( 'You must define the %1$s constant as true in your %2$s file to allow creation of a Network.' ),
			'<code>APP_ALLOW_NETWORK</code>',
			'<code>app-config.php</code>'
		)
	);
}

if ( is_network_admin() ) {
	$title       = __( 'Network Setup' );
	$parent_file = 'settings.php';
} else {
	$title       = __( 'Create a Network of Sites' );
	$parent_file = 'tools.php';
}

$network_help = '<p>' . __( 'This screen allows you to configure a network as having subdomains (<code>site1.example.com</code>) or subdirectories (<code>example.com/site1</code>). Subdomains require wildcard subdomains to be enabled in Apache and DNS records, if your host allows it.' ) . '</p>' .
	'<p>' . __( 'Choose subdomains or subdirectories; this can only be switched afterwards by reconfiguring your installation. Fill out the network details, and click Install. If this does not work, you may have to add a wildcard DNS record (for subdomains) or change to another setting in Permalinks (for subdirectories).' ) . '</p>' .
	'<p>' . __( 'The next screen for Network Setup will give you individually-generated lines of code to add to the configuration and .htaccess files. Make sure the settings of your FTP client make files starting with a dot visible, so that you can find .htaccess; you may have to create this file if it really is not there. Make backup copies of those two files.' ) . '</p>' .
	'<p>' . __( 'Add the designated lines of code to the configuration file (just before <code>/*...stop editing...*/</code>) and <code>.htaccess</code> (replacing the existing rules).' ) . '</p>' .
	'<p>' . __( 'Once you add this code and refresh your browser, network should be enabled. This screen, now in the Network Admin navigation menu, will keep an archive of the added code. You can toggle between Network Admin and Site Admin by clicking on the Network Admin or an individual site name under the Network Sites dropdown in the Toolbar.' ) . '</p>' .
	'<p>' . __( 'The choice of subdirectory sites is disabled if this setup is more than a month old because of permalink problems with &#8220;/blog/&#8221; from the main site. This disabling will be addressed in a future version.' ) . '</p>';

get_current_screen()->add_help_tab( [
	'id'      => 'network',
	'title'   => __( 'Network' ),
	'content' => $network_help,
] );

get_current_screen()->set_help_sidebar( '' );

// Get the admin page header.
include( APP_VIEWS_PATH . '/backend/header/admin-header.php' );

?>
<div class="wrap">

	<h1><?php echo esc_html( $title ); ?></h1>

	<?php
	if ( $_POST ) {

		check_admin_referer( 'install-network-1' );

		require_once( APP_INC_PATH . '/backend/upgrade.php' );

		// Create network tables.
		install_network();
		$base              = parse_url( trailingslashit( get_option( 'home' ) ), PHP_URL_PATH );
		$subdomain_install = allow_subdomain_install() ? ! empty( $_POST['subdomain_install'] ) : false;

		if ( ! network_domain_check() ) {
			$result = populate_network( 1, get_clean_basedomain(), sanitize_email( $_POST['email'] ), wp_unslash( $_POST['sitename'] ), $base, $subdomain_install );

			if ( is_wp_error( $result ) ) {

				if ( 1 == count( $result->get_error_codes() ) && 'no_wildcard_dns' == $result->get_error_code() ) {
					network_step2( $result );
				} else {
					network_step1( $result );
				}
			} else {
				network_step2();
			}
		} else {
			network_step2();
		}
	} elseif ( is_network() || network_domain_check() ) {
		network_step2();
	} else {
		network_step1();
	}
	?>
</div>

<?php

// Get the admin page footer.
include( APP_VIEWS_PATH . '/backend/footer/admin-footer.php' );
