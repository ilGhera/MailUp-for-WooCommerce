/**
 * JS
 *
 * @author ilGhera
 * @package mailup-for-wc/js
 * @since 0.9.0
 */

var mufwcController = function() {

	var self = this;

	self.onLoad = function() {

		self.anchor();
		self.subscribe_button();
		self.access_form();
		self.subscribe_form();

	}


	self.subscribe_button = function() {

		jQuery(function($) {
			
			$('.mufwc-add-button').on('click', function() {
				
				var list          = $('.mufwc-add-button').data('list');
				var group         = $('.mufwc-add-button').data('group');
				var response_text = $('.mufwc-add-button').data('response-text');
				var redirect      = $('.mufwc-add-button').data('redirect');
				var product_id    = $('.mufwc-add-button').data('product-id');

				var data = {
					'action': 'mufwc-add-to',
					'mufwc-subscribe-noce': mufwcSettings.nonce,
					'username': mufwcSettings.userName,
					'mail': mufwcSettings.email,
					'list': list,
					'group': group,
					'response-text': response_text,
					'redirect': redirect,
					'product_id': product_id
				};

				$.post( mufwcSettings.ajaxURL, data, function(response) {
					
					$('.mufwc-add-button').html(response);
				
				})

			})

		})

	}


	self.anchor = function() {

		jQuery(function($){
			
			$('.mufwc-add-button-ancor').on('click', function(){
		
				$('html, body').animate({
		
					scrollTop: $('.mufwc-before-text').offset().top - 150
		
				}, 'slow');

				if($(this).hasClass('logged')) {
		
					$('.mufwc-add-button').trigger('click');
		
				}					
		
			})

		})

	}


	self.access_form = function() {

		jQuery(function($){

			$('.mufwc-register').on('click', function(){

				$(this).addClass('active');
				$('.mufwc-login').removeClass('active');
				$('.mufwc-access .woocommerce .col-1').hide();
				$('.mufwc-access .woocommerce .col-2').show();

			})

			$('.mufwc-login').on('click', function() {

				$(this).addClass('active');
				$('.mufwc-register').removeClass('active');
				$('.mufwc-access .woocommerce .col-2').hide();
				$('.mufwc-access .woocommerce .col-1').show();      

			})


		})

	}


	self.subscribe_form = function() {

		jQuery(function($) {
			
			$('.newsletter-form-button').on('click', function(e) {

				e.preventDefault();
				
				var nonce         = $('#mufwc-guest-form-nonce').val();
				var name          = $('#mufwc-name').val();
				var mail          = $('#mufwc-mail').val();
				var list          = $('#mufwc-list').val();
				var group         = $('#mufwc-group').val();
				var response_text = $('#response-text').val();
				var redirect      = $('#mufwc-redirect').val();
				// var product_id    = $('.mufwc-product-id').val();

				var data = {
					'action': 'mufwc-form',
					'mufwc-guest-form-nonce': nonce,
					'name': name,
					'mail': mail,
					'list': list,
					'group': group,
					'response-text': response_text,
					'redirect': redirect
					// 'product_id': product_id
				};

				$.post( mufwcSettings.ajaxURL, data, function(response) {
					
					$('#mufwc-subscription-form').html(response);
				
				})

			})

		})

	}
}

/**
 * Class starter with onLoad method
 */
jQuery(document).ready(function($) {
	
	var Controller = new mufwcController;
	Controller.onLoad();

});
