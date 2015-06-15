/** * zAnimate.js * *  animation file for zstore manager basic  *  zooms images */jQuery(document).ready(function() {

var v = false;var savedParent;var myElement;var myElement1;var savedWidth;var savedLeft;
	
	jQuery('.z_listing').hover(function() {
	if (v == false){
		var pos = jQuery(this).position();
		var pos_width = jQuery(this).width();
	    var z = jQuery(this).find('.zoomBox').fadeIn(400);
		var pos_width = jQuery(this).width();				var h4; 

		var minHeight = z.css('min-height');
		z.css('width',pos_width+ pos_width/2);
		var newHeight; 
		var h =  jQuery(this).height();				var x = jQuery(this).find('.zoomedImage1');        		var w = x.width();				savedWidth = w;				var myImg = jQuery(this).find('img')		var bColor = myImg.css('background-color');		jQuery('.zoomBox').css('background-color', bColor);	 		var new_w;				var source = x.attr('src');				var rep ;
		switch (pos_width)
		{		case 55: /*tiny*/			z.css('width',3*pos_width);			h4 = pos_width*4.5;			jQuery('.zoomClassTitle').css('height', 4*16+"px");			rep = source.replace('50.jpg','250.jpg');			x.attr('src',rep);			new_w = w*2;			
		    break;
		case 97: /*small */					 h4 = pos_width *3.5;			 z.css('width',2*pos_width);			 jQuery('.zoomClassTitle').css('height', 4*16+"px");			 rep = source.replace('92.jpg','250.jpg');			 x.attr('src',rep);			 new_w = w*4;		    break;
		case 157: /*medium */			 h4 = pos_width *2.25;			 new_w = w * 1.2;		    break;		case 333: /* huge */
					 h4 =pos_width *1.5;			 new_w = w * 1.2;
			break;
		case 215:  /* large */
			h4 = pos_width *1.75;			new_w = w * 1.2;
			break;
		
		}	
		z.css('height',h4);		var p = jQuery(this).position();
		var midZlisting = pos_width/2 + p.left;
		var newLeft = midZlisting - z.width()/2;	
		p.left = newLeft;
		savedLeft = p.left;
		savedTop = p.top;
		z.css('left', p.left+'px');
		z.css('top', p.top+'px');
		x.width(new_w);
		x.css('display','inline-block');
		x = jQuery(this).find('.zoomClass');	
		x.css('display','inline-block');
		v = true;
	}
}, function() {
	if (v == true){
	jQuery(this).find('.zoomBox').stop().fadeOut(100);
	 jQuery(this).find('.zoomBox').css('left',savedLeft+'px');		var x = jQuery(this).find('.zoomedImage1');	x.width(savedWidth);	v= false;
	}
});
}); // end ready