<?php
/**
* RadioOption.php - radio input form element
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
class RadioOption extends OptionType
{ 
	public function displayElement()
	{
		$count = 0;
		?><div class="radio"><?php
		foreach($this->options as $key => $val){?>
				<input name="<?php echo $this->name;?>" type="radio" id="<?php echo $this->name."_".$count;?>" value="<?php echo $val;?>" <?php if ( $val  == $this->value) { echo 'checked="checked"'; } ?> class="isradio"/><label for="<?php echo $this->name."_".$count;?>" ><?php echo $key;?></label><br/>

		<?php 
			$count++;
		} 
		?>			</div><?php
			
	}	
}



?>