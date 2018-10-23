/*
(function($, Models, Collections, Views) {
	$(document).ready(function() {
		if(ae_globals.fre_updated_bids != 1){
			$(window).load(function(){
				$.ajax({
					url: ae_globals.ajaxURL,
					type: 'get',
					data: {
						action : 'update-data-credit'
					},
					beforeSend: function () {
					},
					success: function (res) {
						if(res.success){
							if(confirm(res.msg)){
								window.open(res.redirect, '_blank');
							}else{
								window.location.reload();
							}
						}
					}
				});
			});
		}
		if(ae_globals.fre_updated_bid_accept != 1 ){
			$(window).load(function(){
				$.ajax({
					url: ae_globals.ajaxURL,
					type: 'get',
					data: {
						action : 'update-bid-unacceptable'
					},
					beforeSend: function () {
					},
					success: function (res) {
						if(res.success){
							alert(res.msg);
							window.location.reload();
						}
					}
				});
			});
		}
	});
})(jQuery, window.AE.Models, window.AE.Collections, window.AE.Views);
*/
