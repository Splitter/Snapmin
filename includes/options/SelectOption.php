<?php
/**
* SelectOption.php - select input form element
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
class SelectOption extends OptionType
{ 
	public function displayElement()
	{
		?>
		
		
		<select name="<?php echo $this->name;?>" id="<?php echo $this->name;?>"> 
			<?php foreach($this->options as $key => $val){?>
				<option value = "<?php echo $val;?>" <?php if ( $val  == $this->value) { echo ' selected="selected"'; } ?>><?php echo $key;?></option>
			
			<?php } ?>
		</select>
		
		
		<?php
	}	
}



?>