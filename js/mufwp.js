/**
 * JS
 *
 * @author ilGhera
 * @package mailup-for-wp/js
 * @since 0.9.0
 */

var mufwpController = function() {

	var self = this;

	self.onLoad = function() {

		self.anchor();
		self.subscribe_button();
		self.access_form();
		self.subscribe_form();

	}


	self.subscribe_button = function() {

		jQuery(function($) {
			
			$('.mufwp-add-button').on('click', function() {
				
				var list          = $('.mufwp-add-button').data('list');
				var group         = $('.mufwp-add-button').data('group');
				var response_text = $('.mufwp-add-button').data('response-text');
				var redirect      = $('.mufwp-add-button').data('redirect');
				var product_id    = $('.mufwp-add-button').data('product-id');

				var data = {
					'action': 'mufwp-add-to',
					'mufwp-subscribe-noce': mufwpSettings.nonce,
					'username': mufwpSettings.userName,
					'mail': mufwpSettings.email,
					'list': list,
					'group': group,
					'response-text': response_text,
					'redirect': redirect,
					'product_id': product_id
				};

				$.post( mufwpSettings.ajaxURL, data, function(response) {
					
					$('.mufwp-add-button').html(response);
				
				})

			})

		})

	}


	self.anchor = function() {

		jQuery(function($){
			
			$('.mufwp-add-button-ancor').on('click', function(){
		
				$('html, body').animate({
		
					scrollTop: $('.mufwp-before-text').offset().top - 150
		
				}, 'slow');

				if($(this).hasClass('logged')) {
		
					$('.mufwp-add-button').trigger('click');
		
				}					
		
			})

		})

	}


	self.access_form = function() {

		jQuery(function($){

			$('.mufwp-register').on('click', function(){

				$(this).addClass('active');
				$('.mufwp-login').removeClass('active');
				$('.mufwp-access .wordpress .col-1').hide();
				$('.mufwp-access .wordpress .col-2').show();

			})

			$('.mufwp-login').on('click', function() {

				$(this).addClass('active');
				$('.mufwp-register').removeClass('active');
				$('.mufwp-access .wordpress .col-2').hide();
				$('.mufwp-access .wordpress .col-1').show();      

			})


		})

	}


	self.subscribe_form = function() {

		jQuery(function($) {
			
			$('.newsletter-form-button').on('click', function(e) {

				e.preventDefault();
				
				var nonce         = $('#mufwp-guest-form-nonce').val();
				var name          = $('#mufwp-name').val();
				var mail          = $('#mufwp-mail').val();
				var list          = $('#mufwp-list').val();
				var group         = $('#mufwp-group').val();
				var response_text = $('#response-text').val();
				var redirect      = $('#mufwp-redirect').val();
				// var product_id    = $('.mufwp-product-id').val();

				var data = {
					'action': 'mufwp-form',
					'mufwp-guest-form-nonce': nonce,
					'name': name,
					'mail': mail,
					'list': list,
					'group': group,
					'response-text': response_text,
					'redirect': redirect
					// 'product_id': product_id
				};

				$.post( mufwpSettings.ajaxURL, data, function(response) {
					
					$('#mufwp-subscription-form').html(response);
				
				})

			})

		})

	}
}

/**
 * Class starter with onLoad method
 */
jQuery(document).ready(function($) {
	
	var Controller = new mufwpController;
	Controller.onLoad();

});
