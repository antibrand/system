<?php
/**
 * Template functions
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Namespace specificity for theme functions & filters.
namespace AB_Theme\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body classes
 *
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0
 * @access public
 * @param  array $classes Classes for the body element.
 * @return array Returns the array of body classes.
 */
function body_classes( $classes ) {

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;

}
add_filter( 'body_class', 'AB_Theme\Includes\body_classes' );

/**
 * Pingback header
 *
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the link element in '<head>`.
 */
function pingback_header() {

	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}

}
add_action( 'wp_head', 'AB_Theme\Includes\pingback_header' );

/**
 * Main menu fallback
 *
 * @since  1.0.0
 * @access public
 * @return mixed
 */
function main_menu_fallback() {

	$customize_link = site_url();

?>
<div class="main-menu-wrap">
	<ul class="main-menu">
		<li><a href="<?php echo admin_url( '/customize.php?url=' . esc_url( $customize_link ) . '&autofocus[panel]=nav_menus' ); ?>"><?php esc_html_e( 'Set Up Menu', 'antibrand' ); ?></a></li>
		<li><a href="<?php echo admin_url( 'themes.php?page=theme-options' ) ?>"><?php esc_html_e( 'Theme Options', 'antibrand' ); ?></a></li>
		<li><a href="<?php echo admin_url( 'themes.php?page=theme-info' ) ?>"><?php esc_html_e( 'Theme Info', 'antibrand' ) ?></a></li>
	</ul>
</div>
<?php

}

/**
 * Theme toggle script
 *
 * Toggles a body class and toggles the
 * text of the toggle button.
 *
 * @since  1.0.0
 * @access public
 * @return mixed
 */
function theme_mode_script() {

	// Label before the icon.
	$label = esc_html__( 'Lights: ', 'antibrand' );

	// Bulb on icon for switching to dark theme.
	$bulb_on = '<svg id="bulb-on" width="100%" height="100%" viewBox="0 0 195 228" aria-labelledby="theme-toggle-title theme-toggle-desc"><path id="bulb-rays" d="M97.063,0c-3.663,0 -6.626,2.962 -6.626,6.625l0.001,17.688c-0.001,3.662 2.962,6.625 6.624,6.625c3.663,0 6.657,-2.963 6.657,-6.625l0,-17.688c0,-3.663 -2.994,-6.625 -6.656,-6.625Zm-62.5,25.125c-1.698,0 -3.424,0.642 -4.719,1.938c-2.59,2.589 -2.59,6.816 0,9.406l12.5,12.5c2.59,2.59 6.816,2.59 9.406,0c2.59,-2.59 2.59,-6.816 0,-9.407l-12.5,-12.499c-1.295,-1.296 -2.99,-1.938 -4.687,-1.938Zm125.031,0c-1.697,0 -3.392,0.642 -4.688,1.938l-12.5,12.5c-2.59,2.59 -2.59,6.816 0,9.406c2.59,2.59 6.785,2.59 9.375,0l12.5,-12.5c2.59,-2.59 2.59,-6.817 0,-9.406c-1.295,-1.296 -2.99,-1.938 -4.687,-1.938Zm-152.969,62.531c-3.663,0 -6.625,2.962 -6.625,6.625c0,3.663 2.962,6.656 6.625,6.656l17.688,0c3.662,0 6.625,-2.993 6.625,-6.656c0,-3.663 -2.963,-6.625 -6.625,-6.625l-17.688,0Zm163.219,0c-3.663,0 -6.625,2.962 -6.625,6.625c0,3.663 2.962,6.656 6.625,6.656l17.687,0c3.663,0 6.625,-2.993 6.625,-6.656c0,-3.663 -2.962,-6.625 -6.625,-6.625l-17.687,0Zm-131.594,50c-1.701,0 -3.392,0.674 -4.688,1.969l-12.499,12.5c-2.59,2.59 -2.59,6.785 0,9.375c1.295,1.295 2.989,1.937 4.687,1.937c1.697,0 3.392,-0.642 4.688,-1.937l12.531,-12.5c2.59,-2.59 2.59,-6.785 0,-9.375c-1.295,-1.295 -3.018,-1.969 -4.719,-1.969Zm116.156,0c-1.701,0 -3.392,0.674 -4.687,1.969c-2.59,2.59 -2.59,6.785 0,9.375l12.5,12.5c1.295,1.295 2.99,1.937 4.687,1.937c1.698,0 3.424,-0.642 4.719,-1.937c2.59,-2.59 2.59,-6.785 0,-9.375l-12.5,-12.5c-1.295,-1.295 -3.017,-1.969 -4.719,-1.969Z" /><path id="bulb" d="M97.07,39.112c-30.39,0 -59.687,23.64 -59.687,57.125c0,20.848 8.27,34.373 15.625,44.781c7.355,10.408 13.125,17.458 13.125,28.531l0,26.531c0,7.57 5.454,13.654 12.281,15.125c1.37,5.623 4.374,11.002 9.594,13.782c7.762,4.002 18.518,2.515 23.906,-4.625c2.04,-2.711 3.538,-5.912 4.156,-9.25c6.668,-1.603 11.969,-7.594 11.969,-15.032l0,-26.531c0,-11.073 5.77,-18.123 13.125,-28.531c7.355,-10.408 15.594,-23.933 15.594,-44.781c0,-33.485 -29.297,-57.125 -59.688,-57.125Zm0,13.25c23.531,0 46.438,18.19 46.438,43.875c0,17.538 -6.11,27.167 -13.125,37.093c-5.981,8.464 -13.09,17.373 -15.063,29.594l-36.468,0c-1.973,-12.221 -9.082,-21.13 -15.063,-29.594c-7.014,-9.926 -13.125,-19.555 -13.125,-37.093c0,-25.685 22.876,-43.875 46.406,-43.875Zm-10.437,6.625c-0.944,-0.034 -1.951,0.123 -2.969,0.375c-14.389,5.425 -25.156,18.085 -27.906,33.562c-0.597,3.437 1.938,7.153 5.375,7.75c3.437,0.597 7.154,-2.031 7.75,-5.469c1.926,-10.842 9.419,-19.71 19.469,-23.5c2.881,-1.071 4.755,-4.348 4.218,-7.375c-0.797,-3.712 -3.106,-5.241 -5.937,-5.343Zm-7.25,117.187l35.375,0l0,4.438l-35.375,0l0,-4.438Zm0,17.688l35.375,0l0,2.218c0,1.445 -1.119,2.188 -2.219,2.188l-30.937,0c-1.101,0 -2.219,-0.743 -2.219,-2.188l0,-2.218Zm11,17.687l13.906,0c-1.077,2.273 -2.938,4.155 -5.562,4.313c-3.164,0.583 -6.964,-0.697 -8.157,-3.907c-0.066,-0.132 -0.127,-0.272 -0.187,-0.406Z" />';

	$bulb_on .= sprintf(
		'<title id="%1s">%2s</title>',
		'theme-toggle-title',
		esc_html__( 'Turn lights off', 'antibrand' )
	);
	$bulb_on .= sprintf(
		'<desc id="%1s">%2s</desc>',
		'theme-toggle-desc',
		esc_html__( 'Switch the theme to dark mode', 'antibrand' )
	);
	$bulb_on .= sprintf(
		'<text class="screen-reader-text">%1s</text>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' )
	);

	$bulb_on .= '</svg>';

	// Button when lights are on.
	$button_on = sprintf(
		'<button class="theme-toggle-button" type="button" name="dark_light" title="%1s"><span>%2s</span> <span>%3s</span></button>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' ),
		$label,
		$bulb_on
	);

	// Bulb off icon for switching to light theme.
	$bulb_off = '<svg id="bulb-off" width="100%" height="100%" viewBox="0 0 195 228" aria-labelledby="theme-toggle-title theme-toggle-desc"><path id="bulb" d="M97.07,39.112c-30.39,0 -59.687,23.64 -59.687,57.125c0,20.848 8.27,34.373 15.625,44.781c7.355,10.408 13.125,17.458 13.125,28.531l0,26.531c0,7.57 5.454,13.654 12.281,15.125c1.37,5.623 4.374,11.002 9.594,13.782c7.762,4.002 18.518,2.515 23.906,-4.625c2.04,-2.711 3.538,-5.912 4.156,-9.25c6.668,-1.603 11.969,-7.594 11.969,-15.032l0,-26.531c0,-11.073 5.77,-18.123 13.125,-28.531c7.355,-10.408 15.594,-23.933 15.594,-44.781c0,-33.485 -29.297,-57.125 -59.688,-57.125Zm0,13.25c23.531,0 46.438,18.19 46.438,43.875c0,17.538 -6.11,27.167 -13.125,37.093c-5.981,8.464 -13.09,17.373 -15.063,29.594l-36.468,0c-1.973,-12.221 -9.082,-21.13 -15.063,-29.594c-7.014,-9.926 -13.125,-19.555 -13.125,-37.093c0,-25.685 22.876,-43.875 46.406,-43.875Zm-10.437,6.625c-0.944,-0.034 -1.951,0.123 -2.969,0.375c-14.389,5.425 -25.156,18.085 -27.906,33.562c-0.597,3.437 1.938,7.153 5.375,7.75c3.437,0.597 7.154,-2.031 7.75,-5.469c1.926,-10.842 9.419,-19.71 19.469,-23.5c2.881,-1.071 4.755,-4.348 4.218,-7.375c-0.797,-3.712 -3.106,-5.241 -5.937,-5.343Zm-7.25,117.187l35.375,0l0,4.438l-35.375,0l0,-4.438Zm0,17.688l35.375,0l0,2.218c0,1.445 -1.119,2.188 -2.219,2.188l-30.937,0c-1.101,0 -2.219,-0.743 -2.219,-2.188l0,-2.218Zm11,17.687l13.906,0c-1.077,2.273 -2.938,4.155 -5.562,4.313c-3.164,0.583 -6.964,-0.697 -8.157,-3.907c-0.066,-0.132 -0.127,-0.272 -0.187,-0.406Z" />';

	$bulb_off .= sprintf(
		'<title id="%1s">%2s</title>',
		'theme-toggle-title',
		esc_html__( 'Turn lights on', 'antibrand' )
	);
	$bulb_off .= sprintf(
		'<desc id="%1s">%2s</desc>',
		'theme-toggle-desc',
		esc_html__( 'Switch the theme to light mode', 'antibrand' )
	);
	$bulb_off .= sprintf(
		'<text class="screen-reader-text">%1s</text>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' )
	);

	$bulb_off .= '</svg>';

	// Button when lights are on.
	$button_off = sprintf(
		'<button class="theme-toggle-button" type="button" name="dark_light" title="%1s"><span>%2s</span> <span>%3s</span></button>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' ),
		$label,
		$bulb_off
	);

	?>
<script>jQuery(document).ready(function(e){var t=e('#theme-toggle');localStorage.theme?(e('body').addClass(localStorage.theme),e(t).html(localStorage.html)):(e('body').addClass('light-mode'),e(t).html('<?php echo $button_on; ?>')),e(t).click(function(){e('body').hasClass('light-mode')?(e('body').removeClass('light-mode').addClass('dark-mode'),e(t).html('<?php echo $button_off; ?>'),localStorage.theme='dark-mode',localStorage.html='<?php echo $button_off; ?>'):(e('body').removeClass('dark-mode').addClass('light-mode'),e(t).html('<?php echo $button_on; ?>'),localStorage.theme='light-mode',localStorage.html='<?php echo $button_on; ?>')})});</script>
<?php

}