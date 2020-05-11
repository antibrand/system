/**
 * Simple jQuery tabs
 */

;( function( $, window, undefined ) {
	'use strict';

	$.fn.app_tabs = function( options ) {

		// Default options.
		var defaults = {
			mouseevent   : 'click',
			activeclass  : 'active',
			attribute    : 'href',
			animation    : false,
			autorotate   : false,
			deeplinking  : false,
			pauseonhover : true,
			delay        : 2000,
			active       : 1,
			container    : false,
			controls     : {
				prev : '.prev-tab',
				next : '.next-tab'
			}
		};

		var options = $.extend( defaults, options );

		return this.each( function() {

			var $this = $(this), _cache_li = [], _cache_div = [];

			if ( options.container ) {
				var _container = $( options.container );
			} else {
				var _container = $this;
			}

			var _app_tabs = _container.find( '> div' );

			// Caching.
			_app_tabs.each( function() {
				_cache_div.push( $(this).css( 'display' ) );
			});

			// Autorotate.
			var elements = $this.find( '> ul > li' ), i = options.active - 1; // ungly

			if ( ! $this.data( 'app_tabs-init' ) ) {

				$this.data( 'app_tabs-init', true );
				$this.opts = [];

				$.map(
					[
						'mouseevent',
						'activeclass',
						'attribute',
						'animation',
						'autorotate',
						'deeplinking',
						'pauseonhover',
						'delay',
						'container'
					],
					function( val, i ) {
						$this.opts[val] = $this.data(val) || options[val];
					}
				);

				$this.opts['active'] = $this.opts.deeplinking ? deep_link() : ( $this.data( 'active' ) || options.active )

				_app_tabs.hide();

				if ( $this.opts.active ) {
					_app_tabs.eq( $this.opts.active - 1 ).show();
					elements.eq( $this.opts.active - 1 ).addClass( options.activeclass );
				}

				var fn = eval(

					function(e, tab) {

						var _this = tab ? elements.find( 'a[' + $this.opts.attribute + '="' + tab +'"]' ).parent() : $(this);

						_this.trigger( '_before' );
						elements.removeClass( options.activeclass );
						_this.addClass( options.activeclass );
						_app_tabs.hide();

						i = elements.index(_this);

						var currentTab = tab || _this.find( 'a' ).attr( $this.opts.attribute );

						if ( $this.opts.deeplinking ) {
							location.hash = currentTab;
						}

						if ( $this.opts.animation ) {

							_container.find( currentTab ).animate(
								{ opacity: 'show' },
								250,
								function() {
									_this.trigger( '_after' );
								}
							);

						} else {
							_container.find( currentTab ).show();
							_this.trigger( '_after' );
						}

						return false;
					}
				);

				var init = eval( "elements." + $this.opts.mouseevent + "(fn)" );

				init;

				var t;
				var forward = function() {

					// Wrap around.
					i = ++i % elements.length;

					$this.opts.mouseevent == 'hover' ? elements.eq(i).trigger( 'mouseover' ) : elements.eq(i).click();

					if ( $this.opts.autorotate ) {

						clearTimeout(t);
						t = setTimeout( forward, $this.opts.delay );

						$this.mouseover( function () {
							if ( $this.opts.pauseonhover ) {
								clearTimeout(t);
							}
						});
					}
				}

				if ( $this.opts.autorotate ) {

					t = setTimeout( forward, $this.opts.delay );

					$this.hover( function() {
						if ( $this.opts.pauseonhover ) {
							clearTimeout(t);
						}
					}, function() {
						t = setTimeout( forward, $this.opts.delay );
					});

					if ( $this.opts.pauseonhover ) {
						$this.on( "mouseleave", function() {
							clearTimeout(t); t = setTimeout( forward, $this.opts.delay );
						});
					}
				}

				function deep_link() {

					var ids = [];
					elements.find( 'a' ).each( function() {
						ids.push( $(this).attr( $this.opts.attribute ) );
					});

					var index = $.inArray( location.hash, ids )

					if ( index > -1 ) {
						return index + 1
					} else {
						return ( $this.data( 'active' ) || options.active )
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

				$this.find( options.controls.next ).click( function() {
					move( 'forward' );
				});

				$this.find( options.controls.prev ).click( function() {
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
						$(this).removeClass( options.activeclass );
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