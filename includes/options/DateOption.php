<?php
/**
* DateOption.php - input field for date with jquery powered date selector
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
class DateOption extends OptionType
{ 
	
	public function __construct($option) {
		$this->save = true;
		$this->name =$option['name'];
		$this->title = $option['title'];
		$this->desc = (isset($option['description']))? $option['description']:"";
		$this->def = (isset($option['def']))? $option['def']:"";
		$this->value = (isset($option['value']))? $option['value']:"1/1/2011";
		$this->options = (isset($option['options']))? $option['options']:array();
		$this->validator = (isset($option['validator']))? $option['validator']:null;
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Date must be in format m/d/YY!");
	}
	
	public function addInit(){
	    wp_enqueue_script( 'jquery' );
		wp_enqueue_script('sv_datepicker',
							SNAPMIN_URI.'assets/js/datepicker.js',
							 array('jquery'),
							'1.0' );
		wp_enqueue_style( 'sv_datepicker', 
							SNAPMIN_URI.'assets/css/datepicker.css');
	}
	
	public function displayElement()
	{
		?>
		<input name="<?php echo $this->name;?>" type="text" id="<?php echo $this->name;?>" value="<?php echo htmlspecialchars( stripslashes( $this->value), ENT_QUOTES);?>" class="regular-text" /> 
		<script type="text/javascript">
			jQuery('input[name=<?php echo $this->name;?>]').DatePicker({
					format:'m/d/Y',
					date: jQuery('input[name=<?php echo $this->name;?>]').val(),
					current: jQuery('input[name=<?php echo $this->name;?>]').val(),
					starts: 1,
					position: 'bottom',
					onBeforeShow: function(){
						jQuery('input[name=<?php echo $this->name;?>]').DatePickerSetDate(jQuery('input[name=<?php echo $this->name;?>]').val(), true);
					},
					onChange: function(formated, dates){
						jQuery('input[name=<?php echo $this->name;?>]').val(formated);
						jQuery('input[name=<?php echo $this->name;?>]').DatePickerHide();
					}
				});		
		</script>
		
		<?php
	}	
	
	public function validate()
	{
		$arr=split("/",$this->value); 
		$mm=$arr[0]; $dd=$arr[1]; $yy=$arr[2]; 
		if(!checkdate($mm,$dd,$yy)){
			return !($this->error = true); 
		}	
		if($this->validator!=null){
			return ($this->error=!(bool)$this->validator($this->value)); 
		}
		return true;
	}
	
}



?>