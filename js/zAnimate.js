jQuery(document).ready(function() {

var v = false;
var savedParent;
var myElement;
var myElement1;
var savedWidth;
var savedHeight;
var savedLeft;


jQuery('.z_listing').hover(function() {
			
	if (v == false){
    
			
			var pos = jQuery(this).position();
		var pos_width = jQuery(this).width();
	
	    var z = jQuery(this).find('.zoomBox').fadeIn(400);
		var pos_width = jQuery(this).width();
		
		
		
		
		
		
		
		
		var minHeight = z.css('min-height');
		 
	
		z.css('width',pos_width+ pos_width/2);
		var newHeight; 
		
		var h =  jQuery(this).height();
		var h3;
		switch (pos_width)
		{
		case 55: /*tiny*/
			h3 = 0;
			z.css('width',2*pos_width);
		    break;
		case 97: /*small */
			 h3 = jQuery(this).height()/6;
			
			break;
		case 157: /*medium */
			h3 = jQuery(this).height()/4;
			break;
		case 215:  /* large */
			h3 = jQuery(this).height()/2;
		
			break;
		case 333: /* huge */
			h3 = jQuery(this).height()/3;
			break;
		}	
		
		var h4 = h + h3;
		
		z.css('height',h4);
	
	
		var p = jQuery(this).position();
		
		var midZlisting = pos_width/2 + p.left;
		
		
		var newLeft = midZlisting - z.width()/2;
		
		
		p.left = newLeft;
		savedLeft = p.left;
		savedTop = p.top;
		z.css('left', p.left+'px');
		z.css('top', p.top+'px');
		var x = jQuery(this).find('.zoomedImage1');
		var w = x.width();
		savedWidth = w;
		var myImg = jQuery(this).find('img')
		var bColor = myImg.css('background-color');
		jQuery('.zoomBox').css('background-color', bColor);
		
		var new_w = w * 1.2;
		
		
		
		x.width(new_w);
		x.height(new_w);
		x.css('display','inline-block');
		
			
		x = jQuery(this).find('.zoomClass');	
			
		x.css('display','inline-block');

			
			
			
			
			
		    v = true;
	}
}, function() {
	if (v == true){
	
		 jQuery(this).find('.zoomBox').stop().fadeOut(100);
	 jQuery(this).find('.zoomBox').css('left',savedLeft+'px');	
	 
	var x = jQuery(this).find('.zoomedImage1');
		x.width(savedWidth);
		x.height(savedWidth);
		v= false;
	}
});


}); // end ready