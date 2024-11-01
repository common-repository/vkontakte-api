/**
 * Setting scripts
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 20.05.2018, Webcraftic
 * @version 1.0
 */


(function($) {
	'use strict';

	$(document).ready(function() {
		var vkAccessTolenEl = $("#vkapi_at");

		vkAccessTolenEl.on("change", function() {
			var self = $(this);

			if( self.val().substring(0, 4) == "http" ) {

				var search = vkAccessTolenEl.val().split("#")[1];
				var parts = search.split("&");
				var $_GET = {};
				for( var i = 0; i < parts.length; i++ ) {
					var temp = parts[i].split("=");
					$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
				}

				vkAccessTolenEl.val($_GET["access_token"]);
			}

			if( self.val() == '' ) {
				return false;
			}

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'wbcr_vkapi_valid_access_token',
					access_token: self.val(),
					_wpnonce: $('#_wpnonce').val()
				},
				success: function(data) {

					$("label[for=vkapi_at]").find('.wbcr-vkapi-valid-token-label').remove();

					if( !data || data.error ) {
						console.log(data);

						$("label[for=vkapi_at]").append('<span class="wbcr-vkapi-valid-token-label wbcr-error">' + data.error + '</span>');
						return;
					}

					$("label[for=vkapi_at]").append('<span class="wbcr-vkapi-valid-token-label wbcr-success">Valid</span>');
				}
			});

			return false;
		}).triggerHandler("change");

		var vkGroupIdEl = $("#vkapi_vk_group");
		vkGroupIdEl.on("change", function() {
			var self = this;
			if( isNaN(vkGroupIdEl.val()) ) {
				var parts = vkGroupIdEl.val().split("/");
				var screen_name = parts[parts.length - 1];

				$("label[for=vkapi_vk_group]").find('.wbcr-vkapi-valid-token-label').remove();

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'wbcr_vkapi_valid_owner_id',
						screen_name: screen_name,
						access_token: $("#vkapi_at").val(),
						_wpnonce: $('#_wpnonce').val()
					},
					success: function(data) {
						if( data.error ) {
							$("label[for=vkapi_vk_group]").append('<span class="wbcr-vkapi-valid-token-label wbcr-error">' + data.error + '</span>');
							return;
						}

						if( data.type === "user" ) {
							vkGroupIdEl.val(data.object_id);
						} else if( data.type === "group" ) {
							vkGroupIdEl.val(-data.object_id);
						} else {
							alert("wrong type: " + data.type);
							$("label[for=vkapi_vk_group]").append('<span class="wbcr-vkapi-valid-token-label wbcr-error">wrong type: ' + data.type + '</span>');
							return;
						}

						$("label[for=vkapi_vk_group]").append('<span class="wbcr-vkapi-valid-token-label wbcr-success">Valid</span>');
					}
				});
			}
		}).triggerHandler("change");
	});

})(jQuery);
