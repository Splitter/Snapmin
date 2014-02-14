<?php
/**
* NumericOption.php - input field for numeric data with jquery powered filter
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
class NumericOption extends OptionType
{ 
	
	public function __construct($option) {
		$this->save = true;
		$this->name =$option['name'];
		$this->title = $option['title'];
		$this->desc = (isset($option['description']))? $option['description']:"";
		$this->def = (isset($option['def']))? $option['def']:"";
		$this->value = (isset($option['value']))? $option['value']:"";
		$this->options = (isset($option['options']))? $option['options']:array();
		$this->validator = (isset($option['validator']))? $option['validator']:null;
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Must Be Numeric!");
	}
	
	public function displayElement()
	{
		?>
		<input name="<?php echo $this->name;?>" type="text" id="<?php echo $this->name;?>" value="<?php echo htmlspecialchars( stripslashes( $this->value), ENT_QUOTES);?>" class="regular-text" /> 
		<script type="text/javascript">jQuery('input[name=<?php echo $this->name;?>]').numeric();</script>
		<?php
	}	
	
	public function addInit(){
		wp_enqueue_script('sv_numeric',
							SNAPMIN_URI.'assets/js/jquery.numeric.js',
							 array('jquery'),
							'1.0' );
	}
	
	public function validate()
	{
		if(!is_numeric($this->value)){
			return !($this->error = true); 
		}		
		if($this->validator!=null){
			return ($this->error=!(bool)$this->validator($this->value)); 
		}
		return true;
	}
	
}



?>