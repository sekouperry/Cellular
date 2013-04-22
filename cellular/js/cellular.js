/*****
	cellular.js
	
Utility functions for Drupal :: Cellular

    (c)2012 Adam Blankenship
*****/

jQuery(function($){

(function cellular(){
// Namespace for theme functions

jQuery.fn.activate = function(opts){
	var $t = $(this);
    var settings = {
        "class" : "active"
    };
    return $t.each(function(){	
    // Combine opts with settings.
        if (opts) { 
            $.extend(settings, opts);
        }
        // Write yoScript here.
		$t.toggleClass(settings.class);
	});
}

function activator(el){
  $(el, this).toggleClass('active');
  //console.log(el.attr('class') + " :: " + el.attr('href'));
}


(function labelize(){
	var input = $('input[type="text"], input[type="email"], input[type="password"], textarea');
	
	input.each(function(){
		var $t = $(this);
		var $v = $t.val();
		
		$t.bind('focus', function(){
			if($t.val() === $v){
				$t.val("");
			}
		}).bind('blur', function(){
			if($t.val() === ""){
				$t.val($v);
			}
		});
	});
})();

(function loginForm(){
	var login = $('#block-user-login');
	var form = $('form', login);
	// Hide Form
	form.hide();
	// Show Form
	$('h2', login).click(function(){
		form.slideToggle();
	});
})();

function blocklink(){
	var $t = $(this);
	var $l = $('a', $t).attr('href');
	var $w = '<a href="' + $l + '" />';
	
	$t.parent().wrap($w)
		.hover(function(){
				activator($t);
			}, function(){
				activator($t);
			});
};

$('.block-link, .views-slideshow-cycle-main-frame').each(blocklink);

//End cellular
})();


// fin
});