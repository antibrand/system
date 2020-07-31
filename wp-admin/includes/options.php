<?php
/**
 * Options Administration API.
 *
 * @package App_Package
 * @subpackage Administration
 * @since 4.4.0
 */

/**
 * Output JavaScript to toggle display of additional settings if avatars are disabled.
 *
 * @since 4.2.0
 */
function options_discussion_add_js() {
?>
	<script>
	(function($){
		var parent = $( '#show_avatars' ),
			children = $( '.avatar-settings' );
		parent.change(function(){
			children.toggleClass( 'hide-if-js', ! this.checked );
		});
	})(jQuery);
	</script>
<?php
}

/**
 * Display JavaScript on the page.
 *
 * @since 3.5.0
 */
function options_reading_add_js() {
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		var section = $('#front-static-pages'),
			staticPage = section.find('input:radio[value="page"]'),
			selects = section.find('select'),
			check_disabled = function(){
				selects.prop( 'disabled', ! staticPage.prop('checked') );
			};
		check_disabled();
 		section.find('input:radio').change(check_disabled);
	});
</script>
<?php
}

/**
 * Render the site charset setting.
 *
 * @since 3.5.0
 */
function options_reading_blog_charset() {
	echo '<input name="blog_charset" type="text" id="blog_charset" value="' . esc_attr( get_option( 'blog_charset' ) ) . '" class="regular-text" />';
	echo '<p class="description">' . __( 'The character encoding of your site (UTF-8 is recommended)' ) . '</p>';
}
