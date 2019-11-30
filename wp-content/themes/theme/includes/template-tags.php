<?php
/**
 * Template tags
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Namespace specificity for theme functions & filters.
namespace AB_Theme\Tags;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the application version is 5.0 or greater.
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if the version is 5.0 or greater.
 */
function theme_new_cms() {

	// Get the version.
	$version = get_bloginfo( 'version' );

	if ( $version >= 5.0 ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check if the CMS is ClassicPress.
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if ClassicPress is running.
 */
function theme_classicpress() {

	if ( function_exists( 'classicpress_version' ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check for Advanced Custom Fields
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if the ACF free or Pro plugin is active.
 */
function theme_acf() {

	if ( class_exists( 'acf' ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check for Advanced Custom Fields Pro
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if the ACF Pro plugin is active.
 */
function theme_acf_pro() {

	if ( class_exists( 'acf_pro' ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check for Advanced Custom Fields options page
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if ACF 4.0 free plus the
 *              Options Page addon or Pro plugin is active.
 */
function theme_acf_options() {

	if ( class_exists( 'acf_pro' ) ) {
		return true;
	} elseif ( ( class_exists( 'acf' ) && class_exists( 'acf_options_page' ) ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Site Schema
 *
 * Conditional Schema attributes for `<div id="page"`.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the relevant itemtype.
 */
function site_schema() {

	// Change page slugs and template names as needed.
	if ( is_page( 'about' ) || is_page( 'about-us' ) || is_page_template( 'page-about.php' ) || is_page_template( 'about.php' ) ) {
		$itemtype = esc_attr( 'AboutPage' );
	} elseif ( is_page( 'contact' ) || is_page( 'contact-us' ) || is_page_template( 'page-contact.php' ) || is_page_template( 'contact.php' ) ) {
		$itemtype = esc_attr( 'ContactPage' );
	} elseif ( is_page( 'faq' ) || is_page( 'faqs' ) || is_page_template( 'page-faq.php' ) || is_page_template( 'faq.php' ) ) {
		$itemtype = esc_attr( 'QAPage' );
	} elseif ( is_page( 'cart' ) || is_page( 'shopping-cart' ) || is_page( 'checkout' ) || is_page_template( 'cart.php' ) || is_page_template( 'checkout.php' ) ) {
		$itemtype = esc_attr( 'CheckoutPage' );
	} elseif ( is_front_page() || is_page() ) {
		$itemtype = esc_attr( 'WebPage' );
	} elseif ( is_author() || is_plugin_active( 'buddypress/bp-loader.php' ) && bp_is_home() || is_plugin_active( 'bbpress/bbpress.php' ) && bbp_is_user_home() ) {
		$itemtype = esc_attr( 'ProfilePage' );
	} elseif ( is_search() ) {
		$itemtype = esc_attr( 'SearchResultsPage' );
	} else {
		$itemtype = esc_attr( 'Blog' );
	}

	echo $itemtype;

}

/**
 * Posted on
 *
 * Prints HTML with meta information for the current post-date/time.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the date posted.
 */
function posted_on() {

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s: post date. */
		esc_html_x( 'Posted on %s', 'post date', 'antibrand' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}

/**
 * Posted by
 *
 * Prints HTML with meta information for the current author.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the author name.
 */
function posted_by() {

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'antibrand' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}

/**
 * Entry footer
 *
 * Prints HTML with meta information for the categories, tags and comments.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns various post-related links.
 */
function entry_footer() {

	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {

		$categories_list = get_the_category_list( esc_html__( ', ', 'antibrand' ) );
		if ( $categories_list ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'antibrand' ) . '</span>', $categories_list );
		}

		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'antibrand' ) );

		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'antibrand' ) . '</span>', $tags_list );
		}

	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'antibrand' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				get_the_title()
			)
		);
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			wp_kses(
				__( ' Edit <span class="screen-reader-text">%s</span>', 'antibrand' ),
				[
					'span' => [
						'class' => [],
					],
				]
			),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

}

/**
 * Post thumbnail
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns HTML for the post thumbnail.
 */
function post_thumbnail() {

	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	// Check for the large 16:9 video image size.
	if ( has_image_size( 'image-name' ) ) {
		$size = 'large-video';
	} else {
		$size = 'post-thumbnail';
	}

	// Thumbnail image arguments.
	$args = [
		'alt'  => '',
		'role' => 'presentation'
	];


	if ( is_singular() ) :
		?>

		<div class="post-thumbnail">
			<?php the_post_thumbnail( $size, $args ); ?>
		</div>

		<?php
	else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( $size, $args ); ?>
		</a>

		<?php
	endif;
}

/**
 * Open template tags.
 *
 * Following are template tags for further development
 * by the theme author, child themes, or plugins.
 *
 * These are primarily provided as examples.
 *
 * @todo Possibly add more open tags but perhaps not,
 *       as this is a starter theme.
 *
 * @since  1.0.0
 * @access public
 */

// Fires after opening `body` and before `#page`.
function before_page() {
	do_action( 'before_page' );
}

// Fires after `#page` and before `wp_footer`.
function after_page() {
	do_action( 'after_page' );
}

/**
 * Theme toggle funcionality
 *
 * Prints the toggle button and adds the
 * toggle script to the footer.
 *
 * @since  1.0.0
 * @access public
 * @return mixed
 */
function theme_mode() {

	// Add the toggle script to the footer.
	add_action( 'wp_footer', 'AB_Theme\Includes\theme_mode_script' );

	// Label before the icon.
	$label = esc_html__( 'Lights: ', 'antibrand' );

	// Bulb on icon for when the default light theme is active.
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

	// Toggle button markup.
	$button = sprintf(
		'<button class="theme-toggle-button" type="button" name="dark_light" title="%1s"><span>%2s</span> <span>%3s</span></button>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' ),
		$label,
		$bulb_on
	);

	// Print the toggle button.
	echo sprintf(
		'<div id="theme-toggle" class="theme-toggle">%1s</div>',
		$button
	);

}