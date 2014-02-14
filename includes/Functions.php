<?php
/**
* Functions.php - various functions used by framework.
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
	

/**
* Parses XML file and creates class instances for admin page structure.
*
* @param string $SnapminXML
*   A string containing local path to xml file
*
*/
function Snapmin_ParseXML($SnapminXML){

	// Admin Section Options
	$title = $SnapminXML->title;
	if(isset($SnapminXML->capability)){
		$cap = $SnapminXML->capability;
	}
	else{
		$cap = 'manage_options';
	}
	if(isset($SnapminXML->position)){
		$pos = $SnapminXML->position;
	}
	else{ 
		$pos = null;
	}

	// Create manager instance and begin parsing pages	
	$pages = $SnapminXML->pages;
	$Manager  = new Snapmin((string)$title, (string)$cap, (string)$pos);

	foreach($pages as $pagesa){
		foreach($pagesa as $page){
			//create page instance
			$Manager->addPage(array(
							'title'=>(string)$page->title,
							'menuTitle'=>(string)$page->menuTitle,
							'menuSlug'=>(string)$page->menuSlug,
							'type'=>(string)$page->type
							));
			
			//parse page options/elements
			foreach($page->pageOptions as $options){
				foreach($options as $option){
					//$option = $option->option;
					$settings = array();
						$nArr = array();
					//make sure required fields are set for option.
					if(!isset($option->type) or !isset($option->name) or !isset($option->desc)){
						continue;
					}
					else{
						$settings['type'] = (string)$option->type;
						$settings['name'] = (string)$option->name;
						$settings['description'] = (string)$option->desc;
					}
					
					//optional settings for options/elements
					if(isset($option->title)){
						$settings['title'] = (string)$option->title;
					}
					if(isset($option->def)){
						$settings['def'] = (string)$option->def;
					}
					
					//parse options array for elements that require it
					if(isset($option->opts)){
							$option->opts = (string)$option->opts;
							$opts = explode(',,',$option->opts);
							foreach($opts as $opt){
								$nopt = explode(':;',$opt);
								$nArr[(string)$nopt[1]]=(string)$nopt[0];					
							}
							$settings['options']=$nArr;
					}
					
					//add option to page
					$Manager->addPageOption((string)$page->menuSlug,$settings);		
				}	
			}
			
			//parse page options/elements
			foreach($page->options as $options){
				foreach($options as $option){
					//$option = $option->option;
					$settings = array();
						$nArr = array();
					//make sure required fields are set for option.
					if(!isset($option->type) or !isset($option->name) or !isset($option->desc)){
						continue;
					}
					else{
						$settings['type'] = (string)$option->type;
						$settings['name'] = (string)$option->name;
						$settings['description'] = (string)$option->desc;
					}
					
					//optional settings for options/elements
					if(isset($option->title)){
						$settings['title'] = (string)$option->title;
					}
					if(isset($option->def)){
						$settings['def'] = (string)$option->def;
					}
					
					//parse options array for elements that require it
					if(isset($option->opts)){
							$option->opts = (string)$option->opts;
							$opts = explode(',,',$option->opts);
							foreach($opts as $opt){
								$nopt = explode(':;',$opt);
								$nArr[(string)$nopt[1]]=(string)$nopt[0];					
							}
							$settings['options']=$nArr;
					}
					
					//add option to page
					$Manager->addOption((string)$page->menuSlug,$settings);		
				}	
			}
		}
	}
	return $Manager;
} // Snampmin_ParseXML

?>