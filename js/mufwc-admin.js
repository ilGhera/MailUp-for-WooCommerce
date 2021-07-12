/**
 * Admin JS
 *
 * @author ilGhera
 * @package mailup-for-wc/js
 * @since 0.9.0
 */

var mufwcAdminController = function() {

	var self = this;

	self.onLoad = function() {

		self.tzCheckbox();
		self.chosen();
		self.mufwcPagination();
		self.autoRegistrationActivation();
		self.postFieldsActivation();
		self.privacyFieldActivation();

	}


	/**
	 * Checkboxes
	 */
	self.tzCheckbox = function() {

		jQuery(function($){
			$('input.mufwc[type=checkbox]').tzCheckbox({labels:['On','Off']});
		});

	}


	/**
	 * Fires Chosen
	 * @param  {bool} destroy method distroy
	 */
	self.chosen = function(destroy = false) {

		jQuery(function($){

			var select = $('.mufwc-select');

			if (destroy) {

				$(select).chosen('destroy');

			} else {
			
				$(select).chosen({
			
					disable_search_threshold: 10,
					// width: '200px'
				
				});

			}

		})

	}


	/**
	 * Tab navigation
	 */
	self.mufwcPagination = function() {

		jQuery(function($){

			var contents = $('.mufwc-admin')
			var url = window.location.href.split("#")[0];
			var hash = window.location.href.split("#")[1];

			if(hash) {
		        contents.hide();		    
			    $('#' + hash).fadeIn(200);		
		        $('h2#mufwc-admin-menu a.nav-tab-active').removeClass("nav-tab-active");
		        $('h2#mufwc-admin-menu a').each(function(){
		        	if($(this).data('link') == hash) {
		        		$(this).addClass('nav-tab-active');
		        	}
		        })
		        
		        $('html, body').animate({
		        	scrollTop: 0
		        }, 'slow');
			}

			$("h2#mufwc-admin-menu a").click(function () {
		        var $this = $(this);
		        
		        contents.hide();
		        $("#" + $this.data("link")).fadeIn(200);

		        self.chosen(true);
		        self.chosen();

		        $('h2#mufwc-admin-menu a.nav-tab-active').removeClass("nav-tab-active");
		        $this.addClass('nav-tab-active');

		        window.location = url + '#' + $this.data('link');

		        $('html, body').scrollTop(0);

		    })

		})
	        	
	}

    /**
     * Display auto-activation options
     */
    self.autoRegistrationActivation = function() {

		jQuery(function($){

            var button = $('.mufwc-newsletter-field .tzCheckBox');

            if ( $(button).hasClass('checked') ) {

                $('.mufwc-newsletter-option').show('slow');

            }

            $(button).on('click', function() {

                if ( $(this).hasClass('checked') ) {

                    $('.mufwc-newsletter-option').show('slow');

                } else {

                    $('.mufwc-newsletter-option').hide();

                }

            })


        })

    }


	/**
	 * Display fields only if activates
	 */
	self.postFieldsActivation = function() {

		jQuery(function($){

			var button   = $('.mufwc-post-activate span.tzCheckBox');
			var input    = $('#mufwc-post-activate');
			var fields   = $('.mufwc-post-field');
			var required = $('.mufwc-post-field.required textarea');

			if ( 'checked' == $(input).attr('checked') ) {

				$(fields).show();

			} else {

				$(required).removeAttr('required');

			}

			self.chosen(true);
			self.chosen();

			$(button).on('click', function(){

				if ( 'checked' == $(input).attr('checked') ) {

					$(fields).show('slow');
					$(required).attr('required', 'required');

				} else {

					$(fields).hide();
					$(required).removeAttr('required');

				}
				
				self.chosen(true);
				self.chosen();

			})

		})

	}


	self.privacyFieldActivation = function() {

		jQuery(function($){

			var formTypeField = $('#mufwc-guest-form');
			var privacyField  = $('.privacy-field');

			if ( 'email' == $(formTypeField).val() ) {

				privacyField.show();

			}

			$(formTypeField).on('change', function(){

				if ( 'email' == $(this).val() ) {

					privacyField.show('slow');
				
					self.chosen(true);
					self.chosen();

				} else {

					privacyField.hide();

				}

			})



		})

	} 

}

/**
 * Class starter with onLoad method
 */
jQuery(document).ready(function($) {
	
	var Controller = new mufwcAdminController;
	Controller.onLoad();

});
