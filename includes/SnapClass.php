<?php
/**
* SnapClass.php - manages setting up menu, as well as displaying and executing pages created by framework
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
class Snapmin
{

    public 	$errors,		/* used for debugging development 
							*  TODO: remove from production release
							*/
						
			$pages, 		/* array which holds all page instances */
			
			$menuTitle,		/* title for main menu section */
			
			$capability,	/* capability user must have to access admin pages 
							*  defaults to 'manage_options'
							*/
			
			$position,		/* display order for menu section of admin panel */
			
			$menuSlug;		/* menu slug(unique identifier) for menu section */

	/* Constructor just initializes a few internals and adds hooks */
	public function __construct($menuTitle,$capability = null,$position=null)
	{
		$this->pages = array();
		$this->errors = array();
		$this->menuTitle = $menuTitle;
		$this->capability = ($capability)?$capability:'manage_options';
		$this->position = ($position)?$position:null;
		add_action( 'admin_menu', array($this, 'addMenu') );
		add_action( 'admin_init', array($this, 'addInit'));
		add_action('admin_head', array($this, 'addHead'));
	}
	
	// cycle through all pages and adds menus for each page
	public function addMenu()
	{
		$first = true;
		foreach($this->pages as $key=>$page)
		{
			if($first)
			{	
				/* follow default wordpress behavior when clicking section header
				*  to achieve set section headers menu slug to first items menu slug
				*/
				
				add_menu_page($page->title, $this->menuTitle, $this->capability, $page->menuSlug,array( $page, 'renderPage' ), null, $this->position );
				$submenu = add_submenu_page($page->menuSlug,$page->title,$page->menuTitle, $this->capability,$page->menuSlug, array( $page, 'renderPage' ) );
				$this->menuSlug = $page->menuSlug;
				$first=false;
			}
			else
			{
				$submenu = add_submenu_page($this->menuSlug,$page->title,$page->menuTitle, $this->capability,$page->menuSlug, array( $page, 'renderPage' ) );
			}		
		}	
	}
	
	/* add code during 'admin_init' hook */
	/* add base Stylesheet and any Stylesheets needed by pages/options */
	public function addInit()
	{
		
		wp_enqueue_style('Snapmin-base', SNAPMIN_URI . '/assets/css/snapmin-base.css', false, '1.0', 'all');

		if(isset($_REQUEST['page']))
		{
			foreach($this->pages as $page)
			{
				if($_REQUEST['page']==$page->menuSlug)
				{
					$page->addInit();		
				}
			}	
		}
	}
	
	/* add code during 'admin_head' hook */
	public function addHead()
	{

		if(isset($_REQUEST['page']))
		{
			foreach($this->pages as $page)
			{
				if($_REQUEST['page']==$page->menuSlug)
				{
					$page->addHead();		
				}
			}	
		}
	}
	
	/* Utility function to add pages
	*  just creates instance of page type based on data passed in
	*  and adds it to internal array of pages
	*/	
	public function addPage($page)
	{
		$class = ucwords(strtolower($page['type']))."Page";
		if(class_exists ($class))
		{
			$this->pages[$page['menuSlug']] = new $class($page);
		}
		else
		{
			$this->errors[]="Unable to create page of type '".$class."'";
		}
	}
		
		
		
	/* Utility function to add options
	*  just calls add option function of page class and let
	*  it handle the options 
	*/
	public function addOption($page,$option)
	{
		if(isset($this->pages[$page]))
		{
			$class = ucwords(strtolower($option['type']))."Option";
			if(class_exists($class))
			{
				$this->pages[$page]->addOption($option);
			}
			else
			{
				$this->errors[]="Unable to create option of type '".$class."'";
			}			
		}
		else
		{
			$this->errors[]="Page '".$page."' does not exist";
		}
	}
	
	/* Utility function to add options
	*  just calls add option function of page class and let
	*  it handle the options 
	*/
	public function addPageOption($page,$option)
	{
		if(isset($this->pages[$page]))
		{
			$class = ucwords(strtolower($option['type']))."Option";
			if(class_exists($class))
			{
				$this->pages[$page]->addPageOption($option);
			}
			else
			{
				$this->errors[]="Unable to create option of type '".$class."'";
			}			
		}
		else
		{
			$this->errors[]="Page '".$page."' does not exist";
		}
	}
	
};


?>