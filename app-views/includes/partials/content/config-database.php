<?php
/**
 * Database fieldset for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<section id="config-database" class="form-step">

	<h2><?php _e( 'Database Configuration' ); ?></h2>

	<fieldset>

		<legend class="screen-reader-text"><?php _e( 'Database Connection' ); ?></legend>

		<div>
			<p><?php _e( 'Enter your database connection details below. If you&#8217;re not sure about these, contact your host.' ); ?></p>

			<p><?php _e( 'Unique database table prefixes are needed if you want to run more than one installation with a single database. For security purposes a random prefix has been generated. You can void this by entering your own prefix. It is recommended for legibility that the prefix ends in an underscore.' ); ?></p>
		</div>


		<p class="config-field">
			<label for="app_db_name"><?php _e( 'Database Name' ); ?></label>
			<br /><input name="app_db_name" id="app_db_name" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'name', 'example database name' ), ENT_QUOTES ); ?>" />
			<br /><span class="description config-field-description"><?php _e( 'The name of the database you want to use.' ); ?></span>
		</p>

		<p class="config-field">
			<label for="app_db_user"><?php _e( 'Database User' ); ?></label>
			<br /><input name="app_db_user" id="app_db_user" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'root', 'example database user name' ), ENT_QUOTES ); ?>" />
			<br /><span class="description config-field-description"><?php _e( 'Your database user name.' ); ?></span>
		</p>

		<p class="config-field">
			<label for="app_db_password"><?php _e( 'Database Password' ); ?></label>
			<br /><input name="app_db_password" id="app_db_password" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'mysql', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" />
			<br /><span class="description config-field-description"><?php _e( 'Your database password.' ); ?></span>
		</p>

		<p class="config-field">
			<label for="app_db_host"><?php _e( 'Database Host' ); ?></label>
			<br /><input name="app_db_host" id="app_db_host" type="text" size="25" value="localhost" placeholder="localhost" />
			<br /><span class="description config-field-description"><?php _e( 'You should be able to get this info from your web host, if localhost doesn\'t work.' ); ?></span>
		</p>

		<p class="config-field">
			<label for="app_db_prefix"><?php _e( 'Database Prefix' ); ?></label>
			<br /><?php echo sprintf(
				'<small>%1s <code>%2s</code> %3s</small>',
				__( 'This field is pre-filled with a randomly generated prefix. The random table prefix does not necessarily make the database more secure but the option is provided for those who wish to use it. You may want to use something simple, such as' ), 'app_', __( '. But whatever you choose it is recommended that you end the prefix with an underscore to make the database more legible.' )
			); ?>
			<br /><input name="app_db_prefix" id="app_db_prefix" type="text" value="app_<?php echo esc_attr( md5( time() ) ); ?>_" placeholder="app_" size="25" />
			<br /><span class="description config-field-description">
			<?php echo sprintf(
				'%1s <code>app_%2s_</code><br />%3s',
				esc_html__( 'Random table prefix is:' ),
				md5( time() ),
				esc_html__( 'Change this if you want to define your own prefix.' )
			); ?></span>
		</p>

	</fieldset>

	<p class="step hide-if-no-js">
		<a href="#config-info-optional" class="button prev">
			<?php _e( 'Previous' ); ?>
		</a>
		<a href="#config-identity" class="button next">
			<?php _e( 'Next' ); ?>
		</a>
	</p>

</section>
