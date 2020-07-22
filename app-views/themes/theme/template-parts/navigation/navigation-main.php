<?php
/**
 * Main site navigation markup
 *
 * Renders the menu, mobile menu toggle, and wrapper.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

/**
 * Conditional menu fallback function
 *
 * Gets the included fallback if the current user can
 * customize. Gets the menu default for all other
 * users and visitors.
 */
if ( current_user_can( 'customize' ) ) {
	$fallback_cb = 'AB_Theme\Includes\main_menu_fallback';
} else {
	$fallback_cb = 'wp_page_menu';
}

// Main menu arguments.
$args = [
	'theme_location'  => 'main',
	'menu'            => '',
	'container'       => 'div',
	'container_class' => 'main-menu-wrap',
	'container_id'    => 'main-menu-wrap',
	'menu_class'      => 'main-menu',
	'menu_id'         => 'main-menu',
	'echo'            => true,
	'fallback_cb'     => $fallback_cb,
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'item_spacing'    => 'preserve', // Accepts `preserve` or `discard`.
	'depth'           => 0, // All levels is `0`.
	'walker'          => ''
];

?>
<nav id="site-navigation" class="main-navigation" role="directory" itemscope itemtype="http://schema.org/SiteNavigationElement">
	<button class="menu-toggle" aria-controls="main-menu" aria-expanded="false">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="theme-icon menu-icon"><path d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"/></svg>
		<?php esc_html_e( 'Menu', 'antibrand' ); ?>
	</button>
	<?php wp_nav_menu( $args ); ?>
</nav>