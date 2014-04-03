jQuery(document).ready(function() {
	// Begin effect for active slideshows
	beefIt(jQuery('.lumne-slideshow .active').parent().attr('id'));
	
	/*jQuery('.dot').click(function(){
		var gallery = jQuery(this).parent().attr('id').substring(4);
		var id = jQuery(this).attr('id').substring(3);
		showImage(gallery, id);
	});*/

	/*jQuery('.thumb').click(function(){
		var gallery = jQuery(this).parent().attr('id').substring(6);
		var id = jQuery(this).attr('id').substring(5);
		showImage(gallery, id);
		active = false;
	});*/

	//resize();

	jQuery(window).bind('resize', function(){
		resize();
	});

	jQuery('.lumne-slideshow .active .lumne_image').imagesLoaded(function(){
		resize();
	});

});

var active = true;

// Show single image
function showImage(gallery, id){
	/*jQuery('#gallery'+gallery).clearQueue();
	jQuery('#gallery'+gallery).stop();*/
	active = false;
	var gallery = typeof gallery !== 'undefined' ? gallery : 0;
	var id      = typeof id      !== 'undefined' ? id      : 0;
	jQuery('#gallery'+gallery+' .lumne_image').not('#image'+gallery+'_'+id)
		.attr('z-index', '2');
	jQuery('#image'+gallery+'_'+id).attr('z-index','3')
			.effect('slide', 500,
				jQuery('#gallery'+gallery+' .lumne_image')
					.not('#image'+gallery+'_'+id).hide());
}

// Hide all images
function hideAll(gallery){
	var gallery = typeof gallery !== 'undefined' ? gallery : 0;
	jQuery('#gallery'+gallery+' .lumne_image').hide();
}

// Start effect (for active galleries)
function beefIt(gallery){
	var id = typeof gallery !== 'undefined' ? gallery : 0;
	var gallery = jQuery('#gallery'+id);
	var images  = gallery.children('.top-show').children('.lumne_image');
	var count   = images.length;
	var speed   = gallery.data('trans');
	var wait    = gallery.data('pause');
	//alert('Count: '+count+':'+speed+':'+wait);
	images.not('#image'+id+'_0').hide(); // Show first image

	var pos = 0;
	jQuery('#image'+id+'_'+pos).attr('z-index', 1);
	images.not('#image'+id+'_'+pos).attr('z-index', 2);
	highlightThumb(id, pos);
	//highlightDot(id, pos);
	nextImage(id, ++pos, count, wait, speed, 'slide');

	//resize();
}

function loadImage(){
	jQuery('.lumne-slideshow .active .lumne_image').load(function(){

	}).each(function(){
		if(this.complete) jQuery(this).load();
	});
}
/*function nextImage(id, pos, length, wait, speed){
	//jQuery('#image'+id+'_'+((pos+1)%length)).attr('z-index', 3);
	jQuery('#image'+id+'_'+((pos+1)%length)).delay(wait).effect('slide', speed, function(){
		//jQuery('#gallery'+id+' .lumne_image').not('#image'+id+'_'+(pos%length)).attr('z-index', 2);
		jQuery('#image'+id+'_'+(pos%length)).hide(0, function(){
			jQuery('#image'+id+'_'+(pos%length)).attr('z-index', 3);
			jQuery('#image'+id+'_'+((pos+1)%length)).attr('z-index', 2);
			nextImage(id, ++pos, length, wait, speed);
		});
	});
}*/

function nextImage(id, pos, length, wait, speed, effect){
	var effect = typeof effect !== 'undefined' ? effect : 'slide';
	if(active){
		//jQuery('#image'+id+'_'+(pos%length)).attr('z-index', 2);
		//jQuery('#image'+id+'_'+((pos)%length)).attr('z-index', 3);
		jQuery('#gallery'+id+' .thumbs').delay(wait).scrollTo('#thumb'+(pos%length), speed, {offset:{top:-4}, margin:false, onAfter: highlightThumb(id, ((pos-1)%length), 'aqua')});
		jQuery('#image'+id+'_'+(pos%length)).delay(wait).effect(effect, speed, function(){
			jQuery('#gallery'+id+' .lumne_image').attr('z-index', 1);
			jQuery('#image'+id+'_'+((pos+1)%length)).attr('z-index', 3);
			jQuery('#image'+id+'_'+((pos-1)%length)).hide();
			//highlightImage(id, (pos%length), 'aqua');
			nextImage(id, ++pos, length, wait, speed, effect);
		});
	}
}

function highlightThumb(id, pos){
	/*var color = typeof color !== 'undefined' ? color : 'red';
	jQuery('#gallery'+id+' .thumb').not('#thumb'+pos).css('border', 'none');
	jQuery('#gallery'+id+' #thumb'+pos).css('borderBottom', '4px solid '+color);*/
	jQuery('#gallery'+id+' .thumb').not('#thumb'+pos).removeClass('active');
	jQuery('#gallery'+id+' #thumb'+pos).addClass('active');
	highlightDot(id, pos);

}

function highlightDot(id, pos){
	jQuery('#gallery'+id+' .dot').not('#dot'+pos).removeClass('active');
	jQuery('#gallery'+id+' #dot'+pos).addClass('active');
}

function resize(){
	jQuery('.top-show').css('height', setTopShowHeight());

	jQuery('.thumbs').css('height', getTopShowHeight());

	jQuery('.thumb').css('height', getThumbHeight());

	jQuery('.thumb').css('marginTop', getThumbsLeftSpacing());
}

function setTopShowHeight(){
	return jQuery('.lumne_image').height();
}

function getTopShowHeight(){
	return jQuery('.top-show').height();
}

function getBeforeThumbsWidth(){
	return jQuery('.thumbs:before').width();
}

function getThumbsLeftSpacing(){
	var thumbs = jQuery('.thumbs').width();
	var inside = jQuery('.thumb').width();
	return thumbs - inside;
}

function getThumbHeight(){
	var topShow = jQuery('.top-show');
	var ratio =  topShow.height() / topShow.width();

	return ratio*jQuery('.thumbs').width();
}