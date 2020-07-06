<?php
/**
 * Not writable message for the config page
 *
 * @package App_Package
 * @subpackage Administration
 */

?>
<div class="setup-install-wrap setup-install-no-write">
	<p><?php
		/* translators: %s: app-config.php */
		printf( __( 'Sorry, but I can&#8217;t write the %s file.' ), '<code>app-config.php</code>' );
	?></p>
	<p><?php
		/* translators: %s: app-config.php */
		printf( __( 'You can create the %s file manually and paste the following text into it.' ), '<code>app-config.php</code>' );
	?></p>
	<textarea id="app-config" cols="98" rows="15" class="code" readonly="readonly"><?php
			foreach ( $app_config_file as $line ) {
				echo htmlentities($line, ENT_COMPAT, 'UTF-8' );
			}
	?></textarea>
	<p><?php _e( 'After you&#8217;ve done that, click &#8220;Run the installation.&#8221;' ); ?></p>
	<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the installation' ); ?></a></p>
</div>

<script>
(function(){
if ( ! /iPad|iPod|iPhone/.test( navigator.userAgent ) ) {
	var el = document.getElementById( 'app-config' );
	el.focus();
	el.select();
}
})();
</script>
