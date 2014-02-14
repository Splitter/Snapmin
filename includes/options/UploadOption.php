<?php
/**
* UploadOption.php - text input form element that triggers wordpress' media manager
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
class UploadOption extends OptionType
{ 
	
	public function __construct($option) {
		$this->save = true;
		$this->name =$option['name'];
		$this->title = $option['title'];
		$this->desc = (isset($option['description']))? $option['description']:"";
		$this->def = (isset($option['def']))? $option['def']:"";
		$this->value = (isset($option['value']))? $option['value']:"#000000";
		$this->options = (isset($option['options']))? $option['options']:array();
		$this->validator = (isset($option['validator']))? $option['validator']:null;
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Please re-check the value!");
	}
	
	public function addInit(){
			
			wp_enqueue_style('thickbox');
	    	wp_enqueue_script('media-upload');
	
			wp_enqueue_script('thickbox');
	}
	
	public function displayElement()
	{
		?>
		<input name="<?php echo $this->name;?>" type="text" id="<?php echo $this->name;?>" value="<?php echo htmlspecialchars( stripslashes( $this->value), ENT_QUOTES);?>" class="regular-text" /> 
		<script type="text/javascript">
				jQuery('input[name=<?php echo $this->name;?>]').focus(function(){	
						//alert('here');
						var formfield = jQuery(this);
						window._send_to_editor = window.send_to_editor;
						window.send_to_editor = function(html) {
								var imgurl = jQuery(html).attr('href');
									formfield .val(imgurl);
								formfield = null;
								window.send_to_editor = window._send_to_editor;
								tb_remove();
								
						}
						tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
						return false;
				});
				
				
				
				
				
				
				
		</script>
		
		<?php
	}	
	
	public function validate()
	{
		
		if($this->validator!=null){
			return ($this->error=!(bool)$this->validator($this->value)); 
		}
		return true;
	}
	
}



?>