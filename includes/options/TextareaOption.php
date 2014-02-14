<?php
/**
* TextareaOption.php - textarea input form element
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
class TextareaOption extends OptionType
{ 
	public function displayElement()
	{

		?>	    
		
		<textarea name="<?php echo $this->name;?>" id="<?php echo $this->name;?>" cols="40" rows="5"/><?php echo htmlspecialchars( stripslashes( $this->value), ENT_QUOTES);?></textarea>
	
		<?php
			
	}	
}



?>