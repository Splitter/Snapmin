<?php
/**
* SnapEditor.php - main snapmin editor class for the page builder.
*
* - Defines menus, hooks and then calls appropriate class to execute correct section of editor
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
class SnapEditor
{
	private $xml,
			$posted,
			$errors,
			$valid,
			$settings;
	
	/* Constructor just initializes a few internals and adds hooks */
	public function __construct($xml)
	{
		$this->errors = array();
		$this->xml = $xml;
		$this->posted = false;
		add_action( 'admin_menu', array($this, 'addMenu') );
		add_action( 'admin_init', array($this, 'addHeaders'));	
		if(isset($_REQUEST['do']) and $_REQUEST['do']=='options' and $_REQUEST['page']=='snapmin_editor'){
			add_action( 'admin_head', array($this, 'addOptionsHead'));	
		}
	}
	
	public function addMenu()
	{
		add_menu_page("Snapmin Page Editor", "Snap Builder", "manage_options", "snapmin_section",array($this, 'settings'), null, 42 );
		$submenu = add_submenu_page("snapmin_section","Snapmin Settings","Settings", "manage_options","snapmin_section",array($this, 'settings') );
		$submenu = add_submenu_page("snapmin_section","Snapmin Page Editor","Page Editor", "manage_options","snapmin_editor",array($this, 'editor') );
	}
	
	public function addHeaders()
	{
		wp_enqueue_script('sv_numeric',
							SNAPMIN_URI.'assets/js/jquery.numeric.js',
							 array('jquery'),
							'1.0' );							
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_style('snapmin_editor', SNAPMIN_URI. 'assets/css/snapmin_editor.css', false, '1.0', 'all');
	}
	
	public function addOptionsHead()
	{
		?>	
		<script>
			var SnapminNew = false;
			var SnapminCreateBlock = function(el){
				var opts = el.children("img:first-child").attr("data-options").split(',');
				var type = el.children("img:first-child").attr("class");
				var count = parseInt(jQuery("input[name=numopts]").val())+1;
				jQuery("input[name=numopts]").val(count);
				if(jQuery(el).parent().attr('id')=='snapmin-options-page'){
					var count2 = parseInt(jQuery("input[name=numpageopts]").val())+1;
					jQuery("input[name=numpageopts]").val(count2);
				}
				var titlebar = jQuery('<div class="snapmin-options-type"/>');
				var icon = jQuery('<img/>');
				icon.attr('src',el.children("img:first-child").attr("src"));
				titlebar.append( icon );
				titlebar.append( document.createTextNode( el.text()) );
				titlebar.append(jQuery('<img class="snapmin-close" src ="<?php echo SNAPMIN_URI;?>/assets/images/close.png" alt="close"/>'));
				var li = jQuery("<li/>");
				li.append(titlebar);
				var right = jQuery('<div class="snapmin-col-right"/>');
				if(jQuery.inArray( "description", opts )!=-1){
					var label = jQuery("<label/>");
					label.append(jQuery("<span>Description&nbsp;&nbsp;</span>"));
					label.append(jQuery('<textarea class="message" name="description'+count+'" ></textarea>'));
					right.append(label);
				}
				var left = jQuery('<div class="snapmin-col-left"/>');
				if(jQuery.inArray( "title", opts )!=-1){
					var label = jQuery("<label/>");
					label.append(jQuery("<span>Title&nbsp;*&nbsp;</span>"));
					label.append(jQuery('<input type="text"  value ="" class="snapmin-options-input" name="title'+count+'" id="title'+count+'"/>'));
					left.append(label);
				}
				if(jQuery.inArray( "name", opts )!=-1){
					var label = jQuery("<label/>");
					label.append(jQuery("<span>Name&nbsp;*&nbsp;</span>"));
					label.append(jQuery('<input type="text"  value ="" class="snapmin-options-input" name="names'+count+'" id="name'+count+'"/>'));
					left.append(label);
				}
				if(jQuery.inArray( "default", opts )!=-1){
					var label = jQuery("<label/>");
					label.append(jQuery("<span>Default&nbsp;&nbsp;</span>"));
					label.append(jQuery('<input type="text"  value ="" class="snapmin-options-input" name="default'+count+'" id="default'+count+'"/>'));
					left.append(label);
				}
				li.append(right);
				li.append(left);
				var optblock = jQuery('<div class ="snapmin-options-block"/>');
				if(jQuery.inArray( "options", opts )!=-1){
					var rightb = jQuery('<div class="snapmin-options-right"/>');
					var inner = jQuery('<div class="snapmin-options-inner">	');
					inner.append(jQuery('<input type="text" value = "Name" class="snapmin-options-input" name="name'+count+'[]" id="name'+count+'"/>'));
					inner.append(jQuery('<input type="text" value = "Value" class="snapmin-options-input" name="value'+count+'[]" id="value'+count+'"/>'));
					inner.append(jQuery('	<img class="snapmin-close-option" src ="<?php echo SNAPMIN_URI;?>/assets/images/close.png" alt="close"/>'));
					rightb.append(inner);
					optblock.append(rightb);
					var leftb = jQuery('<div class="snapmin-options-left">');
					leftb.append('<span>Options *:</span>');
					leftb.append('<a href="#" >Add New Option</a>');
					optblock.append(leftb);
				}
				li.append(optblock);
				li.append(jQuery('<input type = "hidden" name = "type'+count+'" value ="'+type+'"/>'));
				li.append(jQuery('<input type = "hidden" class="snapdisplay" name = "disp'+count+'" value ="'+count+'"/>'));
				li.append(jQuery('<div class="snapmin-clear"></div>'));
				el.replaceWith(li);
			}			
			var SnapminReorder = function(){
				jQuery(".snapmin-options-sortable").children("li").each(function(index){
					jQuery(this).children("input.snapdisplay").each(function(){
						jQuery(this).val(""+(index+1));
					});
				});		
			}
			jQuery(function() {
				jQuery('div.snapmin-options-left a').live('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					var par = jQuery(this).parent().parent();
					var inner = jQuery('<div class="snapmin-options-inner">	');
					var outer = par.children('div.snapmin-options-right:first-child');
					var inp = par.parent().find('input.display');
					var str = inp.attr('name').match(/\d+/);
					var count = str[0];
					var old = par.children( "div.snapmin-options-inner");
					var newd = old.clone();
					inner.append(jQuery('<input type="text" value = "Name" class="snapmin-options-input" name="name'+count+'[]" id="name'+count+'"/>'));
					inner.append(jQuery('<input type="text" value = "Value" class="snapmin-options-input" name="value'+count+'[]" id="value'+count+'"/>'));
					inner.append(jQuery('	<img class="snapmin-close-option" src ="<?php echo SNAPMIN_URI;?>/assets/images/close.png" alt="close"/>'));
					outer.append(inner);
				});				
				jQuery('img.snapmin-close').live('click', function(e) {
					if(jQuery(this).parent().parent().parent().attr('id')=='snapmin-options-page'){					
						var count2 = parseInt(jQuery("input[name=numpageopts]").val())-1;
						jQuery("input[name=numpageopts]").val(count2);
					}
					jQuery(this).parent().parent().remove();	
					jQuery(".snapdisplay").each(function(index){
						jQuery(this).val(index+1);
					});
				});
				jQuery('img.snapmin-close-option').live('click', function(e) {
					if(jQuery(this).parent().parent().attr('id')=='snapmin-options-page'){				
						var count2 = parseInt(jQuery("input[name=numpageopts]").val())-1;
						jQuery("input[name=numpageopts]").val(count2);
					}
					jQuery(this).parent().remove();				
					jQuery(".snapdisplay").each(function(index){
						jQuery(this).val(index+1);
					});
				});				
				jQuery( "ul.snapmin-options-sortable" ).sortable({
					revert: true,
					placeholder: 'snapmin-options-target',
					stop : function(event, ui){
						if(SnapminNew){				
							SnapminCreateBlock(ui.item);
							SnapminNew = false;
						}
						SnapminReorder();
					},
					receive: function(event, ui){
						SnapminNew = true;
						SnapminReorder();
					}
				});
				jQuery( "ul.snapmin-options-draggable li" ).draggable({
					connectToSortable: "ul.snapmin-options-sortable",
					helper: function(event) { 
						var w = jQuery(event.target).parent().children(':first-child').width();
						return jQuery(event.target).clone().addClass('snapmin-options-dragging').css({width:w+"px"});
					},
					revert: "invalid"
				});
			});
		</script>
		<?php
	}
	
	public function editor()
	{		
		if(!isset($_REQUEST['do']) or $_REQUEST['do']!='options'){
			include(SNAPMIN_LOCAL."/includes/editor/EditorPage.php");
			$editorPage =  new SnapEditorPage($this->xml);
			$editorPage->renderPage();
		}
		else{
			$this->options();
		}
	}	
	
	public function options()
	{
		include(SNAPMIN_LOCAL."/includes/editor/OptionsPage.php");
		$optionsPage =  new SnapOptionsPage($this->xml);
		$optionsPage->renderPage();	
	}
	
	public function settings()
	{
		include(SNAPMIN_LOCAL."/includes/editor/SettingsPage.php");
		$settingsPage =  new SnapSettingsPage($this->xml);
		$settingsPage->renderPage();		
	}
}