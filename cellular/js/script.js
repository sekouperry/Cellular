/// Initialize fn after jQuery
jQuery(function($){
// Extend jQuery
$.fn.yoScript = function(opts){
    // Set default settings
    var settings = {
        'yoVar1': 0,
        'yoVar2': 1
    };
    // Combine opts with settings.
    return this.each(function(){	
        if (opts) { 
            $.extend(settings, opts);
        //console.log(settings);
        }
		
        // Write yoScript
		// 
		// 
		// 
		// 
		// 

    });// End .each
}// End yoScript

// Call yoScript
$('#content').yoScript({
    'yoVar1': 1
});


});
// fin