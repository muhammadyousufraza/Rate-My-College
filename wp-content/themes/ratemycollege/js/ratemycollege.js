jQuery(function( $ ){

	$("header .genesis-nav-menu, .nav-primary .genesis-nav-menu").addClass("responsive-menu").before('<div class="responsive-menu-icon"></div>');

	$(".responsive-menu-icon").click(function(){
		$(this).next("header .genesis-nav-menu, .nav-primary .genesis-nav-menu").slideToggle();
	});

	$(window).resize(function(){
		if(window.innerWidth > 600) {
			$("header .genesis-nav-menu, .nav-primary .genesis-nav-menu, nav .sub-menu").removeAttr("style");
			$(".responsive-menu > .menu-item").removeClass("menu-open");
		}
	});

	$(".responsive-menu > .menu-item").click(function(event){
		if (event.target !== this)
		return;
			$(this).find(".sub-menu:first").slideToggle(function() {
			$(this).parent().toggleClass("menu-open");
		});
	});
	//header sticky
	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();

	    if (scroll >= 50) {
	        $(".site-container").addClass("sticky");
	    } else {
	        $(".site-container").removeClass("sticky");
	    }

	    if (scroll >= 10) {
	        $(".site-container").addClass("sticky-2");
	    } else {
	        $(".site-container").removeClass("sticky-2");
	    }
	});
	//register
	$(document).on('submit','#ratemycollege_register_form',function(e){
		e.preventDefault();
		var submit_data = {
			'action':	'ratemycollege_register',
			'first_name': 		$('#ratemycollege_register_first_name').val(),
			'last_name': 		$('#ratemycollege_register_last_name').val(),
			'email': 		$('#ratemycollege_register_email').val(),
			'password': 		$('#ratemycollege_register_password').val()
		};
		//console.log(submit_data);
		$.ajax({
			type: 'POST',
			url: ratemycollege_vars.ajax_url,
			data: submit_data,
			success: function ( response ) {
				//console.log(response.status);
				if ( 'success' == response.status ) {
					location.reload();
				} else {
					$('.ratemycollege_register_message').html(response.status).attr("style","color:red");
				}
			},
			error: function( e ) {
				
			}
		});
	});
	//rating
	$(document).on('submit','#rating-coach-form',function(e){
		e.preventDefault();
		var iq='';
		var individual = ''; 
		var personable=''; 
		var academics=''; 
		var staff='';
		var communication='';
		var temporary = [];
		$('#rate-the-coach-item-iq .dashicons-star-filled').each(function(index, element) {
           temporary.push($(this).data("index"));
        });
		iq=temporary[temporary.length - 1];
		
		var temporary = [];
		$('#rate-the-coach-item-player-individual-development .dashicons-star-filled').each(function(index, element) {
           temporary.push($(this).data("index"));
        });
		individual=temporary[temporary.length - 1];
		
		var temporary = [];
		$('#rate-the-coach-item-personable .dashicons-star-filled').each(function(index, element) {
           temporary.push($(this).data("index"));
        });
		personable=temporary[temporary.length - 1];
		
		var temporary = [];
		$('#rate-the-coach-item-academics .dashicons-star-filled').each(function(index, element) {
           temporary.push($(this).data("index"));
        });
		academics=temporary[temporary.length - 1];
		
		var temporary = [];
		$('#rate-the-coach-item-staff .dashicons-star-filled').each(function(index, element) {
           temporary.push($(this).data("index"));
        });
		staff=temporary[temporary.length - 1];
		
		var temporary = [];
		$('#rate-the-coach-item-communication .dashicons-star-filled').each(function(index, element) {
           temporary.push($(this).data("index"));
        });
		communication=temporary[temporary.length - 1];
		
		if(iq == undefined){
			alert('You have not yet rating for IQ');
			return false;
		};
		if(individual == undefined){
			alert('You have not yet rating Player/Individual Development');
			return false;
		};
		if(personable == undefined){
			alert('You have not yet rating Personable');
			return false;
		};
		if(communication == undefined){
			alert('You have not yet rating Communication');
			return false;
		};
		if(academics == undefined){
			alert('You have not yet rating for Academics ');
			return false;
		};
		if(staff == undefined){
			alert('You have not yet rating Staff');
			return false;
		};
		var review=$('#rating-coach-form-review-text').val();
		if(review == ''){
			alert('You have not yet Review');
			return false;
		};
		var answer1=$('input[name=rating-coach-form-quetion-item-answer-1]:checked').val();
		if(answer1 == undefined){
			answer1='';
		};
		var answer2=$('input[name=rating-coach-form-quetion-item-answer-2]:checked').val();
		if(answer2 == undefined){
			answer2='';
		};
		var post_id=$('.rating-coach-form-star').data('post_id');
		var user_id=$('.rating-coach-form-star').data('user_id');
		//console.log(answer2);
		var submit_data = {
			'action':	'ratemycollege_rating',
			'iq': 		iq,
			'individual': 		individual,
			'personable': 		personable,
			'academics': 		academics,
			'staff': 		staff,
			'communication': communication,
			'answer1': answer1,
			'answer2': answer2,
			'user_id': user_id,
			'post_id':post_id,
			'review_content':review
		};
		//console.log(submit_data);
		$('#rating-coach-form-submit').html('<span class="fa fa-circle-o-notch fa-spin"></span> Submit...');
		$.ajax({
			type: 'POST',
			url: ratemycollege_vars.ajax_url,
			data: submit_data,
			success: function ( response ) {
				console.log(response.status);
				if ( 'success' == response.status ) {
					alert('Thank you for your rating!');
					$('#rating-coach-form-submit').html('Submit').attr("disabled", true);
					location.reload();
				} else {
					//$('.ratemycollege_login_message').html(response.status).attr("style","color:red");
				}
			},
			error: function( e ) {
				
			}
		});
	});
	//login
	$(document).on('submit','#ratemycollege_login_form',function(e){
		e.preventDefault();
		var submit_data = {
			'action':	'ratemycollege_login',
			'username': 		$('#ratemycollege_login_username').val(),
			'password': 		$('#ratemycollege_login_password').val()
		};
		$('#ratemycollege_login_submit').html('<span class="fa fa-circle-o-notch fa-spin"></span> Login...');
		$.ajax({
			type: 'POST',
			url: ratemycollege_vars.ajax_url,
			data: submit_data,
			success: function ( response ) {
				console.log(response.status);
				if ( 'success' == response.status ) {
					location.reload();
				} else {
					$('#ratemycollege_login_submit').html('Login');
					$('.ratemycollege_login_message').html(response.status).attr("style","color:red");
				}
			},
			error: function( e ) {
				
			}
		});
	});
	//login2
	$(document).on('submit','#ratemycollege_login2_form',function(e){
		e.preventDefault();
		var submit_data = {
			'action':	'ratemycollege_login2',
			'username': 		$('#ratemycollege_login2_username').val(),
			'password': 		$('#ratemycollege_login2_password').val()
		};
		$('#ratemycollege_login2_submit').html('<span class="fa fa-circle-o-notch fa-spin"></span> Login...');
		$.ajax({
			type: 'POST',
			url: ratemycollege_vars.ajax_url,
			data: submit_data,
			success: function ( response ) {
				console.log(response.status);
				if ( 'success' == response.status ) {
					location.reload();
				} else {
					$('#ratemycollege_login2_submit').html('Login');
					$('.ratemycollege_login2_message').html(response.status).attr("style","color:red");
				}
			},
			error: function( e ) {
				
			}
		});
	});
	//hover star
	$(document).on('mouseenter','#rate-the-coach-item-iq .rating-coach-star-rating-form',function(e){
		var index=$(this).data('index');
		var star=$('#rate-the-coach-item-iq .rating-coach-star-rating-form');
		var star1=$('#rate-the-coach-item-iq .rating-coach-star-rating-form:nth-of-type(1)');
		var star2=$('#rate-the-coach-item-iq .rating-coach-star-rating-form:nth-of-type(2)');
		var star3=$('#rate-the-coach-item-iq .rating-coach-star-rating-form:nth-of-type(3)');
		var star4=$('#rate-the-coach-item-iq .rating-coach-star-rating-form:nth-of-type(4)');
		var star5=$('#rate-the-coach-item-iq .rating-coach-star-rating-form:nth-of-type(5)');
		if(index == "1"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "2"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "3"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "4"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "5"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star5.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}
	});
	$(document).on('mouseenter','#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form',function(e){
		var index=$(this).data('index');
		var star=$('#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form');
		var star1=$('#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form:nth-of-type(1)');
		var star2=$('#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form:nth-of-type(2)');
		var star3=$('#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form:nth-of-type(3)');
		var star4=$('#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form:nth-of-type(4)');
		var star5=$('#rate-the-coach-item-player-individual-development .rating-coach-star-rating-form:nth-of-type(5)');
		if(index == "1"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "2"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "3"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "4"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "5"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star5.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}
	});
	$(document).on('mouseenter','#rate-the-coach-item-personable .rating-coach-star-rating-form',function(e){
		var index=$(this).data('index');
		var star=$('#rate-the-coach-item-personable .rating-coach-star-rating-form');
		var star1=$('#rate-the-coach-item-personable .rating-coach-star-rating-form:nth-of-type(1)');
		var star2=$('#rate-the-coach-item-personable .rating-coach-star-rating-form:nth-of-type(2)');
		var star3=$('#rate-the-coach-item-personable .rating-coach-star-rating-form:nth-of-type(3)');
		var star4=$('#rate-the-coach-item-personable .rating-coach-star-rating-form:nth-of-type(4)');
		var star5=$('#rate-the-coach-item-personable .rating-coach-star-rating-form:nth-of-type(5)');
		if(index == "1"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "2"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "3"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "4"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "5"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star5.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}
	});
	$(document).on('mouseenter','#rate-the-coach-item-academics .rating-coach-star-rating-form',function(e){
		var index=$(this).data('index');
		var star=$('#rate-the-coach-item-academics .rating-coach-star-rating-form');
		var star1=$('#rate-the-coach-item-academics .rating-coach-star-rating-form:nth-of-type(1)');
		var star2=$('#rate-the-coach-item-academics .rating-coach-star-rating-form:nth-of-type(2)');
		var star3=$('#rate-the-coach-item-academics .rating-coach-star-rating-form:nth-of-type(3)');
		var star4=$('#rate-the-coach-item-academics .rating-coach-star-rating-form:nth-of-type(4)');
		var star5=$('#rate-the-coach-item-academics .rating-coach-star-rating-form:nth-of-type(5)');
		if(index == "1"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "2"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "3"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "4"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "5"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star5.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}
	});
	$(document).on('mouseenter','#rate-the-coach-item-staff .rating-coach-star-rating-form',function(e){
		var index=$(this).data('index');
		var star=$('#rate-the-coach-item-staff .rating-coach-star-rating-form');
		var star1=$('#rate-the-coach-item-staff .rating-coach-star-rating-form:nth-of-type(1)');
		var star2=$('#rate-the-coach-item-staff .rating-coach-star-rating-form:nth-of-type(2)');
		var star3=$('#rate-the-coach-item-staff .rating-coach-star-rating-form:nth-of-type(3)');
		var star4=$('#rate-the-coach-item-staff .rating-coach-star-rating-form:nth-of-type(4)');
		var star5=$('#rate-the-coach-item-staff .rating-coach-star-rating-form:nth-of-type(5)');
		if(index == "1"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "2"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "3"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "4"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "5"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star5.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}
	});
	$(document).on('mouseenter','#rate-the-coach-item-communication .rating-coach-star-rating-form',function(e){
		var index=$(this).data('index');
		var star=$('#rate-the-coach-item-communication .rating-coach-star-rating-form');
		var star1=$('#rate-the-coach-item-communication .rating-coach-star-rating-form:nth-of-type(1)');
		var star2=$('#rate-the-coach-item-communication .rating-coach-star-rating-form:nth-of-type(2)');
		var star3=$('#rate-the-coach-item-communication .rating-coach-star-rating-form:nth-of-type(3)');
		var star4=$('#rate-the-coach-item-communication .rating-coach-star-rating-form:nth-of-type(4)');
		var star5=$('#rate-the-coach-item-communication .rating-coach-star-rating-form:nth-of-type(5)');
		if(index == "1"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "2"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "3"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "4"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}else if(index == "5"){
			star.removeClass('dashicons-star-filled');
			star.removeClass('dashicons-star-half');
			star.addClass('dashicons-star-empty');
			star1.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star2.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star3.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star4.addClass('dashicons-star-filled').removeClass('dashicons-star-empty');
			star5.addClass('dashicons-star-filled').removeClass('dashicons-star-empty').addClass('selected');
		}
	});
	//show or hide form review
	$(document).on('click','#coaches-records-show-rating',function(e){
		$('.ratemycollege-form-register-login-out').toggle();
	});
	//suggest search sport
	// $("#sport_name").on("keyup", function(e) {
  //       e.preventDefault();
  //       var key = $("#sport_name").val().replace(/:|;|!|@@|#|\$|%|\^|&|\*|'|"|>|<|,|\.|\?|`|~|\+|=|_|\(|\)|{|}|\[|\]|\\|\|/gi, "");
	// 	var search_by=$('a.sport-search-by.active').data('sport_search_by');
  //       if (key.length >= 3) {
	// 		var submit_data = {
	// 		'action':	'ratemycollege_sport_suggest',
	// 		'key': key,
	// 		'search_by': search_by
	// 		};
	// 		console.log(submit_data);
	// 		$.ajax({
	// 			type: 'POST',
	// 			url: ratemycollege_vars.ajax_url,
	// 			data: submit_data,
	// 			success: function ( response ) {
	// 				console.log(response);
	// 				if ( 'success' == response.status ) {
	// 					$("#sport-search-by-suggest").html(response.html).show();
	// 				}
	// 			},
	// 			error: function( e ) {
					
	// 			}
	// 		});
  //       } else{
	// 		$("#sport-search-by-suggest").html('').hide();
	// 	}
  //   })
	//get key from click into suggest
	// $(document).on('click','#sport-search-by-suggest>li',function(e){
	// 	 e.preventDefault();
	// 	 $("#sport_name").val($(this).text());
	// 	 $("#sport-search-by-suggest").hide();
	// });
	// //suggest search coach name or school name
	// $("#coachName2").on("keyup", function(e) {
  //       e.preventDefault();
  //       var key = $("#coachName2").val().replace(/:|;|!|@@|#|\$|%|\^|&|\*|'|"|>|<|,|\.|\?|`|~|\+|=|_|\(|\)|{|}|\[|\]|\\|\|/gi, "");
	// 	var search_by=$('a.coach2-search-by.active').data('coach2_search_by');
  //       if (key.length >= 3) {
	// 		var submit_data = {
	// 		'action':	'ratemycollege_coach_school_suggest',
	// 		'key': key,
	// 		'search_by': search_by
	// 		};
	// 		console.log(submit_data);
	// 		$.ajax({
	// 			type: 'POST',
	// 			url: ratemycollege_vars.ajax_url,
	// 			data: submit_data,
	// 			success: function ( response ) {
	// 				console.log(response);
	// 				if ( 'success' == response.status ) {
	// 					$("#coach2-search-by-suggest").html(response.html).show();
	// 				}
	// 			},
	// 			error: function( e ) {
					
	// 			}
	// 		});
  //       } else{
	// 		$("#coach2-search-by-suggest").html('').hide();
	// 	}
  //   })
	//get key from click into suggest
	// $(document).on('click','#coach2-search-by-suggest>li',function(e){
	// 	 e.preventDefault();
	// 	 $("#coachName2").val($(this).text());
	// 	 $("#coach2-search-by-suggest").hide();
	// });

	// handle footer links to accordion tabs
	function handleTabHash() {
		var tab = window.location.hash.substr(1);
		if( tab.search('t-') > -1 ) {
			var index = tab.split('t-')[1];
			var tabEl = jQuery('.uabb-adv-accordion-item[data-index="'+index+'"]');
			var tabButton = tabEl.find('.uabb-adv-accordion-button');
			var tabContent = tabEl.find('.uabb-adv-accordion-content');

			// reset all other open tabs
			jQuery('.uabb-adv-accordion-button').removeClass('uabb-adv-accordion-item-active');
			jQuery('.uabb-adv-accordion-content').hide();

			tabButton.trigger('click');
			tabButton.addClass('uabb-adv-accordion-item-active');
			tabContent.show();
		}
	}

	window.addEventListener("hashchange", function() {
		console.log('changed');
		handleTabHash();
	}, false);

	// check hash on page load
	var checkExist = setInterval(function() {
		if (jQuery('.uabb-adv-accordion').length) {
			handleTabHash();
			clearInterval(checkExist);
		}
 	}, 100); // check every 100ms
	
});