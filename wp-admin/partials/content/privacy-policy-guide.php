<?php
/**
 * Privacy policy guide page template
 *
 * @package    App_Package
 * @subpackage Administration
 */

?>
<div class="wrap">
	<h1><?php echo $title; ?></h1>
	<p class="description"><?php _e( 'Demonstration content for a privacy policy page' ); ?></p>

	<hr class="wp-header-end" />

	<div class="privacy-policy-guide">
		<?php WP_Privacy_Policy_Content::privacy_policy_guide(); ?>
	</div>
</div>