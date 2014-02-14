<?php
/**
* PageType.php - Abstract base class for pages
*
* - an abstract class to base all page classes on. It contains some basic functionality as
*	well as defines the structure Snapmin expects page classes to have.
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
abstract class PageType 
{

    public 	$errors,
			$title,
			$menuTitle,
			$menuSlug,
			$options,
			$values;			
	
	public function __construct($data)
	{
		$this->title = $data['title'];
		$this->menuTitle = $data['menuTitle'];
		$this->menuSlug = $data['menuSlug'];
		$this->options = array();
		$this->errors = false;
		$this->values = get_option($data['menuSlug']);
	}
	
	public function saveOptions()
	{
		update_option($this->menuSlug, $this->values );
	}
	
	public function addInit()// Called during 'admin_init' hook
	{
		foreach($this->options as $opt)
		{
			$opt->addInit();
		}	
	}
	
	public function addHead()// Called during 'admin_head' hook
	{
		foreach($this->options as $opt)
		{
			$opt->addHead();
		}	
	}
	
	public abstract function renderPage();
	
	public abstract function addOption($option);
	
	public function addPageOption($option){}

}

?>
