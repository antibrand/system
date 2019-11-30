<?php
/**
 * Theme Customizer
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ab_theme_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', [
			'selector'        => '.site-title a',
			'render_callback' => 'ab_theme_customize_partial_blogname',
		] );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', [
			'selector'        => '.site-description',
			'render_callback' => 'ab_theme_customize_partial_blogdescription',
		] );
	}

}
add_action( 'customize_register', 'ab_theme_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function ab_theme_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function ab_theme_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ab_theme_customize_preview_js() {
	wp_enqueue_script( 'ub-customizer', get_template_directory_uri() . '/assets/js/customizer.min.js', [ 'customize-preview' ], '20151215', true );
}
add_action( 'customize_preview_init', 'ab_theme_customize_preview_js' );