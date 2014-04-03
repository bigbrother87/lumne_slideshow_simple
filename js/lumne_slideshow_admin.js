var image_custom_uploader;

jQuery(document).ready(function(){
	activateDeletes();

	jQuery('.image-button').click(function(e) {
		e.preventDefault();
		uploader(jQuery(this).attr('id').substring(9), jQuery(this).data('count'));
		activateDeletes();
	});
});


function uploader(id, count){
	//jQuery('#new_image_path'+id).val('Clicked');

	//If the uploader object has already been created, reopen the dialog
	if (image_custom_uploader) {
		image_custom_uploader.open();
		return;
	}

	//Extend the wp.media object
	image_custom_uploader = wp.media.frames.file_frame = wp.media({
		title: 'Choose Image',
		button: {
		text: 'Choose Image'
		},
		multiple: false
	});

	//When a file is selected, grab the URL and set it as the text field's value
	image_custom_uploader.on('select', function() {
		attachment = image_custom_uploader.state().get('selection').first().toJSON();
		var url = '';
		url = attachment['url'];
		//jQuery('#new_image_path'+id).val(url);
		jQuery('#new_image'+id).before(newImage(id, url));
	});

	//Open the uploader dialog
	image_custom_uploader.open();
}

function newImage(section, url){
	var number = jQuery('#new_image'+section).data('count');
	jQuery('#new_image'+section).data('count', number*1+1);

	return '<tr class=\'lumne-image-row\'>'
			+ '<td>'
				+ '<img src=\'' + url + '\' class=\'lumne-image-preview\' />'
				+ '<input type=\'hidden\' name=\'plugin_options[path_' + section + '_' + number + ']\' value=\'' + url + '\' />'
			+ '</td>'
			+ '<td>'
				+ '<input id=\'plugin_text_image_' + section + '_' + number + '\' class=\'lumne-image-link\' name=\'plugin_options[link_' + section + '_' + number + ']\' size=\'30\' type=\'text\' placeholder=\'Insert link here (e.g. http://lumne.net)\' />'
			+ '</td>'
			+ '<td><img src=\'\' id=\'delete_' + section + '_' + number + '\' class=\'delete-image\' /></td>'
		+ '</tr>';

	/*return '<label for=\'plugin_text_image_'+ section +'_' + number + '\'>'
			+ '<img src=\'' + url + '\' class=\'lumne-image-preview\' />'
			+ '<input type=\'hidden\' name=\'plugin_options[path_' + section + '_' + number + ']\' value=\'' + url + '\' />'
			+ '<input id=\'plugin_text_image_' + section + '_' + number + '\' class=\'lumne-image-link\' name=\'plugin_options[link_' 
			+ section + '_' + number + ']\' size=\'30\' type=\'text\' placeholder=\'Insert link here (e.g. http://lumne.net)\' />'
			+ '</label><br />';*/
}

function activateDeletes(){
	jQuery('.delete-image').click(function(){
		var id = jQuery(this).attr('id').substring(7);
		//alert(id);
		//jQuery('label[for="plugin_text_image_0_0"]').remove();
		jQuery('label[for="'+ 'plugin_text_image_' + id +'"]').remove();
		jQuery(this).remove();
	});
}

function reNumber(id){
	return true;
}