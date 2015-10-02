jQuery(document).ready(function($) {

	function setCookie(cname,cvalue,exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires=" + d.toGMTString();
	    document.cookie = cname+"="+cvalue+"; "+expires+";path=/";
	}

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	function checkCookie() {
	    var cookie=getCookie("rose-alert-bar");
	    if (cookie != "") {
	    	// Don't show alert bar.
	        $('.alert-bar').hide();
	    	$('.mini-alert').show();
	    } else {
	    	// Show alert bar.
	    	$('.alert-bar').slideDown();
	    	$('.mini-alert').slideUp();
	    	if (cookie != "" && cookie != null) {} 
	    }
	}

	checkCookie();

	$(".alert-bar .fa-times").click(function () {
		// Only set the cookie if user clicks close.
		setCookie("rose-alert-bar", "closed", 1); 
		$('.alert-bar').slideUp();
	    $('.mini-alert').slideDown(); 
	    return;
	});

	$(".mini-alert").click(function () {
		// Expire the cookie if they click open.
		document.cookie = "rose-alert-bar=; expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/";
	    $('.alert-bar').slideDown();
	    $('.mini-alert').slideUp();
	    return;
	});

});

