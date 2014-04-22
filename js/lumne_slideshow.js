jQuery(document).ready(function() {
	// Begin effect for active slideshows
	initializeIt(jQuery('.lumne-slideshow .active').parent().attr('id'));

	jQuery(window).load(function(){
		resize();
	});

	jQuery(window).bind('resize', function(){
		resize();
	});

});

var active = true;

// Start effect (for active galleries)
function initializeIt(gallery){
	var id = typeof gallery !== 'undefined' ? gallery : 0;
	var gallery = jQuery('#gallery'+id);
	var images  = gallery.children('.top-show').children('.lumne_image');
	var count   = images.length;
	var speed   = gallery.data('trans');
	var wait    = gallery.data('pause');

	var pos = 0;
	images.not('#image'+id+'_'+pos).hide(); // Show first image
	jQuery('#image'+id+'_'+pos).attr('z-index', 1);
	images.not('#image'+id+'_'+pos).attr('z-index', 2);
	nextImage(id, ++pos, count, wait, speed, 'slide');

}

function nextImage(id, pos, length, wait, speed, effect){
	var effect = typeof effect !== 'undefined' ? effect : 'slide';
	if(active){
		jQuery('#image'+id+'_'+(pos%length)).delay(wait).effect(effect, speed, function(){
			jQuery('#gallery'+id+' .lumne_image').attr('z-index', 1);
			jQuery('#image'+id+'_'+((pos+1)%length)).attr('z-index', 3);
			jQuery('#image'+id+'_'+((pos-1)%length)).hide();
			nextImage(id, ++pos, length, wait, speed, effect);
		});
	}
}

function resize(){
	jQuery('.top-show').css('height', setTopShowHeight());
}

function setTopShowHeight(){
	return jQuery('.lumne_image').height() / jQuery('.lumne_image').width() * jQuery('.top-show').width();
}