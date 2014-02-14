<?php
/**
* ColorOption.php - input field for hex color code with jquery powered color selector
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
class ColorOption extends OptionType
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
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Color must be in hex format(#939393)!");
	}
	
	public function addInit(){
	    wp_enqueue_script( 'jquery' );
		wp_enqueue_script('sv_colorpicker',
							SNAPMIN_URI.'assets/js/colorpicker.js',
							 array('jquery'),
							'1.0' );
		wp_enqueue_style( 'sv_colorpicker', 
							SNAPMIN_URI.'assets/css/colorpicker.css');
	}
	
	public function displayElement()
	{
		?>
		<input name="<?php echo $this->name;?>" type="text" id="<?php echo $this->name;?>" value="<?php echo htmlspecialchars( stripslashes( $this->value), ENT_QUOTES);?>" class="regular-text" /> 
		<script type="text/javascript">
				
				jQuery('input[name=<?php echo $this->name;?>]').ColorPicker({
					onSubmit: function(hsb, hex, rgb, el) {
						jQuery(el).val(hex);
						jQuery(el).ColorPickerHide();
					},
					onBeforeShow: function () {
						jQuery(this).ColorPickerSetColor(this.value);
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('input[name=<?php echo $this->name;?>]').val( '#' + hex);
					}
				})
				.bind('keyup', function(){
					jQuery(this).ColorPickerSetColor(this.value);
				});
		</script>
		
		<?php
	}	
	
	public function validate()
	{
		if(!preg_match('/^#[a-f0-9]{6}$/i',$this->value)){
			return !($this->error = true); 
		}	
		if($this->validator!=null){
			return ($this->error=!(bool)$this->validator($this->value)); 
		}
		return true;
	}
	
}



?>