
jQuery('document').ready(function(){	
		jQuery('td.sv_static').each(function(){
			var $this = jQuery(this);
			jQuery('input[type=text], select, textarea,div.radio',this).each(function(){	
				var inp = jQuery(this);
				inp.css({float:'left'});
				var off = inp.offset();
				var w = inp.width();
				var desc = jQuery('span.description',$this);
				desc.hide();
				var txt = desc.text();
				if(jQuery.trim(txt)!=""){
					var img = jQuery('<img src = "'+snapmin_url+'assets/images/info.png" />');
					img.css({float:'left'});
					inp.parent().append(img);
					img.css({top:'0',left:'0'});
					img.attr('title',txt);
					var opts={
							offsetX:-15,
							offsetY:0,
							type:0,
							tClass:'svtt_darkbottom'
						};
					img.svTitleTooltip(opts);
				}
			});;
		});
});