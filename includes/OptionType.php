<?php
/**
* OptionType.php - Abstract base class for options
*
* - an abstract class to base all other option classes on. It contains some basic functionality as
*	well as defines the structure Snapmin expects option type classes to have.
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
abstract class OptionType 
{

	public 	$error,
			$errorMessage,
			$value,
			$title,
			$desc,
			$name,
			$options,
			$validator,
			$save,
			$def;
	
	public function __construct($option) {
		$this->save = true;
		$this->name =$option['name'];
		$this->title = $option['title'];
		$this->desc = (isset($option['description']))? $option['description']:"";
		$this->def = (isset($option['def']))? $option['def']:"";
		$this->value = (isset($option['value']))? $option['value']:"";
		$this->options = (isset($option['options']))? $option['options']:array();
		$this->validator = (isset($option['validator']))? $option['validator']:null;
		$this->errorMessage = (isset($option['errorMessage']))? $option['errorMessage']:__("Invalid Value!");
	}
	public function reset(){
		$this->value=$this->def;
	}
	public function validate()
	{
		if($this->validator!=null){
			return ($this->error=!(bool)$this->validator($this->value)); 
		}
		return true;
	}
	public function setValue($value)
	{	
		return ($this->value = $value);
	}
	
	public function addHead(){} // Called during 'admin_head' hook
	public function addInit(){} // Called during 'admin_init' hook
	public abstract function displayElement();
}


?>