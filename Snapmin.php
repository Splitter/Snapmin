<?php
/**
* Snapmin.php - Main file which includes and executes code neccessary for snapmin to funtion properly.
*
* Notes : include this file within themes function.php for magic to happen
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

//Only need execution if in admin area of wordpress
if( is_admin() ){


//---------------------------------------------------------------------------------->>
// Framework Settings

	// Enable / Disable built in page builder by doing EITHER of the following steps
		// 1. - set equal to 0
		// 2. - OR comment out line completely 
	define('SNAPMIN_ADD_EDITOR',1);
	
	// The name of the 'snapmin' directory 
	define('SNAPMIN_DIRECTORY','Snapmin');

	// URI to framework directory 
	define('SNAPMIN_URI', get_template_directory_uri().'/'.SNAPMIN_DIRECTORY.'/');

	// Path to framework directory 
	define('SNAPMIN_LOCAL', get_template_directory().'/'.SNAPMIN_DIRECTORY.'/');

	// XML File Location 
	define('SNAPMIN_XML_FILE',SNAPMIN_LOCAL.'SnapPages.xml');

	
//---------------------------------------------------------------------------------->>
// Load XML file containing theme option pages setup

	$SnapminXML = null;
	if(file_exists(SNAPMIN_XML_FILE)){
		if(!$SnapminXML=simplexml_load_file(SNAPMIN_XML_FILE)){
			trigger_error(__('Error reading Snapmin XML file'),E_USER_ERROR);
		}
	}
	
	
//---------------------------------------------------------------------------------->>
// Admin Pages Setup
	
	// Various functions used by framework
	include(SNAPMIN_LOCAL."includes/Functions.php");

	//  Include manager & base classes
	include(SNAPMIN_LOCAL."includes/OptionType.php");
	include(SNAPMIN_LOCAL."includes/PageType.php");
	include(SNAPMIN_LOCAL."includes/SnapClass.php");

	// Include all available page types
	$dir = dir(SNAPMIN_LOCAL."includes/pages"); 
	while (($file = $dir->read()) !==  false) { 
		if (substr($file, -8) == 'Page.php') { 
			include(SNAPMIN_LOCAL."includes/pages/".$file);
		}
	} 
	$dir->close(); 

	// Include all available option types
	$dir = dir(SNAPMIN_LOCAL."includes/options"); 
	while (($file = $dir->read()) !==  false) { 
		if (substr($file, -10) == 'Option.php') { 
			include(SNAPMIN_LOCAL."includes/options/".$file);
		}
	} 
	$dir->close();  
	
	// Parse the XML file defining pages - returns page manager instanc
	$Manager = Snapmin_ParseXML($SnapminXML);
	


//---------------------------------------------------------------------------------->>
//Page Editor Setup
	if( defined( 'SNAPMIN_ADD_EDITOR' ) and SNAPMIN_ADD_EDITOR == 1 ){	
			include(SNAPMIN_LOCAL."includes/SnapEditor.php");
			$SnapEditor = new SnapEditor($SnapminXML);
	}

} // end is_admin() block

?>