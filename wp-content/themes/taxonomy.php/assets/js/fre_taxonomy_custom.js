/* global i10n_WPTermImages, ajaxurl */
(function($, Models, Collections, Views) {
	$(document).ready(function() {
    'use strict';
	/* Globals */
	var ae_tax_images_modal,
		term_image_working;
	Views.ae_tax = Backbone.View.extend({
		el: 'body',
		model: [],
		events: {
			'click .ae-tax-images-media': 'openImage',
			'click .ae-tax-images-remove': 'removeImage'
		},
		initialize: function () {
		},
		openImage: function(e){
			var view = this;
			e.preventDefault();
			// Already adding
			if ( term_image_working ) {
				return;
			}

			// Open the modal
			if ( ae_tax_images_modal ) {
				ae_tax_images_modal.open();
				return;
			}

			// First time modal
			ae_tax_images_modal = wp.media.frames.ae_tax_images_modal = wp.media( {
				title:    i10n_WPTermImages.insertMediaTitle,
				button:   { text: i10n_WPTermImages.insertIntoPost },
				library:  { type: 'image' },
				multiple: false
			} );
			var clicked = $( this );
			ae_tax_images_modal.on( 'select', function () {
				// Prevent doubles
				view.term_image_lock( 'lock' );
				// Get the image URL
				var image = ae_tax_images_modal.state().get( 'selection' ).first().toJSON();
				if ( '' !== image ) {
					var imageUrl = image.url;
					if( typeof image.sizes.thumbnail !== 'undefined') {
						imageUrl = image.sizes.thumbnail.url;
					}
					if ( ! clicked.hasClass( 'quick' ) ) {
						$( '#project_category_image' ).val( image.id );
						$( '#ae-tax-images-photo' ).attr( 'src', imageUrl ).show();
						$( '.ae-tax-images-remove' ).show();
					} else {
						$( 'button.ae-tax-images-media' ).hide();
						$( 'a.button', '.inline-edit-row' ).show();
						$( ':input[name="project_category_image_image"]', '.inline-edit-row' ).val( image.id );
						$( 'img.ae-tax-images-media', '.inline-edit-row' ).attr( 'src', imageUrl).show();
					}
				}
				view.term_image_lock( 'unlock' );
			} );

			// Open the modal
			ae_tax_images_modal.open();
		},
		removeImage: function(e){
			e.preventDefault();
			// Clear image metadata
			if ( ! $( this ).hasClass( 'quick' ) ) {
				$( '#project_category_image' ).val( 0 );
				$( '#ae-tax-images-photo' ).attr( 'src', '' ).hide();
				$( '.ae-tax-images-remove' ).hide();
			} else {
				$( ':input[name="project_category_image"]', '.inline-edit-row' ).val( '' );
				$( 'img.ae-tax-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
				$( 'a.button', '.inline-edit-row' ).hide();
				$( 'button.ae-tax-images-media' ).show();
			}
		},
		term_image_lock: function( lock_or_unlock ) {
			if ( lock_or_unlock === 'unlock' ) {
				term_image_working = false;
				$( '.ae-tax-images-media' ).prop( 'disabled', false );
			} else {
				term_image_working = true;
				$( '.ae-tax-images-media' ).prop( 'disabled', true );
			}
		}
	});
		new Views.ae_tax();
	/**
	 * Lock the image fieldset
	 *
	 * @param {boolean} lock_or_unlock
	 */

});
})(jQuery, window.AE.Models, window.AE.Collections, window.AE.Views);
