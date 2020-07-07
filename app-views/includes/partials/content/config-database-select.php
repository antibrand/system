<?php
/**
 * Can't select a database
 *
 * Layout markup is provided by the config page template.
 * This is simply the message printed if the installation
 * process cannot cennect with a database.
 *
 * @package App_Package
 * @subpackage Administration
 *
 * @see wp-includes/wp-db.php
 */

?>
<h2><?php _e( 'Can\'t Select a Database' ); ?></h2>

<?php if ( ! empty( $db ) ) : ?>

	<p><?php echo sprintf(
		__( 'We were able to connect to the database server (which means your username and password is okay) but not able to select the "%s" database.' ),
		'<code>' . htmlspecialchars( $db, ENT_QUOTES ) . '</code>'
	); ?></p>

<?php else : ?>

	<p><?php _e( 'We were able to connect to the database server (which means your username and password is okay) but not able to select the database.' ); ?></p>

<?php endif; ?>

<ul>

	<li><?php _e( 'Are you sure it exists?' ); ?></li>

<?php if ( ! empty( ( $db && $this->dbuser ) ) ) : ?>

	<li>
	<?php echo sprintf(
		__( 'Does the user %1$s have permission to use the %2$s database?' ),
		'<code>' . htmlspecialchars( $this->dbuser, ENT_QUOTES )  . '</code>',
		'<code>' . htmlspecialchars( $db, ENT_QUOTES ) . '</code>'
	); ?>
	</li>

<?php elseif( ! empty( ( $db ) ) ) : ?>

	<li>
	<?php echo sprintf(
		__( 'Does the user have permission to use the %1$s database?' ),
		'<code>' . htmlspecialchars( $db, ENT_QUOTES ) . '</code>'
	); ?>
	</li>

<?php elseif( ! empty( ( $this->dbuser ) ) ) : ?>

	<li>
	<?php echo sprintf(
		__( 'Does the user %1$s have permission to use the database?' ),
		'<code>' . htmlspecialchars( $this->dbuser, ENT_QUOTES )  . '</code>'
	); ?>
	</li>

<?php else : ?>

	<li><?php _e( 'Does the user have permission to use the database?' ); ?></li>

<?php endif; ?>

<?php if ( ! empty( ( $db ) ) ) : ?>

	<li>
	<?php echo sprintf(
		__( 'On some systems the name of your database is prefixed with your username, so it would be like <code>username_%1$s</code>. Could that be the problem?' ),
		htmlspecialchars( $db, ENT_QUOTES )
	); ?>
	</li>

<?php else : ?>

	<li><?php _e( 'On some systems the name of your database is prefixed with your username. Could that be the problem?' ); ?></li>

<?php endif; ?>

</ul>

<p><?php _e( 'If you don\'t know how to set up a database you should contact your host.' ); ?></p>

<?php echo sprintf(
	'<p class="step"><a href="%1s" class="button button-large">%2s</a></p>',
	esc_url( 'config.php' ),
	__( 'Retry Configuration' )

); ?>
