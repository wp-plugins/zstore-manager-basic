jQuery(document).ready(function() {


	set_the_height();
	
function set_the_height()
{

	var object_width = jQuery('.z_listing').width();
    var theText = jQuery('.z_productDescription:eq( 2 )').text();
	switch (object_width)

		{
		case 55: /* tiny*/
	
			jQuery('.z_productTitle').css('height',16*10+"px");
			if (theText.length < 100){
			
				jQuery('.z_productDescription').css('height',16*10+"px");
				
			}else 
				jQuery('.z_productDescription').css('height',16*20+"px");
		
		break;
		
		case 97: /*small */
				jQuery('.z_productTitle').css('height',16*8+"px");
				if (theText.length < 100){
			
				jQuery('.z_productDescription').css('height',16*6+"px");
				
			}else 
				jQuery('.z_productDescription').css('height',16*16+"px");
		break;

		case 157: /*medium */
			jQuery('.z_productTitle').css('height',16*4+"px");
			if (theText.length < 100){
			
				jQuery('.z_productDescription').css('height',16*4+"px");
				
			}else 
				jQuery('.z_productDescription').css('height',16*10+"px");
			
		break;
		case 215:  /* large */
			jQuery('.z_productTitle').css('height',16*3+"px");
			
			if (theText.length < 100){
			
				jQuery('.z_productDescription').css('height',16*3+"px");
				
			}else 
				jQuery('.z_productDescription').css('height',16*8+"px");
		break;
		case 333: /* huge */
			jQuery('.z_productTitle').css('height',16*2+"px");
		break;

		}
}

}); // end ready