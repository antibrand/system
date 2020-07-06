<?php
/**
 * Database fieldset for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
			<section id="config-database" class="form-step">

				<h2><?php _e( 'System Configuration' ); ?></h2>

				<fieldset>

					<legend class="screen-reader-text"><?php _e( 'Database Connection' ); ?></legend>

					<div>
						<p><?php _e( 'Enter your database connection details below. If you&#8217;re not sure about these, contact your host.' ); ?></p>

						<p><?php _e( 'Unique database table prefixes are needed if you want to run more than one installation with a single database. For security purposes a random prefix has been generated. You can void this by entering your own prefix. It is recommended for legibility that the prefix ends in an underscore.' ); ?></p>
					</div>

					<table class="form-table">
						<tr>
							<th scope="row"><label for="app_db_name"><?php _e( 'Database Name' ); ?></label></th>
							<td><input name="app_db_name" id="app_db_name" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'name', 'example database name' ), ENT_QUOTES ); ?>" /></td>
							<td><?php _e( 'The name of the database you want to use.' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_user"><?php _e( 'Database User' ); ?></label></th>
							<td><input name="app_db_user" id="app_db_user" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'root', 'example database user name' ), ENT_QUOTES ); ?>" /></td>
							<td><?php _e( 'Your database user name.' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_password"><?php _e( 'Database Password' ); ?></label></th>
							<td><input name="app_db_password" id="app_db_password" type="text" size="25" value="" placeholder="<?php echo htmlspecialchars( _x( 'mysql', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" /></td>
							<td><?php _e( 'Your database password.' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_host"><?php _e( 'Database Host' ); ?></label></th>
							<td><input name="app_db_host" id="app_db_host" type="text" size="25" value="localhost" placeholder="localhost" /></td>
							<td><?php
								printf( __( 'You should be able to get this info from your web host, if %s doesn&#8217;t work.' ),'<code>localhost</code>' );
							?></td>
						</tr>
						<tr>
							<th scope="row"><label for="app_db_prefix"><?php _e( 'Database Prefix' ); ?></label></th>
							<td>
								<input name="app_db_prefix" id="app_db_prefix" type="text" value="app_<?php echo esc_attr( md5( time() ) ); ?>_" placeholder="app_" size="25" />
								<?php echo sprintf(
									'<p class="description">%1s <code>%2s</code> %3s</p>',
									__( 'The random table prefix does not necessarily make the database more secure but the option is provided for those who wish to use it. You may want to use something simple, such as' ),
									'app_',
									__( '. But whatever you choose it is recommended that you end the prefix with an underscore to make the database more legible.' )
								); ?>
							</td>
							<td><?php echo sprintf(
								'%1s <code>app_%2s_</code><br />%3s',
								esc_html__( 'Random table prefix is:' ),
								md5( time() ),
								esc_html__( 'Change this if you want to define your own prefix.' )

							); ?></td>
						</tr>
					</table>

				</fieldset>

				<?php if ( isset( $_GET['noapi'] ) ) : ?>
				<input name="noapi" type="hidden" value="1" />
				<?php endif; ?>

				<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>" />

				<p class="step"><a href="#config-identity" class="button hide-if-no-js prev"><?php _e( 'Previous' ); ?></a> <input name="submit" type="submit" value="<?php echo htmlspecialchars( __( 'Submit Information' ), ENT_QUOTES ); ?>" class="button" /></p>

			</section>
