/**
* gallery.js - image gallery option javascript
*
* LICENSE: GPLv2
*
* @package    Snapmin
* @copyright  Copyright (c) 2012-2014 Mike Pippin a.k.a Splitter
* @license    http://wordpress.org/about/gpl/
* @version    1.0.1
* @link       https://github.com/Splitter/Snapmin
* 
*/
jQuery(document).ready(function($) {
	$('.sv-gallery-edit').click(function (e) {
		e.preventDefault();		
		var parent = $(this).parents('.sv-gallery-wrapper'),
			valueInput = parent.children('.sv-gallery-data'),
			ids = valueInput.attr('value'),
			shortcode = '[gallery ids="' + ids + '"]';
		wp.media.gallery.edit(shortcode).on('update', function(obj) { 
											var idList = [];
											$.each(obj.models, function(id,val) {
																	idList.push(val.id)
																});
											valueInput.attr('value',idList.join(","));
											$.ajax({
													  type: 'POST',
													  url: ajaxurl,
													  dataType: 'html',
													  data: {
														action: 'gallery_update',
														ids: idList
													  },
													  success: function(res) {
														parent.children('.sv-gallery-inner').html(res)														
													  }
													})
										});
	})

});




