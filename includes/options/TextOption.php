<?php
/**
* TextOption.php - text input form element
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
class TextOption extends OptionType
{ 
	public function displayElement()
	{
		?>
		<input name="<?php echo $this->name;?>" type="text" id="<?php echo $this->name;?>" value="<?php echo htmlspecialchars( stripslashes( $this->value), ENT_QUOTES);?>" class="regular-text" /> 
		<?php
	}	
}



?>