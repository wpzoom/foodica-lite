/**
 * Foodica > Recommended Plugins: install and activate plugins one by one, or all recommended ones.
 */
( function ( $ ) {
	'use strict';

	var settings = window.foodicaRecommendedPlugins || {};

	/**
	 * Install and activate the plugin of one card. Resolves when done (also on failure).
	 *
	 * @param {jQuery} $item Plugin card.
	 * @return {jQuery.Promise}
	 */
	function installPlugin( $item ) {
		var $button = $item.find( '.js-foodica-install-plugin' );
		var $error = $item.find( '.plugin-item-error' );
		var label = $button.text();
		var done = $.Deferred();

		if ( $item.hasClass( 'plugin-item--active' ) ) {
			return done.resolve().promise();
		}

		$button.prop( 'disabled', true ).text( settings.installing );
		$item.addClass( 'plugin-item--loading' );
		$error.attr( 'hidden', true ).text( '' );

		$.post( settings.ajaxUrl, {
			action: 'foodica_install_plugin',
			security: settings.nonce,
			slug: $item.data( 'slug' )
		} ).done( function ( response ) {
			if ( response && response.success ) {
				$item.addClass( 'plugin-item--active' );
				$button.text( settings.active );
				return;
			}
			$error.text( response && response.data && response.data.message ? response.data.message : settings.failed ).removeAttr( 'hidden' );
			$button.prop( 'disabled', false ).text( label );
		} ).fail( function () {
			$error.text( settings.failed ).removeAttr( 'hidden' );
			$button.prop( 'disabled', false ).text( label );
		} ).always( function () {
			$item.removeClass( 'plugin-item--loading' );
			done.resolve();
		} );

		return done.promise();
	}

	$( function () {
		$( '.js-foodica-install-plugin' ).on( 'click', function () {
			installPlugin( $( this ).closest( '.foodica-plugin-item' ) );
		} );

		// One after another: installing several plugins at the same time can fail.
		$( '.js-foodica-install-all' ).on( 'click', function () {
			var $all = $( this ).prop( 'disabled', true );
			var queue = $( '.foodica-plugins-recommended .foodica-plugin-item' ).not( '.plugin-item--active' ).toArray();

			( function next() {
				if ( ! queue.length ) {
					$all.prop( 'disabled', $( '.foodica-plugins-recommended .foodica-plugin-item' ).not( '.plugin-item--active' ).length === 0 );
					return;
				}
				installPlugin( $( queue.shift() ) ).always( next );
			}() );
		} );
	} );
}( jQuery ) );
