<?php
/**
* RichTextOption.php - textarea powered by wordpress' version of tinymce, to create a feature rich text editor
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
class RichTextOption extends OptionType
{ 
	public function displayElement()
	{	
		$editor_settings = array(
			'textarea_name' => $this->name ,
			'textarea_rows' => 10,
			'wpautop' => true
		);		
		wp_editor( stripslashes($this->value), $this->name, $editor_settings );
	}	
}



?>