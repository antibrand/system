<?php
/**
 * Identity fieldset for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

/**
 * Look for identity definitions
 *
 * If an id-config file exists and identity constants have been
 * defined then those will be used as the initial values of the
 * field inputs.
 *
 * @since 1.0.0
 */
if ( defined( 'APP_NAME' ) && APP_NAME ) {
	$app_name = APP_NAME;
} else {
	$app_name = '';
}

if ( defined( 'APP_TAGLINE' ) && APP_TAGLINE ) {
	$app_tagline = APP_TAGLINE;
} else {
	$app_tagline = '';
}

if ( defined( 'APP_WEBSITE' ) && APP_WEBSITE ) {
	$app_website = APP_WEBSITE;
} else {
	$app_website = '';
}

?>
<section id="config-identity" class="form-step">

	<h2><?php _e( 'Identity Configuration' ); ?></h2>

	<fieldset>

		<legend class="screen-reader-text"><?php _e( 'White Label' ); ?></legend>

		<div id="white-label-inputs-info" class="install-fieldset-section">

			<p><?php _e( 'Enter an application name to be used throughout the website management system. This allows you to "white label" the application and can be changed at any time in the <code>id-config</code> file in the application\'s root directory.' ); ?></p>

			<p class="config-field config-app-name">
				<label for="app_name"><?php _e( 'Application Name' ); ?></label>
				<br /><input name="app_name" id="app_name" type="text" size="35" value="<?php echo esc_html( $app_name ); ?>" placeholder="<?php echo htmlspecialchars( _x( 'White Label System', 'example name for the website management system' ), ENT_QUOTES ); ?>" />
				<br /><span class="description config-field-description"><?php _e( 'Enter the name to use for your website management system.' ); ?></span>
			</p>
			<p class="config-field config-app-tagline">
				<label for="app_tagline"><?php _e( 'Application Tagline/Description' ); ?></label>
				<br /><input name="app_tagline" id="app_tagline" type="text" size="55" value="<?php echo esc_html( $app_tagline ); ?>" placeholder="<?php echo htmlspecialchars( _x( 'Your tagline or description', 'example tagline for the website management system' ), ENT_QUOTES ); ?>" />
				<br /><span class="description config-field-description"><?php _e( 'Used in documentation, system status features, etc.' ); ?></span>
			</p>
			<p class="config-field config-app-website">
				<label for="app_website"><?php _e( 'Application Website' ); ?></label>
				<br /><input name="app_website" id="app_website" type="text" size="35" value="<?php echo esc_url( $app_website ); ?>" placeholder="<?php echo esc_url( 'https://example.com/' ); ?>" />
				<br /><span class="description config-field-description"><?php _e( 'Link users to your website for more information.' ); ?></span>
			</p>

			<p class="config-field config-app-logo">
				<label for="app_icon"><?php _e( 'Application Icon' ); ?></label>
				<br /><input name="app_icon" id="app_icon" type="file" accept="image/png, image/jpg, image/jpeg image/gif" />
				<br /><span class="description config-field-description"><?php _e( 'Accepts .png, .jpg, .jpeg, .gif.' ); ?></span>
			</p>

		</div>

	</fieldset>

	<?php if ( isset( $_GET['noapi'] ) ) : ?>
	<input name="noapi" type="hidden" value="1" />
	<?php endif; ?>

	<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>" />

	<p class="step">
		<a href="#config-database" class="button hide-if-no-js prev">
			<?php _e( 'Previous' ); ?>
		</a>
		<input name="submit" type="submit" value="<?php echo htmlspecialchars( __( 'Submit Configuration' ), ENT_QUOTES ); ?>" class="button" />
	</p>

</section>
