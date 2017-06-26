var $ = jQuery.noConflict();

function explode(){  
    
$(".variation-Color:first-child").text("Farbe:"); 
$(".festi-user-role-prices-regular-price-lable").text("UVP:");    
$(".festi-user-role-prices-user-price-lable").text("Dein Preis:");    
$(".festi-user-role-prices-discount-lable").text("Rabatt:");
var freeShip = "<tr class='free-ship'><td colspan='2'><span><i class='fa fa-truck' aria-hidden='true'></i></span> Der Versand ist fur dich kostenlos.</td></tr>";    
$("tr.shipping").after(freeShip);    
    
///////////////    adds preloader to thumbnails    ///////////////
$('.MagicToolboxSelectorsContainer').append('<a href="#" class="video-link"><img src="/images/video-plaseholder.jpg" alt="play video"></a>');
$('.psa_overflow_images').addClass('psa-hide');
setTimeout(function(){
    $('.psa_overflow_images').hide();
}, 500);  

$('img.tmlazy.shadow').click(function(){
    setTimeout(function(){
	$('.MagicToolboxSelectorsContainer').append('<a href="#" class="video-link"><img src="/images/video-plaseholder.jpg" alt="play video"></a>');
    }, 2000);
});
    
$('body').on('mouseover', 'a.video-link', function(){
	$('.MagicToolboxMainContainer iframe').remove();
	$('.video-text-psa').remove();
	cover = $('.ps-inner-field').siblings('h2').text();	
	if(cover.indexOf('booncover XS') > 0 ){		// если это booncover XS или booncover XS2	
		$('.MagicToolboxMainContainer').append('<iframe width="100%" height="450" src="https://www.youtube.com/embed/CDJESM-MTEk?disablekb=1&amp;rel=0&amp;showinfo=0" frameborder="0" allowfullscreen=""></iframe><p class="video-text-psa">Zur Video-Wiedergabe klicken</p>');
	}else if(cover.indexOf('boonflip') > 0){	// если это boonflip
		$('.MagicToolboxMainContainer').append('<iframe width="100%" height="450" src="https://www.youtube.com/embed/xM95h4foXO4?disablekb=1&amp;rel=0&amp;showinfo=0" frameborder="0" allowfullscreen=""></iframe><p class="video-text-psa">Zur Video-Wiedergabe klicken</p>');
	}else if(cover.indexOf('booncover S3') > 0){		// если это booncover S3
		$('.MagicToolboxMainContainer').append('<iframe width="100%" height="450" src="https://www.youtube.com/embed/6rYT_nEqjSM?disablekb=1&amp;rel=0&amp;showinfo=0" frameborder="0" allowfullscreen=""></iframe><p class="video-text-psa">Zur Video-Wiedergabe klicken</p>');
	}else{										// для остальных booncover
		$('.MagicToolboxMainContainer').append('<iframe width="100%" height="450" src="https://www.youtube.com/embed/05AwwIIavD8?disablekb=1&amp;rel=0&amp;showinfo=0" frameborder="0" allowfullscreen=""></iframe><p class="video-text-psa">Zur Video-Wiedergabe klicken</p>');
	}

    $('.magic-slide').hide();
});
    
    
$('.MagicToolboxSelectorsContainer').on('mouseover click', 'a', function(){
    $('.magic-slide').show();
	$('.MagicToolboxMainContainer iframe').remove();
	$('.video-text-psa').remove();
}); 
    
}

$(document).ready(function(){
     
    $('<div class="psa_overflow_images"><div><span class="gps_ring"></span><span>Bitte warten...</span></div></div>').insertBefore('.images');
   
    ///////////////    adds video into image thumbnails    ///////////////

    var lastImage = $('.MagicToolboxSelectorsContainer').find('a:last-child');
    
    setTimeout(explode, 4000);  

    
    $('.icon-benefits').click(function(){ 
        var target = $('.ult-banner-block');
        var objPos = $(target).offset().top-110;
        $('html, body').animate({scrollTop: objPos}, 2000);
        return false;  
    });
    
});

///////////////    Tags cloud in menu    ///////////////

$(document).ready(function(){
    $("#nav-menu-item-81632").append($(".tags-cloud"));
});

///////////////    Product menu    ///////////////

// smooth scroll on links
$(function(){
   $('.psa-header-newmenu li a[href^="#"], .ult_crlink a[href^="#"]').click(function(){
       if ($(this).attr("href") == "#video") {
        var target = $(this).attr('href');
        var objPos = $(target).offset().top-320;
        $('html, body').animate({scrollTop: objPos}, 2000);
        return false;   
       }
       else {
        var target = $(this).attr('href');
        var objPos = $(target).offset().top-70;
        $('html, body').animate({scrollTop: objPos}, 2000);
        return false; 
       }
   }); 
});

// Navigation and animate bar        
$(document).ready(function(){
    $('.psa-header-newmenu').append('<div class="bar"></div>');
    $(document).on("scroll", onScroll);
});

function onScroll(event){
    
    var scrollPos = $(document).scrollTop();

    $('.psa-header-newmenu li a').each(function () {

        var currLink = $(this);
        var position = currLink.position();
        var currLinkLi = $(this).closest("li");
        var bar = $(".psa-header-newmenu").find('div.bar');

        var refElement = $(currLink.attr("href"));
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {

            bar.css({
                width: currLinkLi.width(),
                left:  position.left
            });         


            $('.psa-header-newmenu li a').removeClass("active");
            currLink.addClass("active");

        }
        else{
            currLink.removeClass("active");
        }

        $(currLinkLi).mouseover(function() {
            bar.css({
                width: currLinkLi.width(),
                left:  position.left
            }); 

        });
    });

}

// Adds sticky class to menu
var orgElementPos = $('#psa-top-menu').offset();
if ($('#psa-top-menu').length) { 

    orgElementTop = orgElementPos.top;  

    $(window).scroll(function(){
        if($(window).scrollTop()>=orgElementTop){
            $("#psa-top-menu").addClass('sticky').removeClass("unsticky");
            $("#JETZT_KAUFEN").addClass('sticky').removeClass("unsticky");
        }
        else {
            $("#psa-top-menu").addClass('unsticky').removeClass("sticky");
            $("#JETZT_KAUFEN").addClass('unsticky').removeClass("sticky");
        }
    });

}

///////////////    Some hacks    ///////////////

// Image margin if flip

$(document).ready(function(){
    
    if($('.images').height() > 550) {
        $('.images').find('img').addClass('ps-first-load');    
    }
    
    $(".tmcp-field-wrap").each(function() {
        $(".tmcp-field-wrap").click(function() {
           $('.images').find('img').removeClass('ps-first-load');    
//            setTimeout(function(){
//                $('.thumbnails').css('visibility', 'visible');
//            }, 1200);
        });
    });
    
    $('.reset_variations').click(function() {
            imgPs = $('.images').find('img');
            
            setTimeout(function(){ 
                if (imgPs.height() > 550) imgPs.addClass('ps-first-load');
            }, 500);
        
          // $('.thumbnails').css('visibility', 'hidden');
           
    });
    
//******** is mobile events ********//
    
var is_mobile = false;

if( $('.main-menu').css('display')=='none') {
    is_mobile = true;       
}

if (is_mobile) {
    // alert("it's mobile");
    var psa_images = $('#JETZT_KAUFEN').find('.images');

    $('.tmcp-field-wrap').click(function() {
        $("html, body").animate({ scrollTop: 450 }, 1000);
    });

    $(psa_images).insertAfter( $('.psa-move-top') );
    $('.psa-move-top2').insertAfter( $('.psa-move-top') );

    // menu remake in mobile phones
    $('<span class="menu-on"></span>').insertBefore($('.psa-header-newmenu'));
    $('.psa-header-newmenu').hide();
    
    // rotating arrow
    $('.menu-on').click(function(){
    if ($(this).css("transform") == 'none'){
        $(this).css("transform", "rotate(180deg)");
    } else {
        $(this).css("transform", "");
    }
        $('.psa-header-newmenu').slideToggle();    
    });
    $('.psa-header-newmenu li a').click(function(){
       $('.psa-header-newmenu').hide();
       $('.menu-on').css("transform", "rotate(-180deg)")
    })
}
else {
    // alert("it's desktop");
}
});
 
//******** other moments ********//    
    
$(document).ready(function(){
    
// cart onclick
$("#mini-cart").attr("onclick", "window.location.href = '/cart';");      
    
$('#accordion-menu-item-7241 a').html('<img class="sidebar-menu-logo" src="https://www.reboon.de/wp-content/uploads/2017/02/reboon-boon-logo-black2.png" />');

$(".variation-Color:first-child").text("Farbe:"); 

$('ul.faq-filter li[data-filter="*"] a').text('Alles anzeigen');
    
$("div[itemprop='offers'] .price").after("<p class='price-info'>inkl. MwSt 19%.<br>Versand nach Deutschland ist kostenlos.<br>(<a href='https://www.reboon.de/shipping-methods/'>Versandkosten</a>)</p>");

$(".woocommerce-billing-fields").append($("#order_comments_field"));    
    
// button text
$("a.button.checkout.wc-forward").html("Zur Kasse gehen <i class='Defaults-angle-right' style='font-size:14px;'></i>"); 
// next line 
$(".wc-gzd-order-submit").after("<div class='clear'></div>"); 
// checkout modify
var checkoutRow2 = $("div#customer_details div.col-md-6:last-child"),
    checkoutCart = $("#customer_details .col-md-6 .featured-box");
$(checkoutRow2).addClass("checkoutRow2").insertAfter(checkoutCart, 1000);    
});

///////////////    Parallax effects    ///////////////

$(document).ready(function(){
        var bgobj = $('.psa-parallax-1 img');
        $(window).scroll(function() {
            var yPos = -($(window).scrollTop() / 85); 
            // background-position
            var coords = yPos + 'px';
            // effect Parallax Scrolling
            bgobj.css({ top: coords });
        });        
});
              
$(document).ready(function(){

        var bgobj = $('.bncvr-prlx-2 img'); 
        $(window).scroll(function() {
            var yPos = -($(window).scrollTop() / 70); 
            // background-position
            var coords = yPos + 'px';
            // effect Parallax Scrolling
            bgobj.css({ top: coords });
        });
});
        
$(document).ready(function(){

        var bgobj = $('.bncvr-prlx-3 img'); 
        $(window).scroll(function() {
            var yPos = -($(window).scrollTop() / 85);
            // background-position
            var coords = yPos + 'px';
            // effect Parallax Scrolling
            bgobj.css({ top: coords });
        });
		
		msg_success = $('p.message-success');		
		
		/* TRD */
		// сохранение iframe в буфер и вывод сообщения		
		$('button.copy-butt').on('click', function(){			
			var emailLink = document.querySelector('div.iframe-area > p > textarea');  
			var range = document.createRange();  
			range.selectNode(emailLink);  
			window.getSelection().addRange(range);  

			try {  
				// Теперь, когда мы выбрали текст ссылки, выполним команду копирования
				var successful = document.execCommand('copy');				
				if(successful) {					
					msg_success.fadeIn(1000);
					setTimeout(function(){msg_success.fadeOut(1000)}, 5000);
				} // if			  
			} catch(err) {  
				console.log('Oops, unable to copy');  
			}  

			// Снятие выделения
			window.getSelection().removeAllRanges();  
		})
		/* TRD */
         
});

/* TRD */
function trd_save_links(user_id){
    var arrList = $('.trd_link').map(function(){
        return $(this).attr('value');
    }).get();

    $.ajax({
        type: "POST",
        url: TRDJS.ajax_url,
        data: {
            action: 'trd_ecommerce_tools',
            mapLinks: arrList
        },  // data
        success: function(res){
            alert("Links aktualisiert!");
        } // success
    }); // ajax
} // trd_save_links
/* TRD */




$(document).ready(function(){

  // Get the .gif images from the "data-alt".
	var getGif = function() {
		var gif = [];
		$('img').each(function() {
			var data = $(this).data('alt');
			gif.push(data);
		});
		return gif;
	}

	var gif = getGif();



	// Change the image to .gif when clicked and vice versa.
	$('figure').on('click', function() {
        	// Preload all the gif images.
	var image = [];

	$.each(gif, function(index) {
		image[index]     = new Image();
		image[index].src = gif[index];
	});
		var $this   = $(this),
				$index  = $this.index(),
				
				$img    = $this.children('img'),
				$imgSrc = $img.attr('src'),
				$imgAlt = $img.attr('data-alt'),
				$imgExt = $imgAlt.split('.');
				
		if($imgExt[1] === 'gif') {
			$img.attr('src', $img.data('alt')).attr('data-alt', $imgSrc);
		} else {
			$img.attr('src', $imgAlt).attr('data-alt', $img.data('alt'));
		}

		// Add play class to help with the styling.
		$this.toggleClass('play');

	});

});