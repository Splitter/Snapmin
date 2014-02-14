<?php
/**
* Pages.php - array that defines page types and options for each which is used by editor/page builder
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
$SnapPages = array(
			'static' => array(
					'individualOptions' => true,
					'pageOptions' => false,
					'name' => __("Static Page")
			),
			'dynamic' => array(
					'individualOptions' => true,
					'pageOptions' => true,
					'name' => __("Dynamic Page")
			)
);

?>