/**
 * This theme toggle script is added here
 * for reference. The script is in use via
 * the `theme_mode` function.
 *
 * @see /includes/template-finctions.php
 */
jQuery( document ).ready( function ($) {

	var button = $( '#theme-toggle' );

    // Check local storage and set theme.
    if ( localStorage.theme ) {
		$( 'body' ).addClass( localStorage.theme );
		$( button ).text( localStorage.text );
	} else {

		// Set default theme.
		$( 'body' ).addClass( 'light-mode' );
		$( button ).text( 'Dark Theme' );
	}

	// Switch theme and store in local storage.
	$( button ).click( function() {

		if ( $ ( 'body' ).hasClass( 'light-mode') ) {
			$( 'body' ).removeClass( 'light-mode' ).addClass( 'dark-mode' );
			$( button ).text( 'Light Theme' );
			localStorage.theme = 'dark-mode';
			localStorage.text  = 'Light Theme';
		} else {
			$( 'body' ).removeClass( 'dark-mode' ).addClass( 'light-mode' );
			$( button ).text( 'Dark Theme' );
			localStorage.theme = 'light-mode';
			localStorage.text  = 'Dark Theme';
		}
	});
});