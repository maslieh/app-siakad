jQuery(function($){
	$.easing.backout = function(x, t, b, c, d){
		var s=1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	};	
	$('#screen').scrollShow({
		view:'#view',
		content:'#images',
		easing:'backout',
		wrappers:'crop',
		navigators:'a[id]',
		navigationMode:'sr',
		circular:true,
		start:0
	});
 });
 
 // fungsi init tooltip pada slider product; modified by ajay
 function initTooltip() {
	$("#images li").each(function() {
		var tooltip_id = $(this).children(":first");
		tooltip_id.tooltip({position: ['bottom', 'center'],  opacity: 0.95});
	});
 }

 
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;

var temp_input = "";
var current_class = "";

var temp_input_down = "";
var ddlistdown = 0;
var timeout_down = 500;
var closetimer_down = 0;

// fungsi dropdown menu
function jsddm_open() {
	jsddm_canceltimer();
	jsddm_close();
	ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');
}
function jsddm_close() {
	if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');
}
function jsddm_timer() {
	closetimer = window.setTimeout(jsddm_close, timeout);
}
function jsddm_canceltimer() {
	if(closetimer) {
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

//fungsi input fields customation; create by ajay
function fill() {
	temp_input = $(this).attr('value');
	$(this).attr('value', '');
	$(this).addClass("onFocus");
}
function lose() {
	var a = $(this).attr('value');
	if(a == "" || a == " ") {
		$(this).attr('value', temp_input);
	}
	$(this).removeClass("onFocus");
}

//fungsi show downloaded files; create by ajay
function donlot_list() {
	if(closetimer_down) {
		window.clearTimeout(closetimer_down);
		closetimer_down = null;
	}
	donlot_close();
	if(temp_input_down == "") {
		temp_input_down = $(this).attr('value');
	}
	$(this).addClass("onFocus");
	$(this).attr('readOnly', true);
	$(this).attr('disabled', true);
	$(this).attr('value', temp_input_down)
	ddlistdown = $('#download_list').css('visibility', 'visible');
}
function donlot_close() {
	if(ddlistdown) {
		$('#download_input').removeAttr('readOnly');
		$('#download_input').removeAttr('disabled');
		$('#download_input').removeClass("onFocus");
		$('#download_list').css('visibility', 'hidden');
	}
}
function donlot_timer() {
	closetimer_down = window.setTimeout(donlot_close, timeout_down);
}

// accordian for product category; modified by ajay
function initMenu() {
	$('#product_category ul').hide();
	$('#product_category li a').click(
		function() {
			var checkElement = $(this).next();
			if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				checkElement.parent().removeClass("isDown");
				checkElement.slideToggle('normal');
				isdownall = 0;
				isupall = 0;
			}
			if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				checkElement.parent().addClass("isDown");
				checkElement.slideToggle('normal');
				isdownall = 0;
				isupall = 0;
			}
			//return false;
		}
	);
}
var isdownall = 0;
var isupall = 1;
function open_all_cat() {
	if(isdownall == 0) {
		var checkElement = $('#product_category ul');
		if (checkElement.hide()) {
			checkElement.parent().addClass("isDown");
			checkElement.slideDown('normal');
			isdownall = 1;
			isupall = 0;
			//return false;
		}
	} else {
		//return false;
	}
}
function close_all_cat() {
	if(isupall == 0) {
		var checkElement = $('#product_category ul');
		if (checkElement.show()) {
			checkElement.parent().removeClass("isDown");
			checkElement.slideUp('normal');
			isdownall = 0;
			isupall = 1;
			//return false;
		}
	} else {
		//return false;
	}
}

// dom ready function
$(document).ready(function() {
	$('#jsddm > li').bind('mouseover', jsddm_open);
	$('#jsddm > li').bind('mouseout',  jsddm_timer);
	
	$('#download_input').bind('click', donlot_list);
	$('#download_list').bind('mouseleave ', donlot_timer);
	$('#download_list').bind('click', donlot_close);
	
	$('#search_input').focus(fill);
	$('#search_input').blur(lose);
	$('#login_user').focus(fill);
	$('#login_user').blur(lose);
	$('#login_pass').focus(fill);
	$('#login_pass').blur(lose);
	$('#subscribe_input').focus(fill);
	$('#subscribe_input').blur(lose);
	
	$(".latest_img").fadeTo("slow", 0.3);
	$(".latest_img").hover(function(){
		$(this).fadeTo("slow", 1.0);
	},function(){
		$(this).fadeTo("slow", 0.3);
	});
	
	$('#cat_open').bind('click', open_all_cat);
	$('#cat_close').bind('click', close_all_cat);
	initTooltip();
	initMenu();
	
	$('#slider').s3Slider({
            timeOut: 5000
    });
	
});

// nutup submenu ketika sembarang klik di window
document.onclick = jsddm_close;