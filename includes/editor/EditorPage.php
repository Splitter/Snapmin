<?php
/**
* EditorPage.php - main 'page builder' area of the editor.
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
class SnapEditorPage
{
	private $posted,
			$xml,
			$errors;
			
	/* Constructor just initializes a few internals and adds hooks */
	public function __construct($xml)
	{	
		$this->posted = false;
		$this->xml = $xml;
		$this->errors = array();
	}
	
	public function renderPage(){
		$this->posted = false;
		if(!empty($_POST) && check_admin_referer("snapmin_editor","snapmin_editor_wpnonce"))
		{
			if(isset($_REQUEST['do']) and $_REQUEST['do']=='new')
			{
				$this->saveNew();
			}	
			else if(isset($_REQUEST['do']) and $_REQUEST['do']=='edit')
			{
				$this->saveEdit();
			}	
			$this->posted = true;
		}
		else if(isset($_REQUEST['delete']))
		{
			$this->saveDelete();
		}	
		$this->renderHead();
		$title = $menuTitle = $menuSlug = "";
		$type = 'static';
		$do = 'new';
		$id = 0;
		if(!isset($_REQUEST['edit']) and !isset($_REQUEST['delete'])){
			if(sizeof($this->errors)>=1){
				$title = isset($_POST['snapmin_title'])? $_POST['snapmin_title'] : "";
				$menuTitle = isset($_POST['snapmin_menutitle'])? $_POST['snapmin_menutitle']: "";
				$type = isset($_POST['snapmin_type'])?$_POST['snapmin_type'] : "static";			
				$menuSlug = isset($_POST['snapmin_slug'])? $_POST['snapmin_slug']: "";
				$do = 'new';
			}		
		}
		else if(isset($_REQUEST['edit'])){
		
			$id = ((int)$_REQUEST['edit']);
			$page = $this->xml->pages[0]->page[$id-1];
			$title = (string) $page->title;
			$menuTitle = (string) $page->menuTitle;
			$menuSlug = (string) $page->menuSlug;
			$type = (string) $page->type;
			$do = 'edit';
		
		}
		$this->renderMainTable();
		$this->renderPageForm( $title, $menuTitle, $type, $menuSlug, $id, $do );
		$this->renderFoot();
	}
	
	function saveNew(){
		if(!isset($_POST['snapmin_slug']) or trim($_POST['snapmin_slug'])=="" or !isset($_POST['snapmin_title']) or trim($_POST['snapmin_title'])==""  or !isset($_POST['snapmin_menutitle']) or trim($_POST['snapmin_menutitle'])=="" or !isset($_POST['snapmin_type']) or trim($_POST['snapmin_type'])=="" ){
			$this->errors[] = __("All fields must be filled out!");
		}
		if(sizeof($this->errors)<=0 and isset($_POST['snapmin_slug']) and preg_match("/^[A-Za-z0-9_-]+$/", $_POST['snapmin_slug'])){
			$temp = $this->xml->pages[0]->addChild('page');
			$temp->addChild('title',$_POST['snapmin_title']);
			$temp->addChild('menuTitle',$_POST['snapmin_menutitle']);
			$temp->addChild('type',$_POST['snapmin_type']);			
			$temp->addChild('menuSlug',$_POST['snapmin_slug']);
			
			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($this->xml->asXML());
			$dom->save(SNAPMIN_XML_FILE);
		}
		else if(sizeof($this->errors)<=0){
			$this->errors[] = __("Menu Slug can only contain hyphens, underscores and alphanumeric characters");
		}
	}
	
	function saveEdit(){
		if(!isset($_POST['snapmin_slug']) or trim($_POST['snapmin_slug'])=="" or !isset($_POST['snapmin_title']) or trim($_POST['snapmin_title'])==""  or !isset($_POST['snapmin_menutitle']) or trim($_POST['snapmin_menutitle'])=="" or !isset($_POST['snapmin_type']) or trim($_POST['snapmin_type'])=="" ){
			$this->errors[] = __("All fields must be filled out!");
		}
		if(sizeof($this->errors)<=0 and isset($_POST['snapmin_slug']) and preg_match("/^[A-Za-z0-9_-]+$/", $_POST['snapmin_slug'])){
			$id = (int)$_REQUEST['edit']-1;
			$page = $this->xml->pages[0]->page[$id];
			
			$page->title = $_POST['snapmin_title'];
			$page->menuTitle = $_POST['snapmin_menutitle'];
			$page->menuSlug = $_POST['snapmin_slug'];
			$page->type = $_POST['snapmin_type'];
			
			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($this->xml->asXML());
			$dom->save(SNAPMIN_XML_FILE);			
		}
		else if(sizeof($this->errors)<=0){
			$this->errors[] = __("Menu Slug can only contain hyphens, underscores and alphanumeric characters");
		}
	}
	
	function saveDelete(){
		unset($this->xml->pages[0]->page[$_REQUEST['delete']-1]);
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($this->xml->asXML());
		$dom->save(SNAPMIN_XML_FILE);		
			?>			
				<div class='updated settings-error'> 
					<p>
						<strong>
							<?php _e('Successfully Deleted.');?>
						</strong>
					</p>
				</div>
			<?php		
	}
	
	function renderMainTable(){
		?>
		<div id="col-right"> 
				<div class="col-wrap">
				<table class="widefat fixed" cellspacing="0"> 				
					<thead> 
						<tr> 	
						<th scope="col"  class="manage-column" ><?php _e("Menu Title")?></th> 
						<th scope="col"  class="manage-column" ><?php _e("Page Title")?></th> 
						<th scope="col"  class="manage-column" ><?php _e("Page Type")?></th> 
						</tr>
					</thead>
					
					<?php
					
		$pages = $this->xml->pages;
		$count = 1;
		foreach($pages as $pagesa){
			foreach($pagesa as $page){
			?><tr>
				<td>				
					<strong>
					<a class="row-title" href="admin.php?page=snapmin_editor&edit=<?php echo $count;?>" title="Edit">
											
					<?php echo $page->menuTitle;?>
					
					</a>
					</strong>
					
					<br />
					<div class="row-actions">
						<span class='edit'>
							<a href="admin.php?page=snapmin_editor&edit=<?php echo $count;?>">
								<?php _e('Edit');?>
							</a> | </span>
						<span class='editoptions'>
							<a href="admin.php?page=snapmin_editor&edit=<?php echo $count;?>&do=options">
								<?php _e('Manage Page Options');?>
							</a> | </span>
						<span class='delete'>
							<a class='delete-tag' href='admin.php?page=snapmin_editor&delete=<?php echo $count;?>'>
								<?php _e('Delete');?>
							</a>
						</span>
					</div>				
				</td>
				<td>
					<?php echo $page->title;?>
				</td>
				<td>
					<?php echo $page->type;?>
				</td>
			</tr>
			<?php
			$count++;
			}
		}
		?>			
					<tfoot> 
						<tr> 	
						<th scope="col"  class="manage-column" ><?php _e("Menu Title")?></th> 
						<th scope="col"  class="manage-column" ><?php _e("Page Title")?></th> 
						<th scope="col"  class="manage-column" ><?php _e("Page Type")?></th> 
						</tr>
					</tfoot>
				
				</table>
				</div>
			</div>
		<?php
	}
	
	function renderPageForm( $title, $menuTitle, $type, $menuSlug, $id, $do ){
	?>
		<div id="col-left"> 
				<div class="col-wrap"> 
					
					<div class="form-wrap"> 
					<h3><?php echo ($do=='edit'?__('Edit Page'):__('Add New Page'));?></h3> 
					<form id="additem" method="post"  action="admin.php?page=snapmin_editor&do=<?php echo $do.(($do=='edit')?'&edit='.($id):"");?>"> 
						<?php wp_nonce_field("snapmin_editor","snapmin_editor_wpnonce"); ?> 
						<input type = "hidden" name="do" value = "<?php echo $do;?>"/>
						<?php
						if( $id > 0 ){
						?>
							<input type = "hidden" name="edit" value = "<?php echo ($id);?>"/>
						<?php
						}
						?>
						
						<div class="form-field"> 
							<label for="snapmin_menutitle">
								<?php _e('Menu Title:');?>
							</label> 
							<input name="snapmin_menutitle" id="snapmin_menutitle" type="text" value="<?php echo $menuTitle;?>" size="40" aria-required="true" /> 
								<p>
									<?php _e('The title shown in the menu of admin panel.');?>
								</p>
						</div>  
						
						<div class="form-field"> 
							<label for="snapmin_title">
								<?php _e('Page Title:');?>
							</label> 
							<input name="snapmin_title" id="snapmin_title" type="text" value="<?php echo $title;?>" size="40" aria-required="true" /> 
								<p>
									<?php _e('The title shown on actual admin page.');?>
								</p>
						</div>  
						
						<div class="form-field"> 
							<label for="snapmin_slug">
								<?php _e('Menu Slug:');?>
							</label> 
							<input name="snapmin_slug" id="snapmin_slug" type="text" value="<?php echo $menuSlug;?>" size="40" aria-required="true" /> 
								<p>
									<?php _e('A unique identifier that can only contain hyphens, underscores and alphanumeric characters.');?>
								</p>
						</div>  
						
						<div class="form-field"> 
							<label for="snapmin_type">
								<?php _e('Page Type:');?>
							</label> 
							 
							<select name="snapmin_type" id="snapmin_type"> 
							<?php
								include(SNAPMIN_LOCAL."/includes/pages/Pages.php");
								foreach( $SnapPages as $page => $options){
									?>
									<option value = "<?php echo $page; ?>" <?php echo ($type == $page)? "selected='selected'" : ""; ?> ><?php echo $options['name'];?></option>
									<?php
								}									
							?>
							</select>		
							<p>
								<?php _e('The type of admin page you wish to create');?>
							</p>							
						</div>
						<p class="submit">
							<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Page');?>" /> 
						</p>
					</form>
				</div>
					
				</div>
			</div>

	<?php
	}
	
	function renderHead(){
	?>
	<div class="wrap"> 
		<div id="icon-options-general" class="icon32"><br /></div> 
		<h2><?php _e("Snapmin Page Builder");?></h2> 
		<br/>
		<div id="col-container"> 
		<?php 
		$this->renderNotifications(); 
	}
	
	function renderNotifications(){
	
		if(sizeof($this->errors)<=0 and $this->posted)
			{					
			?>			
				<div class='updated settings-error'> 
					<p>
						<strong>
							<?php _e('Successfully Saved.');?>
						</strong>
					</p>
				</div>
			<?php
			}
			else if(sizeof($this->errors)>0)
			{
			?>
				<div class='error settings-error'> 
					<p>
						<strong>
							<?php _e('Some errors have occured:');?>							
						</strong>
						<ul>
							<?php
							foreach($this->errors as $error)
							{
								?>
									<li> - <?php echo $error;?></li>
								<?php		
							}
							?>
						</ul>
					</p>
				</div>
			<?php			
			}		
	
	}
	
	function renderFoot(){
		echo $this->footScripts;
		?>
		</div>
		</div><?php
	}

};


?>