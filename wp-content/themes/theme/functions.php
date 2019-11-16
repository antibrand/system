<?php
/**
 * Theme functions
 *
 * A basic starter theme for your website management system.
 *
 * @package    system
 * @subpackage AB_Theme
 * @link       https://github.com/antibrand/theme
 * @since      1.0.0
 */

// Namespace specificity for theme functions & filters.
namespace AB_Theme\Functions;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get plugins path to check for active plugins.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Theme functions class
 *
 * @since  1.0.0
 * @access public
 */
final class Functions {

	/**
	 * Return the instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {

			$instance = new self;

			// Theme activation & deactivation.
			$instance->activation();

			// Theme dependencies.
			$instance->dependencies();

		}

		return $instance;
	}

	/**
	 * Theme activation & deactivation functions
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function activation() {

		// Require theme activation functions.
		require_once get_theme_file_path( '/includes/class-activate.php' );

		// Require theme deactivation functions.
		require_once get_theme_file_path( '/includes/class-deactivate.php' );

	 }

	/**
	 * Constructor magic method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Swap html 'no-js' class with 'js'.
		add_action( 'wp_head', [ $this, 'js_detect' ], 0 );

		// Theme setup.
		add_action( 'after_setup_theme', [ $this, 'setup' ] );

		// Register widgets.
        add_action( 'widgets_init', [ $this, 'widgets' ] );

		// Disable custom colors in the editor.
		add_action( 'after_setup_theme', [ $this, 'editor_custom_color' ] );

		// Remove unpopular meta tags.
		add_action( 'init', [ $this, 'head_cleanup' ] );

		// Frontend scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		// Admin scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

		// Frontend styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_styles' ] );

		/**
		 * Admin styles.
		 *
		 * Call late to override plugin styles.
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ], 99 );

		// Toolbar styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'toolbar_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'toolbar_styles' ], 99 );

		// Login styles.
		add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );

		// jQuery UI fallback for HTML5 Contact Form 7 form fields.
		add_filter( 'wpcf7_support_html5_fallback', '__return_true' );

		// Remove WooCommerce styles.
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );

		// Theme options page.
		add_action( 'admin_menu', [ $this, 'theme_options' ] );

		// Theme info page.
		add_action( 'admin_menu', [ $this, 'theme_info' ] );

		// User color scheme classes.
		add_filter( 'body_class', [ $this, 'color_scheme_classes' ] );

	}

	/**
	 * JS Replace
	 *
	 * Replaces 'no-js' class with 'js' in the <html> element
	 * when JavaScript is detected.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function js_detect() {

		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";

	}

	/**
	 * Theme setup
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function setup() {

		/**
		 * Load domain for translation
		 *
		 * @since 1.0.0
		 */
		load_theme_textdomain( 'unbranded' );

		/**
		 * Add theme support
		 *
		 * @since 1.0.0
		 */

		// Browser title tag support.
		add_theme_support( 'title-tag' );

		// Background color & image support.
		add_theme_support( 'custom-background' );

		// RSS feed links support.
		add_theme_support( 'automatic-feed-links' );

		// HTML 5 tags support.
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gscreenery',
			'caption'
		 ] );

		/**
		 * Block editor colors
		 *
		 * Match the following HEX codes with SASS color variables.
		 * @see assets/css/_variables.scss
		 *
		 * @since 1.0.0
		 */
		$color_args = [
			[
				'name'  => __( 'Text', 'unbranded' ),
				'slug'  => 'ub-text',
				'color' => '#333333',
			],
			[
				'name'  => __( 'Light Gray', 'unbranded' ),
				'slug'  => 'ub-light-gray',
				'color' => '#888888',
			],
			[
				'name'  => __( 'Pale Gray', 'unbranded' ),
				'slug'  => 'ub-pale-gray',
				'color' => '#cccccc',
			],
			[
				'name'  => __( 'White', 'unbranded' ),
				'slug'  => 'ub-white',
				'color' => '#ffffff',
			],
			[
				'name'  => __( 'Error Red', 'unbranded' ),
				'slug'  => 'ub-error',
				'color' => '#dc3232',
			],
			[
				'name'  => __( 'Warning Yellow', 'unbranded' ),
				'slug'  => 'ub-warning',
				'color' => '#ffb900',
			],
			[
				'name'  => __( 'Success Green', 'unbranded' ),
				'slug'  => 'ub-success',
				'color' => '#46b450',
			]
		];

		// Apply a filter to editor arguments.
		$colors = apply_filters( 'ub_editor_colors', $color_args );

		// Add theme color support.
		add_theme_support( 'editor-color-palette', $colors );

		/**
		 * Set default image sizes
		 *
		 * Define the dimensions and the crop options.
		 *
		 * @since 1.0.0
		 */
		// Featured image support.
		add_theme_support( 'post-thumbnails' );

		// Thumbnail size.
		update_option( 'thumbnail_size_w', 160 );
		update_option( 'thumbnail_size_h', 160 );
		update_option( 'thumbnail_crop', 1 );

		// Medium size.
		update_option( 'medium_size_w', 320 );
		update_option( 'medium_size_h', 240 );
		update_option( 'medium_crop', 1 );

		// Medium-large size.
		update_option( 'medium_large_size_w', 480 );
		update_option( 'medium_large_size_h', 360 );

		// Large size.
		update_option( 'large_size_w', 640 );
		update_option( 'large_size_h', 480 );
		update_option( 'large_crop', 1 );

		// Set the post thumbnail size, 16:9 HD Video.
		set_post_thumbnail_size( 1280, 720, [ 'center', 'center' ] );

		// Add wide image support for the block editor.
		add_theme_support( 'align-wide' );

		/**
		 * Add image sizes
		 *
		 * Three sizes per aspect ratio so that we
		 * will use srcset for responsive images.
		 *
		 * @since 1.0.0
		 */

		 // 1:1 square.
		add_image_size( __( 'large-thumbnail', 'beeline-theme' ), 240, 240, true );
		add_image_size( __( 'xlarge-thumbnail', 'beeline-theme' ), 320, 320, true );

		// 16:9 HD Video.
		add_image_size( __( 'large-video', 'unbranded' ), 1280, 720, true );
		add_image_size( __( 'medium-video', 'unbranded' ), 960, 540, true );
		add_image_size( __( 'small-video', 'unbranded' ), 640, 360, true );

		// 21:9 Cinemascope.
		add_image_size( __( 'large-banner', 'unbranded' ), 1280, 549, true );
		add_image_size( __( 'medium-banner', 'unbranded' ), 960, 411, true );
		add_image_size( __( 'small-banner', 'unbranded' ), 640, 274, true );

		/**
		 * Custom header
		 */
		add_theme_support( 'custom-header', apply_filters( 'ub_custom_header_args', [
			'width'              => 2048,
			'height'             => 878,
			'flex-height'        => true,
			'video'              => false,
			'wp-head-callback'   => [ $this, 'header_style' ]
		] ) );

		register_default_headers( [
			'default-image' => [
				'url'           => '%s/assets/images/default-header.jpg',
				'thumbnail_url' => '%s/assets/images/default-header.jpg',
				'description'   => __( 'Default Header Image', 'unbranded' ),
			],
		] );

		/**
		 * Custom logo
		 *
		 * @since 1.0.0
		 */

		// Logo arguments.
		$logo_args = [
			'width'       => 180,
			'height'      => 180,
			'flex-width'  => true,
			'flex-height' => true
		];

		// Apply a filter to logo arguments.
		$logo = apply_filters( 'ub_header_image', $logo_args );

		// Add logo support.
		add_theme_support( 'custom-logo', $logo );

		 /**
		 * Set content width.
		 *
		 * @since 1.0.0
		 */
		$ub_content_width = apply_filters( 'ub_content_width', 1280 );

		if ( ! isset( $content_width ) ) {
			$content_width = $ub_content_width;
		}

		// Embed sizes.
		update_option( 'embed_size_w', 1280 );
		update_option( 'embed_size_h', 720 );

		/**
		 * Register theme menus.
		 *
		 * @since  1.0.0
		 */
		register_nav_menus( [
			'main'   => __( 'Main Menu', 'unbranded' ),
			'footer' => __( 'Footer Menu', 'unbranded' ),
			'social' => __( 'Social Menu', 'unbranded' )
		] );

		/**
		 * Add stylesheet for the content editor.
		 *
		 * @since 1.0.0
		 */
		add_editor_style( '/assets/css/editor.min.css', [ 'ub-admin' ], '', 'screen' );

	}

	/**
	 * Style the header image and text
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns an HTML style block.
	 *
	 */
	public function header_style() {

		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles.
		if ( ! display_header_text() ) {
			$style = sprintf(
				'<style type="text/css">%1s</style>',
				'.site-title,
				 .site-title a,
				 .site-description {
					position: absolute;
					clip: rect(1px, 1px, 1px, 1px);
				}'
			);
		} else {
			$style = sprintf(
				'<style type="text/css">%1s</style>',
				'.site-title,
				 .site-title a,
				 .site-description {
					color: #' . esc_attr( $header_text_color ) . ';
				}'
			);
		}

		echo $style;
	}

	/**
	 * Register widgets
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function widgets() {

		// Add customizer widget refresh support.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Register sidebar widget area.
		register_sidebar( [
			'name'          => esc_html__( 'Sidebar', 'unbranded' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'unbranded' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		] );

	}

	/**
	 * Theme support for disabling custom colors in the editor
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool Returns true for the color picker.
	 */
	public function editor_custom_color() {

		$disable = add_theme_support( 'disable-custom-colors', [] );

		// Apply a filter for conditionally disabling the picker.
		$custom_colors = apply_filters( 'ub_editor_custom_colors', '__return_false' );

		return $custom_colors;

	}

	/**
	 * Clean up meta tags from the <head>
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function head_cleanup() {

		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
	}

	/**
	 * Frontend scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_scripts() {

		// Enqueue jQuery.
		wp_enqueue_script( 'jquery' );

		// Navigation toggle and dropdown.
		wp_enqueue_script( 'test-navigation', get_theme_file_uri( '/assets/js/navigation.min.js' ), [], null, true );

		// Skip link focus, for accessibility.
		wp_enqueue_script( 'unbranded-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.min.js' ), [], null, true );

		// FitVids for responsive video embeds.
		wp_enqueue_script( 'bs-fitvids', get_theme_file_uri( '/assets/js/jquery.fitvids.min.js' ), [ 'jquery' ], null, true );
		wp_add_inline_script( 'bs-fitvids', 'jQuery(document).ready(function($){ $( ".entry-content" ).fitVids(); });', true );

		// Comments scripts.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

	/**
	 * Admin scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_scripts() {}

	/**
	 * Frontend styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_styles() {

		// Google fonts.
		// wp_enqueue_style( 'unbranded-google-fonts', 'add-url-here', [], '', 'screen' );

		/**
		 * Theme sylesheet
		 *
		 * This enqueues the minified stylesheet compiled from SASS (.scss) files.
		 * The main stylesheet, in the root directory, only contains the theme header but
		 * it is necessary for theme activation. DO NOT delete the main stylesheet!
		 */
		wp_enqueue_style( 'unbranded', get_theme_file_uri( '/assets/css/style.min.css' ), [], '' );

		// Add right-to-left styles if needed.
		if ( is_rtl() ) {
			wp_enqueue_style( 'bs-blocks', get_theme_file_uri( '/assets/css/rtl.min.css' ), [ 'unbranded' ], '' );
		}

		// Block styles.
		if ( function_exists( 'has_blocks' ) ) {
			if ( has_blocks() ) {
				wp_enqueue_style( 'bs-blocks', get_theme_file_uri( '/assets/css/blocks.min.css' ), [ 'unbranded' ], '' );
			}
		}

		// Print styles.
		wp_enqueue_style( 'bs-print', get_theme_file_uri( '/assets/css/print.min.css' ), [], '', 'print' );

	}

	/**
	 * Admin styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_styles() {

		wp_enqueue_style( 'bs-admin', get_theme_file_uri( '/assets/css/admin.min.css' ), [], '' );

	}

	/**
	 * Toolbar styles
	 *
	 * Enqueues if user is logged in and admin bar is showing.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function toolbar_styles() {

		if ( is_user_logged_in() && is_admin_bar_showing() ) {
			wp_enqueue_style( 'bs-toolbar', get_theme_file_uri( '/assets/css/toolbar.min.css' ), [], '' );
		}

	}

	/**
	 * Login styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function login_styles() {

		wp_enqueue_style( 'custom-login', get_theme_file_uri( '/assets/css/login.css' ), [], '', 'screen' );

	}

	/**
	 * Theme dependencies
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function dependencies() {

		require get_theme_file_path( '/includes/template-functions.php' );
		require get_theme_file_path( '/includes/template-tags.php' );
		require get_theme_file_path( '/includes/customizer.php' );

	}

	/**
	 * Theme options page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function theme_options() {

		// Add a submenu page under Themes.
		$this->help_theme_options = add_submenu_page(
			'themes.php',
			__( 'Theme Options', 'unbranded' ),
			__( 'Theme Options', 'unbranded' ),
			'manage_options',
			'unbranded-options',
			[ $this, 'theme_options_output' ]
		);

		// Add sample help tab.
		add_action( 'load-' . $this->help_theme_options, [ $this, 'help_theme_options' ] );

	}

	/**
     * Get output of the theme options page
     *
     * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function theme_options_output() {

        require get_parent_theme_file_path( '/includes/theme-options-page.php' );

	}

	/**
     * Add tabs to the about page contextual help section
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function help_theme_options() {

		// Add to the about page.
		$screen = get_current_screen();
		if ( $screen->id != $this->help_theme_options ) {
			return;
		}

		// More information tab.
		$screen->add_help_tab( [
			'id'       => 'help_theme_options_info',
			'title'    => __( 'More Information', 'unbranded' ),
			'content'  => null,
			'callback' => [ $this, 'help_theme_options_info' ]
		] );

        // Add a help sidebar.
		$screen->set_help_sidebar(
			$this->help_theme_options_sidebar()
		);

	}

	/**
     * Get convert plugin help tab content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
     */
	public function help_theme_options_info() {

		include_once get_theme_file_path( 'includes/partials/help-theme-options-info.php' );

    }

    /**
     * The about page contextual tab sidebar content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the HTML of the sidebar content.
     */
    public function help_theme_options_sidebar() {

        $html  = sprintf( '<h4>%1s</h4>', __( 'Author Credits', 'unbranded' ) );
        $html .= sprintf(
            '<p>%1s %2s.</p>',
            __( 'This theme was created by', 'unbranded' ),
            'Your Name'
        );
        $html .= sprintf(
            '<p>%1s <br /><a href="%2s" target="_blank">%3s</a> <br />%4s</p>',
            __( 'Visit', 'unbranded' ),
            'https://example.com/',
            'Example Site',
            __( 'for more details.', 'unbranded' )
        );
        $html .= sprintf(
            '<p>%1s</p>',
            __( 'Change this sidebar to give yourself credit for the hard work you did customizing this theme.', 'unbranded' )
         );

		return $html;

	}

	/**
	 * Theme info page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function theme_info() {

		// Add a submenu page under Themes.
		add_submenu_page(
			'themes.php',
			__( 'Theme Info', 'unbranded' ),
			__( 'Theme Info', 'unbranded' ),
			'manage_options',
			'unbranded-info',
			[ $this, 'theme_info_output' ]
		);

	}

	/**
     * Get output of the theme info page
     *
     * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function theme_info_output() {

        require get_theme_file_path( '/includes/theme-info-page.php' );

	}

	/**
     * User color scheme classes
	 *
	 * Add a class to the body element according to
	 * the user's admin color scheme preference.
     *
     * @since  1.0.0
	 * @access public
	 * @return array Returns a modified array of body classes.
     */
	public function color_scheme_classes( $classes ) {

		// Add a class if user is logged in and admin bar is showing.
		if ( is_user_logged_in() && is_admin_bar_showing() ) {

			// Get the user color scheme option.
			$scheme = get_user_option( 'admin_color' );

			// Return body classes with `user-color-$scheme`.
			return array_merge( $classes, array( 'user-color-' . $scheme ) );
		}

		// Return the unfiltered classes if user is not logged in.
		return $classes;

	}

}

/**
 * Get an instance of the Functions class
 *
 * This function is useful for quickly grabbing data
 * used throughout the theme.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function ub_theme() {

	$ub_theme = Functions::get_instance();

	return $ub_theme;

}

// Run the Functions class.
ub_theme();