(function($){

	wp.customize("site_logo", function(value) {
		value.bind(function(newval) {
			$(".fre-site-logo img").attr('src', newval);
			$(".fre-footer-logo img").attr('src', newval);
		} );
	});
	// Block Banner
	wp.customize("title_banner", function(value) {
		value.bind(function(newval) {
			$("#title_banner").html(newval);
		} );
	});
	wp.customize("background_banner", function(value) {
		value.bind(function(newval) {
			$("#background_banner").css('background-image','url(' + newval + ')');
		} );
	});
	// Block Work
	wp.customize("title_work", function(value) {
		value.bind(function(newval) {
			$("#title_work").html(newval);
		} );
	});
	wp.customize("img_work_1", function(value) {
		value.bind(function(newval) {
			$("#img_work_1").attr('src', newval);
		} );
	});
	wp.customize("desc_work_1", function(value) {
		value.bind(function(newval) {
			$("#desc_work_1").html(newval);
		} );
	});
	wp.customize("img_work_2", function(value) {
		value.bind(function(newval) {
			$("#img_work_2").attr('src', newval);
		} );
	});
	wp.customize("desc_work_2", function(value) {
		value.bind(function(newval) {
			$("#desc_work_2").html(newval);
		} );
	});
	wp.customize("img_work_3", function(value) {
		value.bind(function(newval) {
			$("#img_work_3").attr('src', newval);
		} );
	});
	wp.customize("desc_work_3", function(value) {
		value.bind(function(newval) {
			$("#desc_work_3").html(newval);
		} );
	});
	wp.customize("img_work_4", function(value) {
		value.bind(function(newval) {
			$("#img_work_4").attr('src', newval);
		} );
	});
	wp.customize("desc_work_4", function(value) {
		value.bind(function(newval) {
			$("#desc_work_4").html(newval);
		} );
	});

	// Block Freelance
	wp.customize("title_freelance", function(value) {
		value.bind(function(newval) {
			$("#title_freelance").html(newval);
		} );
	});

	// Block Project
	wp.customize("title_project", function(value) {
		value.bind(function(newval) {
			$("#title_project").html(newval);
		} );
	});

	// Block Story
	wp.customize("title_story", function(value) {
		value.bind(function(newval) {
			$("#title_story").html(newval);
		} );
	});

	// Block Service
	wp.customize("title_service", function(value) {
		value.bind(function(newval) {
			$("#title_service").html(newval);
		} );
	});
	wp.customize("title_service_freelancer", function(value) {
		value.bind(function(newval) {
			$("#title_service").html(newval);
		} );
	});
	
	// Block Get Start
	wp.customize("title_start", function(value) {
		value.bind(function(newval) {
			$("#title_start").html(newval);
		} );
	});
	wp.customize("title_start_freelancer", function(value) {
		value.bind(function(newval) {
			$("#title_start").html(newval);
		} );
	});
	wp.customize("title_start_employer", function(value) {
		value.bind(function(newval) {
			$("#title_start").html(newval);
		} );
	});
})(jQuery);