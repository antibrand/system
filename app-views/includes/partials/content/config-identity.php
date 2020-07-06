<?php
/**
 * Identity fieldset for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
			<section id="config-identity" class="form-step">

				<h2><?php _e( 'System Configuration' ); ?></h2>

				<fieldset>

					<legend class="screen-reader-text"><?php _e( 'White Label' ); ?></legend>

					<div id="white-label-inputs-info" class="install-fieldset-section">

						<p><?php _e( 'Enter an application name to be used throughout the website management system. This allows you to "white label" the application and can be changed at any time in the <code>id-config</code> file in the application\'s root directory.' ); ?></p>

						<p class="config-field config-app-name">
							<label for="app_name"><?php _e( 'Application Name' ); ?></label>
							<br /><input name="app_name" id="app_name" type="text" size="35" value="" placeholder="<?php echo htmlspecialchars( _x( 'White Label System', 'example name for the website management system' ), ENT_QUOTES ); ?>" />
							<br /><span class="description config-field-description"><?php _e( 'Enter the name to use for your website management system.' ); ?></span>
						</p>
						<p class="config-field config-app-tagline">
							<label for="app_tagline"><?php _e( 'Application Tagline/Description' ); ?></label>
							<br /><input name="app_tagline" id="app_tagline" type="text" size="55" value="" placeholder="<?php echo htmlspecialchars( _x( 'Your tagline or description', 'example tagline for the website management system' ), ENT_QUOTES ); ?>" />
							<br /><span class="description config-field-description"><?php _e( 'Used in documentation, system status features, etc.' ); ?></span>
						</p>
						<p class="config-field config-app-website">
							<label for="app_website"><?php _e( 'Application Website' ); ?></label>
							<br /><input name="app_website" id="app_website" type="text" size="35" value="" placeholder="<?php echo esc_url( 'https://example.com/' ); ?>" />
							<br /><span class="description config-field-description"><?php _e( 'Link users to your website for more information.' ); ?></span>
						</p>

					</div>

					<div id="white-label-inputs-info" class="install-fieldset-section">

						<p class="config-field config-app-logo">
							<label for="app_logo"><?php _e( 'Application Logo' ); ?></label>
							<br /><input name="app_logo" id="app_logo" type="file" accept="image/png, image/jpg, image/jpeg image/gif" />
							<br /><span class="description config-field-description"><?php _e( 'Accepts .png, .jpg, .jpeg, .gif.' ); ?></span>
						</p>

					</div>

				</fieldset>

				<p class="step hide-if-no-js"><a href="#config-info-optional" class="button prev"><?php _e( 'Previous' ); ?></a> <a href="#config-database" class="button next"><?php _e( 'Next' ); ?></a></p>
			</section>
