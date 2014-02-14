<?php
/**
* Options.php - array that defines option types and options for each which is used by editor/page builder
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
$SnapOptions = array(
			'header' => array(
					'valid' => array(
								"name",
								"description"
							),
					'image' => SNAPMIN_URI."assets/images/optionheading.png",
					'name' => __("Heading")
			),
			'paragraph' => array(
					'valid' => array(
								"name",
								"description"
							),
					'image' => SNAPMIN_URI."assets/images/optionparagraph.png",
					'name' => __("Paragraph")
			),
			'text' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optiontext.png",
					'name' => __("Text Input")
			),
			'textarea' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optiontextarea.png",
					'name' => __("TextArea")
			),
			'richtext' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optionrichtext.png",
					'name' => __("Rich Text Editor")
			),
			'select' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default",
								"options"
							),
					'image' => SNAPMIN_URI."assets/images/optionselect.png",
					'name' => __("Dropdown")
			),
			'radio' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default",
								"options"
							),
					'image' => SNAPMIN_URI."assets/images/optionradio.png",
					'name' => __("Radio")
			),
			'numeric' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optionnumber.png",
					'name' => __("Numeric Input")
			),
			'date' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optiondate.png",
					'name' => __("Date Input")
			),
			'color' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optioncolor.png",
					'name' => __("Color Input")
			),
			'upload' => array(
					'valid' => array(
								"name",
								"description",
								"title",
								"default"
							),
					'image' => SNAPMIN_URI."assets/images/optionupload.png",
					'name' => __("Upload Input")
			),
			'category' => array(
					'valid' => array(
								"name",
								"description",
								"title"
							),
					'image' => SNAPMIN_URI."assets/images/optioncategory.png",
					'name' => __("Category Dropdown")
			),
			'tag' => array(
					'valid' => array(
								"name",
								"description",
								"title"
							),
					'image' => SNAPMIN_URI."assets/images/optiontag.png",
					'name' => __("Tag Dropdown")
			),
			'gallery' => array(
					'valid' => array(
								"name",
								"description",
								"title"
							),
					'image' => SNAPMIN_URI."assets/images/optiongallery.png",
					'name' => __("Gallery")
			)
);

?>