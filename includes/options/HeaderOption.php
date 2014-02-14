<?php
/**
* HeaderOption.php - Heading to display prominent text to user
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
class HeaderOption extends OptionType
{ 
	public function __construct($option) {
		$this->save = false;
		$this->name =$option['name'];
		$this->title = $option['title'];
		$this->desc = (isset($option['description']))? $option['description']:"";
		$this->def = (isset($option['def']))? $option['def']:"";
		$this->value = (isset($option['value']))? $option['value']:"";
		$this->options = (isset($option['options']))? $option['options']:array();
		$this->validator = (isset($option['validator']))? $option['validator']:null;
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Invalid Value!");
	}
	public function displayElement()
	{
		?>
		<h2><?php echo $this->desc;?></h2>
		<?php	
	}	
}



?>