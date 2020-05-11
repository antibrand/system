/**
 * Simple jQuery tabs
 */

;( function( $, window, undefined ) {
	'use strict';

	$.fn.app_tabs = function( options ) {

		// Default options.
		var defaults = {
			tab_mouseevent   : 'click',
			tab_activeclass  : 'active',
			tab_attribute    : 'href',
			tab_animation    : false,
			tab_anispeed     : 400,
			tab_autorotate   : false,
			tab_deeplinking  : false,
			tab_pauseonhover : true,
			tab_delay        : 2000,
			tab_active       : 1,
			tab_container    : false,
			tab_controls     : {
				tab_prev : '.prev-tab',
				tab_next : '.next-tab'
			}
		};

		var options = $.extend( defaults, options );

		return this.each( function() {

			var $this = $(this), _cache_li = [], _cache_div = [];

			if ( options.tab_container ) {
				var _tab_container = $( options.tab_container );
			} else {
				var _tab_container = $this;
			}

			var _app_tabs = _tab_container.find( '> div' );

			// Caching.
			_app_tabs.each( function() {
				_cache_div.push( $(this).css( 'display' ) );
			});

			// Autorotate.
			var elements = $this.find( '> ul > li' ), i = options.tab_active - 1; // ungly

			if ( ! $this.data( 'app_tabs-init' ) ) {

				$this.data( 'app_tabs-init', true );
				$this.opts = [];

				$.map(
					[
						'tab_mouseevent',
						'tab_activeclass',
						'tab_attribute',
						'tab_animation',
						'tab_anispeed',
						'tab_autorotate',
						'tab_deeplinking',
						'tab_pauseonhover',
						'tab_delay',
						'tab_container'
					],
					function( val, i ) {
						$this.opts[val] = $this.data(val) || options[val];
					}
				);

				$this.opts['tab_active'] = $this.opts.tab_deeplinking ? deep_link() : ( $this.data( 'tab_active' ) || options.tab_active )

				_app_tabs.hide();

				if ( $this.opts.tab_active ) {
					_app_tabs.eq( $this.opts.tab_active - 1 ).show();
					elements.eq( $this.opts.tab_active - 1 ).addClass( options.tab_activeclass );
				}

				var fn = eval(

					function( e, tab ) {

						if ( tab ) {
							var _this = elements.find( 'a[' + $this.opts.tab_attribute + '="' + tab +'"]' ).parent();
						} else {
							var _this = $(this);
						}

						_this.trigger( '_before' );
						elements.removeClass( options.tab_activeclass );
						_this.addClass( options.tab_activeclass );
						_app_tabs.hide();

						i = elements.index( _this );

						var currentTab = tab || _this.find( 'a' ).attr( $this.opts.tab_attribute );

						if ( $this.opts.tab_deeplinking ) {
							location.hash = currentTab;
						}

						if ( $this.opts.tab_animation ) {

							_tab_container.find( currentTab ).animate(
								{ opacity : 'show' },
								$this.opts.tab_anispeed
							);
							_this.trigger( '_after' );

						} else {
							_tab_container.find( currentTab ).show();
							_this.trigger( '_after' );
						}

						return false;
					}
				);

				var init = eval( "elements." + $this.opts.tab_mouseevent + "(fn)" );

				init;

				var t;
				var forward = function() {

					// Wrap around.
					i = ++i % elements.length;

					$this.opts.tab_mouseevent == 'hover' ? elements.eq(i).trigger( 'mouseover' ) : elements.eq(i).click();

					if ( $this.opts.tab_autorotate ) {

						clearTimeout(t);
						t = setTimeout( forward, $this.opts.delay );

						$this.mouseover( function () {
							if ( $this.opts.tab_pauseonhover ) {
								clearTimeout(t);
							}
						});
					}
				}

				if ( $this.opts.tab_autorotate ) {

					t = setTimeout( forward, $this.opts.tab_delay );

					$this.hover( function() {
						if ( $this.opts.tab_pauseonhover ) {
							clearTimeout(t);
						}
					}, function() {
						t = setTimeout( forward, $this.opts.tab_delay );
					});

					if ( $this.opts.tab_pauseonhover ) {
						$this.on( "mouseleave", function() {
							clearTimeout(t); t = setTimeout( forward, $this.opts.delay );
						});
					}
				}

				function deep_link() {

					var ids = [];
					elements.find( 'a' ).each( function() {
						ids.push( $(this).attr( $this.opts.tab_attribute ) );
					});

					var index = $.inArray( location.hash, ids )

					if ( index > -1 ) {
						return index + 1
					} else {
						return ( $this.data( 'active' ) || options.tab_active )
					}

				}

				var move = function( direction) {

					// Wrap around.
					if ( direction == 'forward' ) {
						i = ++i % elements.length;
					}

					// Wrap around.
					if ( direction == 'backward' ) {
						i = --i % elements.length;
					}

					elements.eq( i ).click();
				}

				$this.find( options.tab_controls.tab_next ).click( function() {
					move( 'forward' );
				});

				$this.find( options.tab_controls.tab_prev ).click( function() {
					move( 'backward' );
				});

				$this.on ( 'show', function( e, tab ) {
					fn( e, tab );
				});

				$this.on ( 'next', function() {
					move( 'forward' );
				});

				$this.on ( 'prev', function() {
					move( 'backward' );
				});

				$this.on ( 'destroy', function() {

					$(this).removeData().find( '> ul li' ).each( function(i) {
						$(this).removeClass( options.tab_activeclass );
					});

					_app_tabs.each( function(i) {
						$(this).removeAttr( 'style' ).css( 'display', _cache_div[i] );
					});
				});
			}
		});
	};

	$(document).ready( function () {
		$( '[data-toggle="app-tabs"]' ).app_tabs();
	} );

})( jQuery );